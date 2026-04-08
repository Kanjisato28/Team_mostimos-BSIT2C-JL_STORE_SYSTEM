<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Auth::index');
$routes->get('/login', 'Auth::index');
$routes->post('/auth', 'Auth::auth');
$routes->get('/dashboard', 'Dashboard::index');
$routes->get('/logout', 'Auth::logout');

// Logs routes for admin
$routes->get('/log', 'Logs::log');

// Categories routes
$routes->get('/categories', 'Categories::index');
$routes->post('categories/save', 'Categories::save');
$routes->get('categories/edit/(:num)', 'Categories::edit/$1');
$routes->post('categories/update', 'Categories::update');
$routes->delete('categories/delete/(:num)', 'Categories::delete/$1');
$routes->post('categories/fetchRecords', 'Categories::fetchRecords');

// Suppliers routes
$routes->get('/suppliers', 'Suppliers::index');
$routes->post('suppliers/save', 'Suppliers::save');
$routes->get('suppliers/edit/(:num)', 'Suppliers::edit/$1');
$routes->post('suppliers/update', 'Suppliers::update');
$routes->delete('suppliers/delete/(:num)', 'Suppliers::delete/$1');
$routes->post('suppliers/fetchRecords', 'Suppliers::fetchRecords');

// Customers routes
$routes->get('/customers', 'Customers::index');
$routes->post('customers/save', 'Customers::save');
$routes->get('customers/edit/(:num)', 'Customers::edit/$1');
$routes->post('customers/update', 'Customers::update');
$routes->delete('customers/delete/(:num)', 'Customers::delete/$1');
$routes->post('customers/fetchRecords', 'Customers::fetchRecords');

// Products routes
$routes->get('/products', 'Products::index');
$routes->post('products/save', 'Products::save');
$routes->get('products/edit/(:num)', 'Products::edit/$1');
$routes->post('products/update', 'Products::update');
$routes->delete('products/delete/(:num)', 'Products::delete/$1');
$routes->post('products/fetchRecords', 'Products::fetchRecords');
$routes->get('products/search', 'Products::search');

// Sales routes
$routes->get('/sales', 'Sales::index');
$routes->get('sales/create', 'Sales::create');
$routes->post('sales/store', 'Sales::store');
$routes->get('sales/view/(:num)', 'Sales::view/$1');
$routes->post('sales/void/(:num)', 'Sales::void/$1');
$routes->post('sales/fetchRecords', 'Sales::fetchRecords');

// Inventory routes
$routes->get('/inventory', 'Inventory::index');
$routes->post('inventory/adjust', 'Inventory::adjust');
$routes->post('inventory/fetchRecords', 'Inventory::fetchRecords');
