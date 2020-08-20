<?php

namespace App\Controllers;

use App\Models\AddOnModel;
use App\Models\CategoryModel;
use App\Models\CustomerModel;
use App\Models\ItemModel;
use App\Models\ItemModifierModel;
use App\Models\ItemAddonModel;
use App\Models\ModifierModel;
use App\Models\OrderModel;
use App\Models\AddOnGroupModel;
use App\Models\ModifierGroupModel;
use App\Models\OrderItemModel;
use App\Models\OrderItemModifierModel;
use App\Models\OrderItemAddonModel;

use App\Models\RestaurantModel;
use App\ThirdParty\CreateOrder;
use App\ThirdParty\GetOrder;
use CodeIgniter\Controller;
use CodeIgniter\I18n\Time;
use DateTime;

class Order extends Controller
{
    var $db, $category, $item, $modifier,
        $order, $customer, $addon, $session, $categories,
        $itemmodifier, $itemAddon, $modifierGroup, $addonGroup,
        $order_item, $order_item_modifier, $order_item_addon;

    /* 
    Cart Object
        {
            items:[
                [0]: [
                        item_id,
                        item_name,
                        item_price,
                        item_quantity,
                        item_total,
                        modifier: [
                            [0]: [
                                modifier_group_id,
                                modifier_group_instruct
                                modifier_id
                                modifier_item,
                                modifier_price
                            ]
                        ],
                        addon:[
                            [0]: [
                                addon_group_id,
                                addon_group_instruct
                                addon_id,
                                addon_item,
                                addon_price
                            ]
                        ]
                    ], 
                ]
            ]
        }
    */

    public function __construct()
    {
        $this->db = db_connect();
        $this->item = new ItemModel();
        $this->modifier = new ModifierModel();
        $this->category = new CategoryModel();
        $this->order = new OrderModel();
        $this->customer = new CustomerModel();
        $this->addon = new AddOnModel();
        $this->categories = $this->category->findAll();
        $this->itemmodifier = new ItemModifierModel();
        $this->itemAddon = new ItemAddonModel();
        $this->modifierGroup = new ModifierGroupModel();
        $this->addonGroup = new AddOnGroupModel();
        $this->order_item = new OrderItemModel();
        $this->order_item_modifier = new OrderItemModifierModel();
        $this->order_item_addon = new OrderItemAddonModel();
        $this->session = session();
    }

    // Route: /order-now , /order-now/category
    public function index($category_slug = NULL)
    {
        /* Header Data: Total Orders, Header Type, Customer ID(if login),  */
        $data['cart_total'] = $this->session->has('cart') ? count($this->session->get('cart')['items']) : 0;
        $data['header'] = "header-layout2";
        $data['cus_id'] = $this->session->has('cus_id') ?  $this->session->cus_id : NULL;

        /* List of all categories */
        $data["categories"] = $this->categories;

        /* First Category, if category_slug is not set */
        $data['category'] = $this->category->first();
        $data['items'] = $this->item->where(['category_id' => $data['category']['category_id']])->findAll();

        /* Check if category slug is set, and return that category items*/
        if (isset($category_slug)) {
            $data['category'] = $this->category->where(['category_slug' => $category_slug])->findAll()[0];
            $data['items'] = $this->item->where(["category_id" => $data['category']['category_id']])->findAll();
        }

        $data['title'] = $data['category']['category_name'];
        $data['content'] = view('order/item_list', $data);
        echo view('templates/header', $data);
        echo view('order/order', $data);
        echo view('templates/footer', $data);

        if ($this->session->has('message')) {
            $this->session->remove('message');
        }
    }

