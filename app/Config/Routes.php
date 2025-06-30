<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::doLogin');
$routes->get('/logout', 'Auth::logout');
$routes->get('/register', 'Auth::register');
$routes->post('/register', 'Auth::doRegister');


$routes->group('admin', ['filter' => 'rolefilter'], function ($routes) {
    $routes->get('dashboard', 'Admin\Dashboard::index');
    $routes->get('categories', 'Admin\Categories::index');
    $routes->get('categories/add', 'Admin\Categories::add');
    $routes->post('categories/store', 'Admin\Categories::store');
    $routes->get('categories/edit/(:num)', 'Admin\Categories::edit/$1');
    $routes->post('categories/update/(:num)', 'Admin\Categories::update/$1');
    $routes->post('categories/delete/(:num)', 'Admin\Categories::delete/$1');
    $routes->get('users', 'Admin\Users::index');
    $routes->post('users/delete/(:num)', 'Admin\Users::delete/$1');
    $routes->post('users/activate/(:num)', 'Admin\Users::activate/$1');
    $routes->get('accstores', 'Admin\AccStores::index');
    $routes->post('accstores/approve/(:num)', 'Admin\AccStores::approve/$1');
    $routes->post('accstores/reject/(:num)', 'Admin\AccStores::reject/$1');
    $routes->post('accstores/suspend/(:num)', 'Admin\AccStores::suspend/$1');
    $routes->post('accstores/unsuspend/(:num)', 'Admin\AccStores::unsuspend/$1');
    $routes->get('reports', 'Reports::index');
    $routes->get('send', 'Admin\Send::index');
    $routes->post('send/updateStatus/(:num)', 'Admin\Send::updateStatus/$1');
});

$routes->group('vendor', ['filter' => 'rolefilter'], function ($routes) {
    $routes->get('dashboard', 'Vendor\Dashboard::index');
    $routes->get('storeidentity', 'Vendor\StoreIdentity::index');
    $routes->post('storeidentity/save', 'Vendor\StoreIdentity::save');
    $routes->get('products', 'Vendor\Products::index');
    $routes->get('products/add', 'Vendor\Products::add');
    $routes->post('products/store', 'Vendor\Products::store');
    $routes->get('products/edit/(:num)', 'Vendor\Products::edit/$1');
    $routes->post('products/update/(:num)', 'Vendor\Products::update/$1');
    $routes->post('products/delete/(:num)', 'Vendor\Products::delete/$1');
    $routes->get('receiveorders', 'Vendor\ReceiveOrders::index');
    $routes->post('receiveorders/accept/(:num)', 'Vendor\ReceiveOrders::accept/$1');
    $routes->get('reports', 'Reports::index');
});

$routes->group('customer', ['filter' => 'rolefilter'], function ($routes) {
    $routes->get('dashboard', 'Customer\Dashboard::index');
    $routes->get('products/detail/(:num)', 'Customer\Products::detail/$1');
    $routes->get('cart', 'Customer\Cart::index');
    $routes->get('cart/add/(:num)', 'Customer\Cart::add/$1');
    $routes->post('cart/updateQuantity/(:num)', 'Customer\Cart::updateQuantity/$1');
    $routes->get('cart/remove/(:num)', 'Customer\Cart::remove/$1');
    $routes->post('payment/process', 'Customer\Payment::process');
    $routes->get('payment/(:num)', 'Customer\Payment::index/$1');
    $routes->get('payment', 'Customer\Payment::index');
    $routes->post('payment/snapToken/(:num)', 'Customer\Payment::snapToken/$1');
    $routes->get('payment/payPrepaid/(:num)', 'Customer\Payment::payPrepaid/$1');
    $routes->get('payment/payPostpaid/(:num)', 'Customer\Payment::payPostpaid/$1');
    $routes->get('reports', 'Reports::index');
    $routes->get('payment/failed/(:num)', 'Customer\Payment::handlePaymentFailed/$1');
    $routes->post('payment/success/(:num)', 'Customer\Payment::handlePaymentSuccess/$1');
    $routes->get('payment/success/(:num)', 'Customer\Payment::handlePaymentSuccess/$1');
    $routes->get('payment/finish', 'Customer\Payment::finish');
    $routes->get('payment/error', 'Customer\Payment::error');
    $routes->post('reports/cancelOrder/(:num)', 'Reports::cancelOrder/$1');
    $routes->post('reports/finishOrder/(:num)', 'Reports::finishOrder/$1');
    $routes->get('feedback/create/(:num)', 'Feedback::create/$1');
    $routes->post('feedback/store', 'Feedback::store');
});

$routes->get('reports', 'Reports::index');
$routes->post('reports/cancelOrder/(:num)', 'Reports::cancelOrder/$1');
$routes->post('reports/finishOrder/(:num)', 'Reports::finishOrder/$1');
$routes->get('reports/printInvoice/(:num)', 'Reports::printInvoice/$1');
$routes->post('midtrans/notification', 'Midtrans::notification');
