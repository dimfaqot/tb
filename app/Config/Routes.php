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
$routes->post('home/get_laporan', 'Home::get_laporan');


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

// barang
$routes->get('/barang', 'Barang::index');
$routes->post('/barang/add', 'Barang::add');
$routes->post('/barang/update', 'Barang::update');
$routes->post('/barang/update_td', 'Barang::update_td');

// penjualan
$routes->get('/penjualan', 'Penjualan::index');
$routes->post('/penjualan/no_nota', 'Penjualan::no_nota');
$routes->post('/penjualan/cari_barang', 'Penjualan::cari_barang');
$routes->post('/penjualan/transaksi', 'Penjualan::transaksi');

// pengeluaran
$routes->get('/pengeluaran', 'Pengeluaran::index');
$routes->get('/inventaris', 'Pengeluaran::inventaris');
$routes->post('/inventaris/add', 'Pengeluaran::add');
$routes->post('/inventaris/update', 'Pengeluaran::update');
$routes->post('/pengeluaran/cari_barang', 'Pengeluaran::cari_barang');
$routes->post('/pengeluaran/transaksi', 'Pengeluaran::transaksi');

// guest
$routes->get('guest/laporan/(:any)/(:num)', 'Guest::laporan/$1/$2');
$routes->get('/guest/cetak_nota/(:any)', 'Guest::cetak_nota/$1');
