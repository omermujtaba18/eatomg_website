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
use App\Models\PromotionModel;
use App\Models\RestaurantModel;
use CodeIgniter\Controller;
use CodeIgniter\I18n\Time;
use DateTime;

use \PayPalCheckoutSdk\Core\PayPalHttpClient;
use \PayPalCheckoutSdk\Core\SandboxEnvironment;
use \PayPalCheckoutSdk\Core\ProductionEnvironment;
use \PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use \PayPalHttp\HttpException;

define('PAYMENT_METHOD_PAYPAL', 'PAYPAL');
define('PAYMENT_METHOD_CARD', 'CARD');
define('PAYMENT_METHOD_CASH', 'CASH');
define('ORDER_TYPE', 'WEBSITE');

class Order extends Controller
{
    var $db, $category, $item, $modifier,
        $order, $customer, $addon, $session, $categories,
        $itemmodifier, $itemAddon, $modifierGroup, $addonGroup,
        $order_item, $order_item_modifier, $order_item_addon,
        $promotion, $restaurant;



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
                ],
                instruct: "",
                cart_subtotal: ",
                cart_tax:"",
                cart_total:"",
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
        $this->categories = $this->category->where(['rest_id' => getEnv('REST_ID'), 'is_show' => 1])->orderBy('priority', 'asc')->findAll();
        $this->itemmodifier = new ItemModifierModel();
        $this->itemAddon = new ItemAddonModel();
        $this->modifierGroup = new ModifierGroupModel();
        $this->addonGroup = new AddOnGroupModel();
        $this->order_item = new OrderItemModel();
        $this->order_item_modifier = new OrderItemModifierModel();
        $this->order_item_addon = new OrderItemAddonModel();
        $this->promotion = new PromotionModel();
        $this->restaurant = new RestaurantModel();
        $this->session = session();
    }

    // Route: /order-now , /order-now/category
    public function index($category_slug = NULL)
    {
        /* Header Data: Total Orders, Header Type, Customer ID(if login),  */
        $data['cart_total'] = $this->session->has('cart') ? count($this->session->get('cart')['items']) : 0;
        $data['header'] = "header-layout2";
        $data['cus_id'] = $this->session->has('cus_id') ?  $this->session->cus_id : NULL;
        $data['restaurant'] = $this->restaurant->find(getEnv('REST_ID'));

        /* List of all categories */
        $data["categories"] = $this->categories;

        /* First Category, if category_slug is not set */
        $data['category'] = $this->category->where('rest_id', getEnv('REST_ID'))->first();
        $data['items'] = $this->item->where(['category_id' => $data['category']['category_id']])->findAll();

        /* Check if category slug is set, and return that category items*/
        if (isset($category_slug)) {
            $data['category'] = $this->category->where(['category_slug' => $category_slug, 'rest_id' => getEnv('REST_ID')])->findAll()[0];
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

    public function add_to_cart($item, $quantity, $instruct, $modifier = [], $modifier_price = 0, $addon = [], $addon_price = 0)
    {
        if (!$this->session->has('cart')) {
            $cart['items'] = [];
            $this->session->set('cart', $cart);
        }

        $cart = $this->session->get('cart');
        $cart_item['item_id'] = $item['item_id'];
        $cart_item['item_name'] = $item['item_name'];
        $cart_item['item_price'] = $item['item_price'] + $modifier_price + $addon_price;
        $cart_item['item_total'] = $quantity * ($cart_item['item_price']);
        $cart_item['item_quantity'] = intval($quantity);
        $cart_item['item_instruct'] = $instruct;
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

                    if ($modifier_group['multi_select']) {
                        foreach ($value as $k => $v) {
                            $selected_modifier = $this->modifier->where(['modifier_group_id' => $key, 'modifier_item' => $v])->first();
                            $modifier['modifier_group_id'] = $key;
                            $modifier['modifier_group_instruct'] = $modifier_group['modifier_group_instruct'];
                            $modifier['modifier_id'] = $selected_modifier['modifier_id'];
                            $modifier['modifier_item'] = $selected_modifier['modifier_item'];
                            $modifier['modifier_price'] = $selected_modifier['modifier_price'];
                            $modifier_price += $modifier['modifier_price'];

                            array_push($modifier_array, $modifier);
                        }
                    } else {
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
            }
            if (!empty($request['a'])) {
                foreach ($request['a'] as $key => $value) {
                    $addon_group = $this->addonGroup->where('addon_group_id', $key)->first();

                    if ($addon_group['multi_select']) {

                        foreach ($value as $k => $v) {
                            $selected_addon = $this->addon->where(['addon_group_id' => $key, 'addon_item' => $v])->first();
                            $addon['addon_group_id'] = $key;
                            $addon['addon_group_instruct'] = $addon_group['addon_group_instruct'];
                            $addon['addon_id'] = $selected_addon['addon_id'];
                            $addon['addon_item'] = $selected_addon['addon_item'];
                            $addon['addon_price'] = $selected_addon['addon_price'];
                            $addon_price += $addon['addon_price'];
                            array_push($addon_array, $addon);
                        }
                    } else {
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
            }

            $item = $this->item->find($item_id);
            $this->add_to_cart($item, $this->request->getPost('quantity'), $this->request->getPost('instruction'), $modifier_array, $modifier_price, $addon_array, $addon_price);
            $this->session->set('message', '<strong>"' . $item['item_name'] . '"<strong> added to cart.');
            return redirect()->to('/order-now/' . $category_slug);
        }

        /* Header Data: Total Orders, Header Type, Customer ID(if login),  */
        $data['cart_total'] = $this->session->has('cart') ? count($this->session->get('cart')['items']) : 0;
        $data['header'] = "header-layout2";
        $data['cus_id'] = $this->session->has('cus_id') ? $this->session->cus_id : NULL;
        $data['restaurant'] = $this->restaurant->find(getEnv('REST_ID'));

        /* List of all categories */
        $data["categories"] = $this->categories;

        /* Search category by slug and return all items */
        $data['category'] = $this->category->where(['category_slug' => $category_slug, 'rest_id' => getEnv('REST_ID')])->first();
        $data['title'] = $data['category']['category_name'];

        /* Get item by item_id */
        $data['item'] =  $this->item->find($item_id);

        /* Get all modifiers attached to the item */
        $item_modifier = $this->itemmodifier->where('item_id', $item_id)->findAll();

        /* Get all addons attached to the item */
        $item_addon = $this->itemAddon->where('item_id', $item_id)->findAll();

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
        if ($this->request->getGet('promo')) {
            $promo_code = $this->request->getGet('promo');
            $promotion = $this->promotion->where([
                'promo_code' => trim($promo_code),
                'is_active' => 1,
                'rest_id' => getEnv('REST_ID')
            ])->first();

            if (!empty($promotion)) {
                $cart = $this->session->get('cart');
                $cart['cart_promo']['type'] = $promotion['promo_type'];
                $cart['cart_promo']['amount'] = $promotion['promo_amount'];
                $this->session->set('cart', $cart);
            }
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
                $cart_subtotal += ($item['item_quantity'] * $item['item_price']);
                $cart_item[$key] = $item;
            }
            $cart['instruct'] = $this->request->getPost('instruct');
            $cart['items'] = $cart_item;
            $cart['cart_subtotal'] = round($cart_subtotal, 2);
            if (!empty($cart['cart_promo']['type'])) {
                if ($cart['cart_promo']['type'] == 'percent') {
                    $cart['cart_discount'] = round($cart_subtotal * $cart['cart_promo']['amount'], 2);
                } else {
                    $cart['cart_discount'] = round($cart['cart_promo']['amount'], 2);
                }
            }
            $cart['cart_tax'] = round(($cart_subtotal * 11.5 / 100), 2);
            $cart['cart_total'] = $cart_subtotal + $cart['cart_tax'];
            if (!empty($cart['cart_discount'])) {
                $cart['cart_total'] -= $cart['cart_discount'];
            }
            $cart['cart_total'] = round($cart['cart_total'], 2);

            $this->session->set('cart', $cart);
            return redirect()->to('/checkout');
        }


        $data['header'] = "header-layout2";
        $data['cus_id'] = $this->session->has('cus_id') ?  $this->session->cus_id : NULL;
        $data['title'] = 'Checkout';
        $data['cart'] = $this->session->get('cart');
        $data['restaurant'] = $this->restaurant->find(getEnv('REST_ID'));

        $this->session->has('show_push') ? $this->session->set('show_push', 0) : $this->session->set('show_push', 1);

        $data['show_push'] = $this->session->show_push;

        $data['items'] = $this->item->where(['rest_id' => getEnv('REST_ID'), 'push_item' => 1])->findAll();
        $data['category'] = $this->category->where(['rest_id' => getEnv('REST_ID'), 'category_id' => $data['items'][0]['category_id']])->first();


        echo view('templates/header', $data);
        echo view('order/checkout', $data);
        echo view('templates/footer', $data);
    }


    public function return_paypal()
    {
        echo "Return";
    }

    public function payByPaypal()
    {
        $cart = $this->session->cart;
        $clientId = getEnv('CLIENT_ID');
        $clientSecret = getEnv('CLIENT_SECRET');
        $order_num = round(microtime(true) * 1000);

        $environment = getEnv('CI_ENVIRONMENT') == 'development' ?
            new SandboxEnvironment($clientId, $clientSecret) : new ProductionEnvironment($clientId, $clientSecret);
        $client = new PayPalHttpClient($environment);
        $total =  number_format($cart['cart_total'], 2, '.', '');

        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
            "intent" => "CAPTURE",
            "purchase_units" => [[
                'reference_id' => $order_num,
                "amount" => [
                    "value" => $total,
                    "currency_code" => "USD"
                ]
            ]],
            "application_context" => [
                "cancel_url" => base_url() . "/checkout",
                "return_url" => base_url() . "/checkout"
            ]
        ];

        try {
            $response = $client->execute($request);
            return json_encode($response);
        } catch (HttpException $ex) {
            echo $ex->statusCode;
            print_r($ex->getMessage());
        }
    }


    // Route: /checkout
    public function checkout()
    {
        if ($this->request->getPost()) {
            $order_num = round(microtime(true) * 1000);
            $payment_id = $this->request->getPost('card') ? $this->request->getPost('card') : $this->request->getPost('paypal');
            $cus_id = $this->request->getPost('cus_id') ? $this->request->getPost('cus_id') : $this->customer->where(['cus_email' => $this->request->getPost('email')])->first()['cus_id'];
            if (empty($cus_id)) {
                $cus_id = $this->customer->insert([
                    'cus_name' => $this->request->getPost('name'),
                    'cus_email' => $this->request->getPost('email'),
                    'cus_phone' => $this->request->getPost('phone'),
                ]);
            }
            $cart = $this->session->cart;

            if ($this->request->getPost('paypal') != '0') {
                $payment_id = $this->request->getPost('paypal');
                $order_id = $this->order->createOrder($cart, $cus_id, $payment_id, ORDER_TYPE, PAYMENT_METHOD_PAYPAL, $order_num);
            } else if ($this->request->getPost('card') != '0') {
                $order_id = $this->order->createOrder($cart, $cus_id, $payment_id, ORDER_TYPE, PAYMENT_METHOD_CARD, $order_num);
            } else {
                $order_id = $this->order->createOrder($cart, $cus_id, '', ORDER_TYPE, PAYMENT_METHOD_CASH, $order_num);
            }

            $this->session->set('order_id', $order_id);
            return redirect()->to('/checkout/confirmation');
        }

        $data['header'] = "header-layout2";
        $data['cus_id'] = $this->session->has('cus_id') ?  $this->session->cus_id : NULL;
        $data['title'] = 'Payment';
        $data['restaurant'] = $this->restaurant->find(getEnv('REST_ID'));

        $data['discount'] = !empty($this->session->get('cart')['cart_discount']) ? $this->session->get('cart')['cart_discount'] : 0;
        $data['subtotal'] = $this->session->get('cart')['cart_subtotal'];
        $data['tax'] = $this->session->get('cart')['cart_tax'];
        $data['total'] = $this->session->get('cart')['cart_total'];

        \Stripe\Stripe::setApiKey(getEnv('STRIPE_SECRET_KEY'));

        $data['intent'] = \Stripe\PaymentIntent::create([
            'payment_method_types' => ['card'],
            'amount' => $data['total'] * 100,
            'currency' => 'usd',
            'application_fee_amount' => (round($data['total'] * 0.029 + 0.8, 2)) * 100,
            'transfer_data' => [
                'destination' => getEnv('STRIPE_REST_KEY'),
            ]
        ]);

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
            $data['rest'] = $this->restaurant->find($data['order']['rest_id']);
            $data['customer'] = $this->customer->find($data['order']['cus_id']);
            $data['cart'] = $this->session->get('cart');
            $this->send_email_restaurant($id, $data);
            $this->send_email_customer($id, $data);
            $this->session->remove('cart');
        }
        $data['header'] = "header-layout2";
        $data['cus_id'] = $this->session->has('cus_id') ?  $this->session->cus_id : NULL;
        $data['title'] = 'Payment';
        $data['restaurant'] = $this->restaurant->find(getEnv('REST_ID'));

        echo view('templates/header', $data);
        echo view('order/confirmation', $data);
        echo view('templates/footer', $data);
    }

    // Route: /select-restauarant
    public function select_restauarant()
    {
        /* Header Data: Total Orders, Header Type, Customer ID(if login),  */
        $data['header'] = "header-layout2";
        $data['cus_id'] = $this->session->has('cus_id') ?  $this->session->cus_id : NULL;
        $data['title'] = '';
        $data['restaurant'] = $this->restaurant->findAll();

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

    public function send_email_restaurant($id, $data)
    {
        $email = \Config\Services::email();
        $email->setFrom('orders@ninetofab.com', $data['rest']['rest_name']);
        $email->setTo($data['rest']['rest_email']);
        $email->setCC('ah@morango.net,pekin@eatomg.com,omer.mujtaba96@gmail.com');
        $subject = '[Online Order#' . $data['order']['order_num'] . ']: ' . $data['rest']['rest_name'];
        $email->setSubject($subject);
        $email->setMessage(view('templates/order_email_rest', $data));

        if (!$email->send(false)) {
            $email->printDebugger();
        }
        $email->clear();
    }

    public function send_email_customer($id, $data)
    {
        $email = \Config\Services::email();
        $email->setFrom($data['rest']['rest_email'], $data['rest']['rest_name']);
        $email->setTo($data['customer']['cus_email']);
        $email->setSubject('Your order has been placed!');
        $email->setMessage(view('templates/order_email_customer', $data));

        if (!$email->send(false)) {
            $email->printDebugger();
        }

        $email->clear();
    }
}