    public function add_to_cart($item, $modifier = [], $modifier_price = 0, $addon = [], $addon_price = 0)
    {
        if (!$this->session->has('cart')) {
            $cart['items'] = [];
            $this->session->set('cart', $cart);
        }

        $cart = $this->session->get('cart');
        $cart_item['item_id'] = $item['item_id'];
        $cart_item['item_name'] = $item['item_name'];
        $cart_item['item_price'] = $item['item_price'];
        $cart_item['item_total'] = $item['item_price'] + $modifier_price + $addon_price;
        $cart_item['item_quantity'] = 1;
        $cart_item['modifier'] = $modifier;
        $cart_item['addon'] = $addon;

        array_push($cart['items'], $cart_item);
        $this->session->set('cart', $cart);
    }

    public function remove_from_cart($id)
    {
        $cart = $this->session->get('cart');
        $cart_item = $cart['items'];
        unset($cart_item[$id]);
        $cart['items'] = $cart_item;
        $this->session->set('cart', $cart);
        return redirect()->to('/cart');
    }

    // Route: order-now/category_slug/item_id
    public function item_by_id($category_slug = NULL, $item_id = NULL)
    {
        $request = $this->request->getPost();
        if ($request) {
            $modifier_array = [];
            $modifier_price = 0;
            $addon_array = [];
            $addon_price = 0;

            if (!empty($request['m'])) {
                foreach ($request['m'] as $key => $value) {
                    $modifier_group = $this->modifierGroup->where('modifier_group_id', $key)->first();
                    $selected_modifier = $this->modifier->where(['modifier_group_id' => $key, 'modifier_item' => $value])->first();

                    $modifier['modifier_group_id'] = $key;
                    $modifier['modifier_group_instruct'] = $modifier_group['modifier_group_instruct'];
                    $modifier['modifier_id'] = $selected_modifier['modifier_id'];
                    $modifier['modifier_item'] = $selected_modifier['modifier_item'];
                    $modifier['modifier_price'] = $selected_modifier['modifier_price'];
                    $modifier_price += $modifier['modifier_price'];

                    array_push($modifier_array, $modifier);
                }
            }
            if (!empty($request['a'])) {
                foreach ($request['a'] as $key => $value) {
                    $addon_group = $this->addonGroup->where('addon_group_id', $key)->first();
                    $selected_addon = $this->addon->where(['addon_group_id' => $key, 'addon_item' => $value])->first();

                    $addon['addon_group_id'] = $key;
                    $addon['addon_group_instruct'] = $addon_group['addon_group_instruct'];
                    $addon['addon_id'] = $selected_addon['addon_id'];
                    $addon['addon_item'] = $selected_addon['addon_item'];
                    $addon['addon_price'] = $selected_addon['addon_price'];
                    $addon_price += $addon['addon_price'];

                    array_push($addon_array, $addon);
                }
            }
            $item = $this->item->find($item_id);
            $this->add_to_cart($item, $modifier_array, $modifier_price, $addon_array, $addon_price);
            $this->session->set('message', '<strong>"' . $item['item_name'] . '"<strong> added to cart.');
            return redirect()->to('/order-now/' . $category_slug);
        }

        /* Header Data: Total Orders, Header Type, Customer ID(if login),  */
        $data['cart_total'] = $this->session->has('cart') ? count($this->session->get('cart')['items']) : 0;
        $data['header'] = "header-layout2";
        $data['cus_id'] = $this->session->has('cus_id') ? $this->session->cus_id : NULL;

        /* List of all categories */
        $data["categories"] = $this->categories;

        /* Search category by slug and return all items */
        $data['category'] = $this->category->where(['category_slug' => $category_slug])->first();
        $data['title'] = $data['category']['category_name'];

        /* Get item by item_id */
        $data['item'] =  $this->item->find($item_id);

        /* Get all modifiers attached to the item */
        $item_modifier = $this->itemmodifier->where('item_id', $item_id)->findAll();

        /* Get all addons attached to the item */
        $item_addon = $this->itemAddon->where('item_id', $item_id)->findAll();

        /* If not modifier or addon is attached to the item */
        if (empty($item_modifier) && empty($item_addon)) {
            $this->add_to_cart($data['item']);
            $this->session->set('message', '<strong>"' . $data['item']['item_name'] . '"<strong> added to cart.');
            return redirect()->to('/order-now/' . $category_slug);
        }

        /* If modifier is attached to the item */
        if (!empty($item_modifier)) {
            $data['item_modifier'] = $item_modifier;
            $data['modifierGroup'] = $this->modifierGroup;
            $data['modifier'] = $this->modifier;
        }
        /* If addon is attached to the item */
        if (!empty($item_addon)) {
            $data['item_addon'] = $item_addon;
            $data['addonGroup'] = $this->addonGroup;
            $data['addon'] = $this->addon;
        }

        $data['content'] = view('order/item', $data);
        echo view('templates/header', $data);
        echo view('order/order', $data);
        echo view('templates/footer', $data);
    }

