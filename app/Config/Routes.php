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


$routes->group('admin', ['filter' => 'rolefilter'], function ($routes) {
    $routes->get('dashboard', 'Admin\Dashboard::index');
});

$routes->group('customer', ['filter' => 'rolefilter'], function ($routes) {
    $routes->get('dashboard', 'Customer\Dashboard::index');
});
