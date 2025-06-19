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
});

$routes->group('customer', ['filter' => 'rolefilter'], function ($routes) {
    $routes->get('dashboard', 'Customer\Dashboard::index');
});