    // Route /cart
    public function cart()
    {
        if ($this->request->getPost('promo')) {
            $this->session->set('promo', '0.25');
        }

        if (!$this->session->has('cart')) {
            return redirect()->to('/order-now');
        }

        if ($this->request->getPost()) {
            $cart = $this->session->get('cart');
            $cart_item = $cart['items'];
            $cart_subtotal = 0;

            foreach ($cart_item as $key => $value) {
                $item = $cart_item[$key];
                $item['item_quantity'] = $this->request->getPost($key);
                $cart_subtotal += ($item['item_quantity'] * $item['item_total']);
                $cart_item[$key] = $item;
            }
            $cart['instruct'] = $this->request->getPost('instruct');
            $cart['items'] = $cart_item;
            $cart['cart_subtotal'] = $cart_subtotal;
            $cart['cart_tax'] = round(($cart_subtotal * 11.5 / 100), 2);
            $cart['cart_total'] = $cart_subtotal + $cart['cart_tax'];
            $this->session->set('cart', $cart);
            return redirect()->to('/checkout/payment');
        }


        $data['header'] = "header-layout2";
        $data['cus_id'] = $this->session->has('cus_id') ?  $this->session->cus_id : NULL;
        $data['title'] = 'Checkout';
        $data['cart'] = $this->session->get('cart');

        echo view('templates/header', $data);
        echo view('order/checkout', $data);
        echo view('templates/footer', $data);
    }

    // TODO: Paypal
    public function pay_by_payapl()
    {
        $orderNum = round(microtime(true) * 1000);
        $body = json_decode($this->request->getBody());
        // $getOrder = new GetOrder();
        // $order = $getOrder->getOrder($body->orderID);

        $cus_id = $this->session->has('cus_id') ? $this->session->get('cus_id') : -1;

        if ($cus_id == -1) {
            $customerData = [
                'cus_name' => $body->cus_name,
                'cus_email' => $body->cus_email,
                'cus_phone' => $body->cus_phone,
            ];
            $cus_id = $this->customerModel->insert($customerData, true);
        }

        $data = [
            'order_num' => $orderNum,
            'cus_id'    => $cus_id,
            'order_placed_time' => new Time('now', 'America/Chicago', 'en_US'),
            'order_delivery_time' => new Time('now +30 minute', 'America/Chicago', 'en_US'),
            'order_discount' => $this->session->has('promo') ? $this->session->get('promo') : '',
            'order_subtotal' => $this->session->get('subtotal'),
            'order_tax' => $this->session->get('tax'),
            'order_total' => $this->session->get('total'),
            'order_status' => 'Pending',
            'order_type' => 'TAKEOUT',
            'order_instruct' => '',
            'rest_id' => $body->restID,
            'order_complete' => '0',
            'order_payment_type' => 'PAYPAL'
        ];

        $order = $this->orderModel->insert($data, true);
        $this->session->set('order_id', $order);

        $cart = $this->session->get('cart')['cart'];

        foreach ($cart as $item) {

            $item_id = $item['item_id'];
            $item_qty = $item['item_qty'];
            $item_modifier = $item['modifier'];
            $item_cur = $this->itemModel->find($item_id);
            $item_price = $item_cur['item_price'];
            $item_addon = array();

            if (!empty($item['addon'])) {
                foreach ($item['addon'] as $a) {
                    $item_price += $a['addon_price'];
                    array_push($item_addon, $a['selected']);
                }
            }

            $item_addon = implode(",", $item_addon);

            $this->db->query("INSERT INTO `eatomg`.`order_items` (`order_num`, `item_id`, `order_item_quantity`, `modifier`, `addon`,`order_item_price`) VALUES ('$orderNum', '$item_id' , '$item_qty' , '$item_modifier' , '$item_addon','$item_price');");
        }

        return $this->response->setStatusCode(200);
    }

