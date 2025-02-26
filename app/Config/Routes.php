<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// landing
$routes->get('/auth/(:any)', 'Landing::auth/$1');
$routes->get('/', 'Landing::index');

// home
$routes->get('/home', 'Home::index');
$routes->post('/home/delete', 'Home::delete');
$routes->get('logout', 'Home::logout');

// menu
$routes->get('/menu', 'Menu::index');
$routes->post('/menu/add', 'Menu::add');
$routes->post('/menu/update', 'Menu::update');

// options
$routes->get('/options', 'Options::index');
$routes->post('/options/add', 'Options::add');
$routes->post('/options/update', 'Options::update');

// options
$routes->get('/user', 'User::index');
$routes->post('/user/add', 'User::add');
$routes->post('/user/update', 'User::update');
