<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use App\Models\OrderModel;
use App\Models\RestaurantModel;
use CodeIgniter\Controller;
use DateTime;

class User extends Controller
{
    var $db, $session, $order, $customer = null;

    public function __construct()
    {
        $this->db = db_connect();
        $this->session = session();
        $this->order = new OrderModel();
        $this->customer = new CustomerModel();
        $this->restaurant = new RestaurantModel();

    }

    public function register()
    {
        $data['cus_id'] = $this->session->has('cus_id') ? $this->session->cus_id : NULL;
        $data['title'] = strtolower('register');
        $data['header'] = "header-layout2";
        $data['restaurant'] = $this->restaurant->find(getEnv('REST_ID'));


        if ($this->request->getPost()) {
            $err = ['msg' => 'Error: Email already exists, Try a different email!'];
            // Check if email already exists with us
            $customer = $this->customer->where('cus_email', $this->request->getPost('email'))->first();

            if (empty($customer)) {
                $cus_id = $this->customer->insert([
                    'cus_name' => $this->request->getPost('name'),
                    'cus_email' => $this->request->getPost('email'),
                    'cus_password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                    'has_register' => 1,
                    'business_id' => getEnv('BUSINESS_ID'),
                    'rest_id' => getEnv('REST_ID')
                ]);
                $customer = $this->customer->find($cus_id);
                unset($customer['cus_password']);
                $this->session->set($customer);

                return redirect()->to('/user/order-history');
            }

            if (!empty($customer['has_register'])) {
                echo view('templates/header', $data);
                echo view('user/register', $err);
                echo view('templates/footer', $data);
                return;
            }

            $this->customer->save([
                'cus_id' => $customer['cus_id'],
                'cus_password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'has_register' => 1
            ]);
            unset($customer['cus_password']);
            $this->session->set($customer);

            return redirect()->to('/user/order-history');
        }

        echo view('templates/header', $data);
        echo view('user/register', $data);
        echo view('templates/footer', $data);
    }

    public function login()
    {
        $data['cus_id'] = $this->session->has('cus_id') ? $this->session->cus_id : NULL;
        $data['title'] = strtolower('login');
        $data['header'] = "header-layout2";
        $data['restaurant'] = $this->restaurant->find(getEnv('REST_ID'));

        $err = ['msg' => 'Error: Invalid Email/Password. Try again!'];
        $err2 = ['msg' => 'Error: Account does not exisits. Register now!'];


        if ($this->request->getVar('email') && $this->request->getVar('password')) {
            $customer = $this->customer->where([
                'cus_email' => $this->request->getVar('email'),
            ])->first();

            if (empty($customer)) {
                echo view('templates/header', $data);
                echo view('user/login', $err2);
                echo view('templates/footer', $data);
                return;
            }

            if (!password_verify($this->request->getVar('password'), $customer['cus_password'])) {
                echo view('templates/header', $data);
                echo view('user/login', $err);
                echo view('templates/footer', $data);
                return;
            }

            $customer = $this->customer->where(['cus_email' => $this->request->getVar('email')])->first();
            unset($customer["cus_password"]);
            $this->session->set($customer);

            return redirect()->to('/user/order-history');
        }

        echo view('templates/header', $data);
        echo view('user/login', $data);
        echo view('templates/footer', $data);
    }

    public function account()
    {
        $data['title'] = ucfirst('account');
        $data['header'] = "header-layout2";
        $data['cus_id'] = $this->session->has('cus_id') ? $this->session->cus_id : NULL;
        $data['restaurant'] = $this->restaurant->find(getEnv('REST_ID'));


        if ($this->request->getPost()) {
            $newData = [
                'cus_id' => $this->session->cus_id,
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

            $data['msg'] = 'Account updated!';
        }

        $customer = $this->customer->find($this->session->cus_id);
        $data['customer'] = $customer;
        $data['content'] = view('user/account', $data);
        echo view('templates/header', $data);
        echo view('user/profile', $data);
        echo view('templates/footer', $data);
    }

    public function order_history()
    {
        $data['restaurant'] = $this->restaurant->find(getEnv('REST_ID'));

        $data['cus_id'] = $this->session->has('cus_id') ? $this->session->cus_id : NULL;
        $data['title'] = ucwords('order history');
        $data['header'] = "header-layout2";

        $data['orderOpen'] = $this->order->where(["cus_id" => $this->session->cus_id, "is_complete" => 0])->findAll();
        rsort($data['orderOpen']);
        $data['orderPast'] = $this->order->where(["cus_id" => $this->session->cus_id, "is_complete" => 1])->findAll();
        rsort($data['orderPast']);

        $data['content'] = view('user/order_history', $data);
        echo view('templates/header', $data);
        echo view('user/profile', $data);
        echo view('templates/footer', $data);
    }

    public function change_password()
    {
        $data['restaurant'] = $this->restaurant->find(getEnv('REST_ID'));

        $data['cus_id'] = $this->session->has('cus_id') ? $this->session->cus_id : NULL;
        $data['title'] = ucwords('change password');
        $data['header'] = "header-layout2";

        if ($this->request->getPost()) {
            $oldUser = $this->customer->find($this->session->cus_id);
            if (password_verify($this->request->getPost('oldpass'), $oldUser['cus_password'])) {
                $newData = [
                    'cus_id' => $this->session->cus_id,
                    'cus_password' => password_hash($this->request->getPost('newpass'), PASSWORD_DEFAULT),
                ];
                $this->customer->save($newData);
                $data['msg'] = 'Password updated!';
            } else {
                $data['err'] = 'Invalid old password!';
            }
        }

        $data['content'] = view('user/change_password', $data);
        echo view('templates/header', $data);
        echo view('user/profile', $data);
        echo view('templates/footer', $data);
    }

    public function promotions()
    {
        $data['title'] = ucwords('Promotions');
        $data['header'] = "header-layout2";
        $data['content'] = view('user/promotion');
        $data['restaurant'] = $this->restaurant->find(getEnv('REST_ID'));

        echo view('templates/header', $data);
        echo view('user/profile', $data);
        echo view('templates/footer', $data);
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/user/login');
    }

    public function get_order($order_num)
    {
        $order = $this->db->table('orders');
        $order->select('*');
        $order->where('order_num', $order_num);
        $order->join('restaurants', 'restaurants.rest_id = orders.rest_id');
        $order = $order->get();
        $order = $order->getResult('array')[0];

        $items = $this->db->table('order_items');
        $items->select('*');
        $items->where('order_id', $order['order_id']);
        $items->join('items', 'items.item_id = order_items.item_id');
        $items = $items->get();
        $items = $items->getResult('array');

        $data = [
            'order' => $order,
            'items' => $items,
        ];
        $data['content'] = view('user/order', $data);

        $data['cus_id'] = $this->session->has('cus_id') ? $this->session->cus_id : NULL;
        $data['title'] = ucwords('Order Details');
        $data['header'] = "header-layout2";
        $data['restaurant'] = $this->restaurant->find(getEnv('REST_ID'));

        echo view('templates/header', $data);
        echo view('user/profile', $data);
        echo view('templates/footer', $data);
    }
}