    public function return_paypal()
    {
        echo "Return";
    }

    public function create_order($cart, $cus_id, $rest_id, $payment_id)
    {
        $order_num = round(microtime(true) * 1000);

        $order_data = [
            'order_num' => $order_num,
            'cus_id' => $cus_id,
            'order_discount' => 0,
            'order_subtotal' => $cart['cart_subtotal'],
            'order_tax' => $cart['cart_tax'],
            'order_total' => $cart['cart_total'],
            'order_type' => '',
            'order_instruct' => $cart['instruct'],
            'rest_id' => $rest_id,
        ];
        $order_id = $this->order->insert($order_data);

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

    // Route: /checkout/payment
    public function payment()
    {
        if ($this->request->getPost()) {

            //TODO: Perform transaction on card, waiting for APIs

            // Create Order if transaction successful
            $cus_id = $this->request->getPost('cus_id');

            if (empty($cus_id)) {
                $cus_id = $this->customer->insert([
                    'cus_name' => $this->request->getPost('name'),
                    'cus_email' => $this->request->getPost('email'),
                    'cus_phone' => $this->request->getPost('phone'),
                ]);
            }

            $rest_id = $this->request->getPost('rest_id');
            $order_id = $this->create_order($this->session->cart, $cus_id, $rest_id, 0);
            $this->session->remove('cart');
            $this->session->set('order_id', $order_id);

            return redirect()->to('/checkout/confirmation');
        }

        $data['header'] = "header-layout2";
        $data['cus_id'] = $this->session->has('cus_id') ?  $this->session->cus_id : NULL;
        $data['title'] = 'Payment';

        if ($this->session->has('promo')) {
            $promo = round($this->session->get('subtotal') * $this->session->get('promo'), 2);
            $total = $this->session->get('subtotal') - $promo + $this->session->get('tax');
            $this->session->set('promo', $promo);
            $this->session->set('total', $total);
            $data['promo'] = $promo;
        }

        $data['subtotal'] = $this->session->get('cart')['cart_subtotal'];
        $data['tax'] = $this->session->get('cart')['cart_tax'];
        $data['total'] = $this->session->get('cart')['cart_total'];

        echo view('templates/header', $data);
        echo view('order/payment', $data);
        echo view('templates/footer', $data);
    }

    // Route: /checkout/confirmation
    public function confirmation()
    {
        if ($this->session->has('order_id')) {
            $id = $this->session->get('order_id');
            $data['order'] = $this->order->find($id);
        }
        $data['header'] = "header-layout2";
        $data['cus_id'] = $this->session->has('cus_id') ?  $this->session->cus_id : NULL;
        $data['title'] = 'Payment';

        echo view('templates/header', $data);
        echo view('order/confirmation', $data);
        echo view('templates/footer', $data);
    }

    // Route: /takout-catering
    public function select_type()
    {
        /* Header Data: Total Orders, Header Type, Customer ID(if login),  */
        $data['header'] = "header-layout2";
        $data['cus_id'] = $this->session->has('cus_id') ?  $this->session->cus_id : NULL;
        $data['title'] = '';

        echo view('templates/header', $data);
        echo view('order/order_type', $data);
        echo view('templates/footer', $data);
    }

    // Route: /empty_cart
    public function empty_cart()
    {
        $this->session->remove('cart');
        return redirect()->to('/order-now');
    }
}
