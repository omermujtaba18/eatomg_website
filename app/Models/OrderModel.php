<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\RestaurantModel;
use App\Models\OrderItemModel;
use App\Models\OrderItemAddonModel;
use App\Models\OrderItemModifierModel;
use DateTime;

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
        'order_payment_type'
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


    public function chargeCard($rest_id, $amount, $card, $customerId, $orderId)
    {

        $restaurant = $this->restaurant->where('rest_id', $rest_id)->first();

        // 5424 0000 0000 0015
        // 2020/12
        // 999
        $auth = [
            "name" => $restaurant['rest_api_id'],
            "transactionKey" => $restaurant['rest_api_key']
        ];

        $ch = curl_init();
        $data = ["createTransactionRequest" => [
            "merchantAuthentication" => $auth,
            "refId" => $orderId,
            "transactionRequest" => [
                "transactionType" => "authCaptureTransaction",
                "amount" => $amount,
                "payment" => [
                    "creditCard" => $card
                ],
                "order" => [
                    "invoiceNumber" => $orderId
                ],
                "poNumber" =>  $orderId,
                "customer" => [
                    "id" => $customerId
                ],
                "transactionSettings" => [
                    "setting" => [
                        "settingName" => "duplicateWindow",
                        "settingValue" => "0"
                    ]
                ],
            ]
        ]];

        curl_setopt($ch, CURLOPT_FORBID_REUSE, TRUE);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_URL, getEnv('CARD_API_URL'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $result = curl_exec($ch);

        function remove_utf8_bom($text)
        {
            $bom = pack('H*', 'EFBBBF');
            $text = preg_replace("/^$bom/", '', $text);
            return $text;
        }

        $json = remove_utf8_bom($result);
        $response = json_decode($json, TRUE);
        curl_close($ch);
        return $response['messages']['resultCode'] == "Ok" ?
            [1, $response['transactionResponse']['transId']] :
            [0, $response['transactionResponse']['errors'][0]['errorText']];
    }


    public function createOrder($cart, $cus_id, $rest_id, $payment_id, $type = null, $pay_type, $order_num)
    {
        $placed_at = new DateTime();
        $order_data = [
            'order_num' => $order_num,
            'cus_id' => $cus_id,
            'order_discount' => !empty($cart['cart_discount']) ? $cart['cart_discount'] : 0,
            'order_subtotal' => $cart['cart_subtotal'],
            'order_tax' => $cart['cart_tax'],
            'order_total' => $cart['cart_total'],
            'order_type' => $type,
            'placed_at' => $placed_at->format('Y-m-d H:i:s'),
            'deliver_at' => $placed_at->modify('+30 minutes')->format('Y-m-d H:i:s'),
            'order_instruct' => $cart['instruct'],
            'rest_id' => $rest_id,
            'order_payment_type' => $pay_type
        ];
        $order_id = $this->insert($order_data);

        foreach ($cart['items'] as $item) {

            $order_item_id = $this->order_item->insert([
                'order_id' => $order_id,
                'item_id' => $item['item_id'],
                'order_item_quantity' => $item['item_quantity']
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
