<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use App\Models\OrderModel;
use App\Models\RestaurantModel;
use CodeIgniter\Controller;
use DateTime;

class User extends Controller
{
    var $db = null;

    public function __construct()
    {
        $this->db = db_connect();
    }

    public function register()
    {
        $session = session();
        $data['cus_id'] = $session->has('cus_id') ? $session->cus_id : NULL;

        $data['title'] = strtolower('register');
        $data['header'] = "header-layout2";

        $customerModel = new CustomerModel();
        $err = ['msg' => 'Error: Email already exists, Try a different email!'];

        if ($this->request->getVar('name') && $this->request->getVar('email') && $this->request->getVar('password')) {
            $user = $customerModel->where([
                'cus_email' => $this->request->getVar('email'),
            ])->first();

            if (is_array($user)) {
                echo view('templates/header', $data);
                echo view('user/register', $err);
                echo view('templates/footer', $data);
                return;
            }

            $customerModel->save([
                'cus_name' => $this->request->getVar('name'),
                'cus_email' => $this->request->getVar('email'),
                'cus_password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
            ]);

            $newUser = $customerModel->where(['cus_email' => $this->request->getVar('email')])->first();
            unset($newUser["cus_password"]);
            $session->set($newUser);

            return redirect()->to('/user/order-history');
        }


        echo view('templates/header', $data);
        echo view('user/register', $data);
        echo view('templates/footer', $data);
    }

    public function login()
    {
        $session = session();
        $data['cus_id'] = $session->has('cus_id') ? $session->cus_id : NULL;

        $data['title'] = strtolower('login');
        $data['header'] = "header-layout2";

        $customerModel = new CustomerModel();
        $err = ['msg' => 'Error: Invalid Email/Password. Try again!'];

        if ($this->request->getVar('email') && $this->request->getVar('password')) {
            $user = $customerModel->where([
                'cus_email' => $this->request->getVar('email'),
            ])->first();

            if (!password_verify($this->request->getVar('password'), $user['cus_password'])) {
                echo view('templates/header', $data);
                echo view('user/login', $err);
                echo view('templates/footer', $data);
                return;
            }

            $newUser = $customerModel->where(['cus_email' => $this->request->getVar('email')])->first();
            unset($newUser["cus_password"]);
            $session->set($newUser);

            return redirect()->to('/user/order-history');
        }

        echo view('templates/header', $data);
        echo view('user/login', $data);
        echo view('templates/footer', $data);
    }

    public function profile()
    {
        $data['title'] = ucfirst('profile');
        $data['header'] = "header-layout2";
        echo view('templates/header', $data);
        echo view('user/profile', $data);
        echo view('templates/footer', $data);
    }

    public function account()
    {
        $session = session();
        $data['title'] = ucfirst('account');
        $data['header'] = "header-layout2";
        $data['cus_id'] = $session->has('cus_id') ? $session->cus_id : NULL;
        $customerModel = new CustomerModel();

        if ($this->request->getPost()) {

            $date = new DateTime();
            $dob = $this->request->getPost('dob');
            $date->setDate($dob[2], $dob[1], $dob[0]);
            $dob = $date->format('Y-m-d');

            $newData = [
                'cus_name' => $this->request->getPost('name'),
                'cus_email' => $this->request->getPost('email'),
                'cus_address' => $this->request->getPost('address'),
                'cus_city' => $this->request->getPost('city'),
                'cus_state' => $this->request->getPost('state'),
                'cus_zip' => $this->request->getPost('zip'),
                'cus_dob' => $dob,
                'cus_phone' => $this->request->getPost('phone')
            ];
            $customerModel->update($session->cus_id, $newData);

            $data['msg'] = 'Account updated!';
        }

        $customer = $customerModel->find($session->cus_id);
        $data['customer'] = $customer;
        $data['content'] = view('user/account', $data);
        echo view('templates/header', $data);
        echo view('user/profile', $data);
        echo view('templates/footer', $data);
    }

    public function orderHistory()
    {
        $session = session();
        $data['cus_id'] = $session->has('cus_id') ? $session->cus_id : NULL;

        $orderModel = new OrderModel();
        $data['title'] = ucwords('order history');
        $data['header'] = "header-layout2";
        $data['orderOpen'] = $orderModel->where(["cus_id" => $session->cus_id, "order_complete" => 0])->findAll();
        $data['orderPast'] = $orderModel->where(["cus_id" => $session->cus_id, "order_complete" => 1])->findAll();

        $data['content'] = view('user/order_history', $data);
        echo view('templates/header', $data);
        echo view('user/profile', $data);
        echo view('templates/footer', $data);
    }

    public function changePassword()
    {
        $session = session();
        $data['cus_id'] = $session->has('cus_id') ? $session->cus_id : NULL;

        $data['title'] = ucwords('change password');
        $data['header'] = "header-layout2";

        $customerModel = new CustomerModel();
        if ($this->request->getPost()) {
            $oldUser = $customerModel->find($session->cus_id);
            if (password_verify($this->request->getPost('oldpass'), $oldUser['cus_password'])) {
                $newData = [
                    'cus_password' => password_hash($this->request->getPost('newpass'), PASSWORD_DEFAULT),
                ];
                $customerModel->update($session->cus_id, $newData);
                $data['msg'] = 'Password updated!';
            } else {
                echo "not verify";
                $data['err'] = 'Invalid password!';
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

        echo view('templates/header', $data);
        echo view('user/profile', $data);
        echo view('templates/footer', $data);
    }

    public function logout()
    {
        $session = session();
        $session->destroy();

        return redirect()->to('/user/login');
    }

    public function order($val)
    {
        $info = $this->db->table('orders');
        $info->select('*');
        $info->where('order_num', $val);
        $info->join('restaurants', 'restaurants.rest_id = orders.rest_id');
        $queryInfo = $info->get();

        $orderItems = $this->db->table('order_items');
        $orderItems->select('*');
        $orderItems->where('order_num', $val);
        $orderItems->join('items', 'items.item_id = order_items.item_id');
        $queryOrderItems = $orderItems->get();

        $data = [
            'info' => (array) $queryInfo->getResult()[0],
            'items' => (array) $queryOrderItems->getResult()
        ];

        $data['title'] = ucwords('Order Details');
        $data['header'] = "header-layout2";
        $data['content'] = view('user/order', $data);

        $session = session();
        $data['cus_id'] = $session->has('cus_id') ? $session->cus_id : NULL;
        echo view('templates/header', $data);
        echo view('user/profile', $data);
        echo view('templates/footer', $data);
    }
}
