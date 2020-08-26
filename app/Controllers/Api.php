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

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Controller;


/** 
 * 1. Login 
 * 2. Register 
 * 3. login facebook
 * 4. login google
 * 5. get_menu
 * 6. get_menu_item
 * 7. check_modifier_addon
 * 8. update_password
 * 9. update_account
 * 10. check_promo
 * 11. order_hisotry
 * 12. place_order
 */


class Api extends Controller
{
    use ResponseTrait;

    var $db, $category, $item, $modifier,
        $order, $customer, $addon, $session, $categories,
        $itemModifier, $itemAddon, $modifierGroup, $addonGroup,
        $order_item, $order_item_modifier, $order_item_addon,
        $promotion, $restaurant, $user;

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
        $this->itemModifier = new ItemModifierModel();
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

    public function check_token()
    {
        $error = ['error' => 'Invalid API token'];
        $token = $this->request->getGet('token');

        if (!empty($token)) {
            $customer = $this->customer->where('token', $token)->first();
            if (!empty($customer)) {
                $this->user = $customer;
                return 0;
            }
        }
        return $this->fail($error, 400);
    }

    public function login_user()
    {
        $error = 'Invalid email or password';
        if ($this->request->getPost()) {
            $customer = $this->customer->where([
                'cus_email' => $this->request->getPost('email'),
            ])->first();

            if (!empty($customer)) {
                if (password_verify($this->request->getPost('password'), $customer['cus_password'])) {
                    if (empty($customer['token'])) {
                        $customer['token'] = md5(uniqid($customer['cus_email'], true));
                        $this->customer->save([
                            'cus_id' => $customer['cus_id'],
                            'token' => $customer['token']
                        ]);
                    }
                    return $this->respond($customer, 200);
                } else {
                    $error = 'Invalid password';
                }
            }
        }
        return $this->failNotFound($error);
    }

    public function register_user()
    {
        $error = ['error' => 'Email already exists, Try a different email!'];
        if ($this->request->getPost()) {
            $customer = $this->customer->where('cus_email', $this->request->getPost('email'))->first();

            if (empty($customer)) {
                $cus_id = $this->customer->insert([
                    'cus_name' => $this->request->getPost('name'),
                    'cus_email' => $this->request->getPost('email'),
                    'cus_password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                    'token' => md5(uniqid($customer['cus_email'], true)),
                    'has_register' => 1
                ]);
                $customer = $this->customer->find($cus_id);
                return $this->respondCreated($customer);
            }

            if (!empty($customer['has_register'])) {
                return $this->fail($error, 400);
            }

            $this->customer->save([
                'cus_id' => $customer['cus_id'],
                'cus_password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'has_register' => 1,
                'token' => md5(uniqid($customer['cus_email'], true))
            ]);
            return $this->respondCreated($customer);
        }
        return $this->fail($error, 400);
    }

    // NOT DONE
    public function login_with_facebook()
    {
    }

    // NOT DONE
    public function login_with_google()
    {
    }

    public function get_menu()
    {
        $check_token = $this->check_token();
        if ($check_token) {
            return $check_token;
        }
        if ($this->request->getMethod() == 'get') {
            $menu = [];
            if ($this->request->getGet('type')) {
                $categories = $this->category->where('category_type', $this->request->getGet('type'))->findAll();
                foreach ($categories as $category) {
                    $items = $this->item->where('category_id', $category['category_id'])->findAll();
                    $obj['title'] = $category['category_name'];
                    $obj['data'] = $items;
                    array_push($menu, $obj);
                }
                return $this->respond($menu);
            }
        }
        return $this->failNotFound();
    }

    public function get_menu_item()
    {
        $check_token = $this->check_token();
        if ($check_token) {
            return $check_token;
        }
        if ($this->request->getMethod() == 'get') {
            $item = [];
            if ($this->request->getGet('item_id')) {
                $item = $this->item->find($this->request->getGet('item_id'));

                if (!empty($item)) {
                    $item_modifier = $this->itemModifier->where('item_id', $item['item_id'])->findAll();
                    $item_addon = $this->itemAddon->where('item_id', $item['item_id'])->findAll();
                    $item['modifier'] = [];
                    $item['addon'] = [];

                    if (empty($item_addon) && empty($item_modifier)) {
                        return $this->respond($item);
                    }

                    if (!empty($item_modifier)) {
                        foreach ($item_modifier as $im) {
                            $modifierGroup = $this->modifierGroup->where('modifier_group_id', $im['modifier_group_id'])->findAll();
                            foreach ($modifierGroup as $mg) {
                                $m['modifier_group_instruct'] = $mg['modifier_group_instruct'];
                                $m['modifier_group_id'] = $mg['modifier_group_id'];

                                $m['modifier'] = $this->modifier->where('modifier_group_id', $mg['modifier_group_id'])->findAll();
                            }
                            array_push($item['modifier'], $m);
                        }
                    }

                    if (!empty($item_addon)) {
                        foreach ($item_addon as $ia) {
                            $addonGroup = $this->addonGroup->where('addon_group_id', $ia['addon_group_id'])->findAll();
                            foreach ($addonGroup as $ag) {
                                $a['addon_group_instruct'] = $ag['addon_group_instruct'];
                                $a['addon'] = $this->addon->where('addon_group_id', $ag['addon_group_id'])->findAll();
                            }
                            array_push($item['addon'], $a);
                        }
                    }

                    return $this->respond($item);
                }
            }
        }
        return $this->failNotFound();
    }

