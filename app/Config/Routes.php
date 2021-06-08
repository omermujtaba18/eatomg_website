<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Order::index');
$routes->match(['get', 'post'], '/register', 'User::register');
$routes->match(['get', 'post'], '/login', 'User::login');
$routes->match(['get', 'post'], '/user/change-password', 'User::change_password');
$routes->match(['get', 'post'], '/user/account', 'User::account');
$routes->get('/user/order-history', 'User::order_history');
$routes->get('/user/(:alpha)', 'User::$1');
$routes->get('/user/order/(:num)', 'User::get_order/$1');

//Order Routes
// $routes->get('/select-restauarant', 'Order::select_restauarant');
$routes->match(['get', 'post'], '/order-now/(:any)/(:num)', 'Order::item_by_id/$1/$2');
$routes->get('/order-now', 'Order::index');
$routes->get('/order-now/(:any)', 'Order::index/$1');
$routes->match(['get', 'post'], '/cart', 'Order::cart');
$routes->match(['get', 'post'], '/cart/remove/(:num)', 'Order::remove_from_cart/$1');
$routes->match(['get', 'post'], '/checkout', 'Order::checkout');
$routes->match(['get', 'post'], '/checkout/pay-paypal', 'Order::payByPaypal');
$routes->match(['get', 'post'], '/checkout/return', 'Order::return_paypal');
$routes->match(['get', 'post'], '/checkout/confirmation', 'Order::confirmation');
$routes->get('/empty_cart', 'Order::empty_cart');

/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need to it be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
