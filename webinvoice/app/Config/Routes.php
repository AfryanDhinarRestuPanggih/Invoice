<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Enable HTTP method spoofing
$routes->setAutoRoute(false);
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Auth');
$routes->setDefaultMethod('login');

// Auth routes (public)
$routes->get('/', 'Auth::login');
$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::attemptLogin');
$routes->get('/register', 'Auth::register');
$routes->post('/register', 'Auth::attemptRegister');
$routes->get('/logout', 'Auth::logout');
$routes->get('/reset-password', 'Auth::resetPassword');
$routes->post('/reset-password', 'Auth::resetPassword');
$routes->get('/debug-user', 'Auth::debugUser');

// Protected routes
$routes->group('', ['filter' => 'auth'], function($routes) {
    // Dashboard
    $routes->get('dashboard', 'DashboardController::index');
    
    // Products
    $routes->get('products', 'ProductController::index');
    $routes->get('products/new', 'ProductController::new');
    $routes->post('products/create', 'ProductController::create');
    $routes->get('products/(:num)/edit', 'ProductController::edit/$1');
    $routes->post('products/update/(:num)', 'ProductController::update/$1');
    $routes->post('products/(:num)/delete', 'ProductController::delete/$1');
    $routes->post('products/(:num)/toggle', 'ProductController::toggle/$1');
    $routes->get('products/search', 'ProductController::search');

    // Customer Routes
    $routes->get('customers', 'CustomerController::index');
    $routes->get('customers/new', 'CustomerController::new');
    $routes->post('customers', 'CustomerController::create');
    $routes->get('customers/(:num)/edit', 'CustomerController::edit/$1');
    $routes->put('customers/(:num)', 'CustomerController::update/$1');
    $routes->delete('customers/(:num)', 'CustomerController::delete/$1');
    $routes->get('customers/(:num)', 'CustomerController::show/$1');
    $routes->get('api/customers/search', 'CustomerController::search');

    // Settings
    $routes->get('settings', 'SettingController::index');
    $routes->post('settings', 'SettingController::update');
    $routes->get('settings/logo/(:any)', 'SettingController::getLogo/$1');

    // Notifications
    $routes->get('notifications', 'NotificationController::index');
    $routes->post('notifications/mark-read', 'NotificationController::markAsRead');
    $routes->get('notifications/count', 'NotificationController::getUnreadCount');

    // Reports
    $routes->get('reports', 'ReportController::index');
    $routes->get('reports/export', 'ReportController::export');
    
    // Invoice routes
    $routes->group('invoices', function($routes) {
        $routes->get('/', 'InvoiceController::index');
        $routes->get('create', 'InvoiceController::create');
        $routes->post('store', 'InvoiceController::store');
        $routes->get('(:num)/edit', 'InvoiceController::edit/$1');
        $routes->post('update/(:num)', 'InvoiceController::update/$1');
        $routes->get('(:num)', 'InvoiceController::show/$1');
        $routes->get('(:num)/print', 'InvoiceController::print/$1');
        $routes->post('(:num)/send', 'InvoiceController::send/$1');
        $routes->post('(:num)/pay', 'InvoiceController::pay/$1');
        $routes->post('(:num)/cancel', 'InvoiceController::cancel/$1');
    });

    // Admin Routes
    $routes->group('admin', ['filter' => 'auth:admin'], function($routes) {
        $routes->get('users', 'Admin\Users::index');
        $routes->get('users/create', 'Admin\Users::create');
        $routes->post('users/store', 'Admin\Users::store');
        $routes->get('users/edit/(:num)', 'Admin\Users::edit/$1');
        $routes->post('users/update/(:num)', 'Admin\Users::update/$1');
        $routes->get('users/delete/(:num)', 'Admin\Users::delete/$1');

        // Backup routes
        $routes->group('backup', function($routes) {
            $routes->get('/', 'Backup::index');
            $routes->get('create', 'Backup::create');
            $routes->get('download/(:segment)', 'Backup::download/$1');
            $routes->post('restore', 'Backup::restore');
            $routes->get('delete/(:segment)', 'Backup::delete/$1');
        });
    });
});

// User Routes
$routes->get('users', 'UserController::index');
$routes->get('users/new', 'UserController::new');
$routes->post('users', 'UserController::create');
$routes->get('users/(:num)', 'UserController::show/$1');
$routes->get('users/(:num)/edit', 'UserController::edit/$1');
$routes->put('users/(:num)', 'UserController::update/$1');
$routes->delete('users/(:num)', 'UserController::delete/$1');
