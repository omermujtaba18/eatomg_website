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
        $API = $this->request->getGet('token');

        if (!empty($API)) {
            $customer = $this->customer->where('token', $API)->first();
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


    public function get_menu()
    {
        // return $this->check_token();

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
        // return $this->check_token();
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

    public function check_promo()
    {
        // return $this->check_token();

        if ($this->request->getMethod() == 'post') {
            $promotion = $this->promotion->where('promo_code', $this->request->getPost('code'))->first();

            if ($promotion) {
                return $this->respond($promotion);
            }
        }
        return $this->failNotFound();
    }
}
