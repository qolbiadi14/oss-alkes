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
$routes->get('/admin/categories', 'Admin\Categories::index');
$routes->get('/admin/categories/add', 'Admin\Categories::add');
$routes->post('/admin/categories/store', 'Admin\Categories::store');
$routes->get('/admin/categories/edit/(:num)', 'Admin\Categories::edit/$1');
$routes->post('/admin/categories/update/(:num)', 'Admin\Categories::update/$1');
$routes->post('/admin/categories/delete/(:num)', 'Admin\Categories::delete/$1');
$routes->get('/admin/users', 'Admin\Users::index');
$routes->post('/admin/users/delete/(:num)', 'Admin\Users::delete/$1');
$routes->post('/admin/users/activate/(:num)', 'Admin\Users::activate/$1');
$routes->get('/admin/accstores', 'Admin\AccStores::index');
$routes->post('/admin/accstores/approve/(:num)', 'Admin\AccStores::approve/$1');
$routes->post('/admin/accstores/reject/(:num)', 'Admin\AccStores::reject/$1');
$routes->post('/admin/accstores/suspend/(:num)', 'Admin\AccStores::suspend/$1');
$routes->post('/admin/accstores/unsuspend/(:num)', 'Admin\AccStores::unsuspend/$1');
$routes->get('/vendor/products', 'Vendor\Products::index');
$routes->get('/vendor/products/add', 'Vendor\Products::add');
$routes->post('/vendor/products/store', 'Vendor\Products::store');
$routes->get('/vendor/products/edit/(:num)', 'Vendor\Products::edit/$1');
$routes->post('/vendor/products/update/(:num)', 'Vendor\Products::update/$1');
$routes->post('/vendor/products/delete/(:num)', 'Vendor\Products::delete/$1');


$routes->group('admin', ['filter' => 'rolefilter'], function ($routes) {
    $routes->get('dashboard', 'Admin\Dashboard::index');
});

$routes->group('vendor', ['filter' => 'rolefilter'], function ($routes) {
    $routes->get('dashboard', 'Vendor\Dashboard::index');
    $routes->get('storeidentity', 'Vendor\StoreIdentity::index');
    $routes->post('storeidentity/save', 'Vendor\StoreIdentity::save');
});

$routes->group('customer', ['filter' => 'rolefilter'], function ($routes) {
    $routes->get('dashboard', 'Customer\Dashboard::index');
    $routes->get('products/detail/(:num)', 'Customer\Products::detail/$1');
    $routes->get('cart', 'Customer\Cart::index');
    $routes->get('cart/add/(:num)', 'Customer\Cart::add/$1');
    $routes->post('cart/updateQuantity/(:num)', 'Customer\Cart::updateQuantity/$1');
    $routes->get('cart/remove/(:num)', 'Customer\Cart::remove/$1');
});