    public function check_modifier_or_addon()
    {
        $check_token = $this->check_token();
        if ($check_token) {
            return $check_token;
        }

        if ($this->request->getMethod() == 'get') {

            $modifier = $this->itemModifier->where('item_id', $this->request->getGet('item_id'))->findAll();
            $addon = $this->itemAddon->where('item_id', $this->request->getGet('item_id'))->findAll();

            if (empty($modifier) && empty($addon)) {
                return $this->respond(false);
            }
            return $this->respond(true);
        }
        return $this->failNotFound();
    }

    public function update_password()
    {
        $check_token = $this->check_token();
        if ($check_token) {
            return $check_token;
        }

        if ($this->request->getMethod() == 'post') {
            if (password_verify($this->request->getPost('oldpass'), $this->user['cus_password'])) {
                $this->customer->save([
                    'cus_id' => $this->user['cus_id'],
                    'cus_password' => password_hash($this->request->getPost('newpass'), PASSWORD_DEFAULT),
                ]);
                return $this->respondCreated();
            }
        }
        return $this->failNotFound();
    }


    public function update_account()
    {
        $check_token = $this->check_token();
        if ($check_token) {
            return $check_token;
        }

        if ($this->request->getMethod() == 'post') {
            $newData = [
                'cus_id' => $this->user['cus_id'],
                'cus_name' => $this->request->getPost('name'),
                'cus_email' => $this->request->getPost('email'),
                'cus_address' => $this->request->getPost('address'),
                'cus_city' => $this->request->getPost('city'),
                'cus_state' => $this->request->getPost('state'),
                'cus_zip' => $this->request->getPost('zip'),
                'cus_dob' => $this->request->getPost('dob'),
                'cus_phone' => $this->request->getPost('phone')
            ];
            $this->customer->save($newData);
            return $this->respondCreated($this->customer->find($this->user['cus_id']));
        }
        return $this->failNotFound();
    }

    public function check_promo()
    {
        $check_token = $this->check_token();
        if ($check_token) {
            return $check_token;
        }

        if ($this->request->getMethod() == 'post') {
            $promotion = $this->promotion->where('promo_code', $this->request->getPost('code'))->first();

            if ($promotion) {
                return $this->respond($promotion);
            }
        }
        return $this->failNotFound();
    }

    public function order_history()
    {
        $check_token = $this->check_token();
        if ($check_token) {
            return $check_token;
        }

        if ($this->request->getMethod() == 'get') {

            $order = $this->db->table('orders');
            $orders = $order->select('*')->where(['is_complete' => 0, 'cus_id' => $this->user['cus_id']])
                ->join('restaurants', 'restaurants.rest_id = orders.rest_id')->get();
            $orders = $orders->getResult('array');

            $active_order = [];
            foreach ($orders as $ao) {
                $order_item = $this->db->table('order_items');
                $item = $order_item->select('order_item_id,items.item_id,order_item_quantity,item_name,item_price')
                    ->where('order_id', $ao['order_id'])
                    ->join('items', 'items.item_id = order_items.item_id')->get();
                $ao['items'] = $item->getResult('array');
                array_push($active_order, $ao);
            }

            $orders = $order->select('*')->where(['is_complete' => 1, 'cus_id' => $this->request->getGet('cus_id')])
                ->join('restaurants', 'restaurants.rest_id = orders.rest_id')->get();
            $orders = $orders->getResult('array');

            $past_order = [];
            foreach ($orders as $po) {
                $order_item = $this->db->table('order_items');
                $item = $order_item->select('order_item_id,items.item_id,order_item_quantity,item_name,item_price')
                    ->where('order_id', $ao['order_id'])
                    ->join('items', 'items.item_id = order_items.item_id')->get();
                $po['items'] = $item->getResult('array');
                array_push($past_order, $po);
            }

            $active['title'] = "Active Orders";
            $active['orders'] = $active_order;
            $past['title'] = "Past Orders";
            $past['orders'] = $past_order;

            $history = [];
            array_push($history, $active);
            array_push($history, $past);

            return $this->respond($history);
        }
        return $this->failNotFound();
    }

    // NOT DONE
    public function place_order()
    {
        $check_token = $this->check_token();
        if ($check_token) {
            return $check_token;
        }
    }
}
