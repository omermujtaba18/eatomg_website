<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\RestaurantModel;
use App\Models\OrderItemModel;
use App\Models\OrderItemAddonModel;
use App\Models\OrderItemModifierModel;
use DateTime;
use DateTimeZone;
use ErrorException;
use Stripe\Stripe;

class OrderModel extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'order_id';
    protected $returnType     = 'array';
    protected $allowedFields = [
        'order_num',
        'cus_id',
        'placed_at',
        'deliver_at',
        'order_discount',
        'order_subtotal',
        'order_tax',
        'order_total',
        'order_status',
        'order_type',
        'order_instruct',
        'rest_id',
        'is_complete',
        'order_payment_type',
        'payment_id',
        'business_id'
    ];

    var $restaurant, $order_item, $order_item_modifier, $order_item_addon = null;

    public function __construct()
    {
        parent::__construct();
        $this->restaurant = new RestaurantModel();
        $this->order_item = new OrderItemModel();
        $this->order_item_addon = new OrderItemAddonModel();
        $this->order_item_modifier = new OrderItemModifierModel();
    }

    public function createOrder($cart, $cus_id, $payment_id, $order_type, $payment_method, $order_num)
    {
        $placed_at = new DateTime();
        $placed_at->setTimezone(new DateTimeZone('America/Chicago'));

        $order_data = [
            'order_num' => $order_num,
            'cus_id' => $cus_id,
            'order_discount' => !empty($cart['cart_discount']) ? $cart['cart_discount'] : 0,
            'order_subtotal' => $cart['cart_subtotal'],
            'order_tax' => $cart['cart_tax'],
            'order_total' => $cart['cart_total'],
            'order_type' => $order_type,
            'placed_at' => $placed_at->format('Y-m-d H:i:s'),
            'deliver_at' => $placed_at->modify('+30 minutes')->format('Y-m-d H:i:s'),
            'order_instruct' => $cart['instruct'],
            'rest_id' => getEnv('REST_ID'),
            'order_payment_type' => $payment_method,
            'payment_id' => $payment_id,
            'business_id' => getEnv('BUSINESS_ID')
        ];
        $order_id = $this->insert($order_data);

        foreach ($cart['items'] as $item) {

            $order_item_id = $this->order_item->insert([
                'order_id' => $order_id,
                'item_id' => $item['item_id'],
                'order_item_quantity' => $item['item_quantity'],
                'order_item_note' => $item['item_instruct']
            ]);

            if (!empty($item['modifier'])) {
                foreach ($item['modifier'] as $modifier) {
                    $this->order_item_modifier->insert([
                        'order_item_id' => $order_item_id,
                        'modifier_group_id' => $modifier['modifier_group_id'],
                        'modifier_id' => $modifier['modifier_id']
                    ]);
                }
            }
            if (!empty($item['addon'])) {
                foreach ($item['addon'] as $addon) {
                    $this->order_item_addon->insert([
                        'order_item_id' => $order_item_id,
                        'addon_group_id' => $addon['addon_group_id'],
                        'addon_id' => $addon['addon_id']
                    ]);
                }
            }
        }
        return $order_id;
    }
}
