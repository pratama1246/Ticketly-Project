<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


// ==============================================================================
// 1. RUTE PUBLIK (Namespace App\Controllers\Public)
// ==============================================================================
$routes->group('', ['namespace' => 'App\Controllers\Public'], static function ($routes) {
    $routes->get('/', 'Home::index');
    $routes->get('/tentang', 'PageController::tentang');
    $routes->get('/concerts', 'PageController::concerts');
    $routes->get('/festivals', 'PageController::festivals');
    $routes->get('/events', 'PageController::events');

    $routes->get('/event/(:segment)', 'EventController::detail/$1');
    $routes->get('/event/(:segment)/select', 'EventController::select/$1');
});

// ==============================================================================
// 2. RUTE USER & CHECKOUT (Namespace App\Controllers\User)
// ==============================================================================
$routes->group('', ['namespace' => 'App\Controllers\User'], static function ($routes) {
    $routes->post('/checkout/start', 'CheckoutController::start');

    $routes->group('checkout', static function ($routes) {
        $routes->get('personal_info', 'CheckoutController::personalInfo');
        $routes->post('process_personal_info', 'CheckoutController::processPersonalInfo');
        $routes->get('payment_method', 'CheckoutController::paymentMethod');
        $routes->post('process_payment', 'CheckoutController::processPayment');
        $routes->get('review_order', 'CheckoutController::reviewOrder');
        $routes->post('create_order', 'CheckoutController::createOrder');
        $routes->get('cancel', 'CheckoutController::cancel');
        $routes->get('pay/(:num)', 'CheckoutController::pay/$1');
        $routes->post('confirm/(:num)', 'CheckoutController::confirmPayment/$1');
    });
});

// 5. Rute Admin
$routes->group('admin', ['filter' => 'group:admin'], static function ($routes) {
    $routes->get('/', 'Admin\DashboardController::index');
    $routes->get('dashboard', 'Admin\DashboardController::index');

    // Manajemen Event
    $routes->get('events', 'Admin\EventController::index');
    $routes->get('events/new', 'Admin\EventController::new');
    $routes->post('events', 'Admin\EventController::create');
    $routes->get('events/([0-9]+)', 'Admin\EventController::show/$1');
    $routes->get('events/edit/([0-9]+)', 'Admin\EventController::edit/$1');
    $routes->put('events/([0-9]+)', 'Admin\EventController::update/$1');
    $routes->post('events/update/([0-9]+)', 'Admin\EventController::update/$1');
    $routes->delete('events/([0-9]+)', 'Admin\EventController::delete/$1');
    
    //Manajemen Tiket
    $routes->get('events/(:num)/tickets', 'Admin\TicketController::index/$1');
    $routes->get('events/(:num)/tickets/new', 'Admin\TicketController::new/$1');
    $routes->get('events/(:num)/tickets/(:num)/edit', 'Admin\TicketController::edit/$1/$2');
    $routes->post('events/(:num)/tickets/(:num)/update', 'Admin\TicketController::update/$1/$2');
    $routes->post('events/(:num)/tickets', 'Admin\TicketController::create/$1');
    $routes->delete('events/(:num)/tickets/(:num)', 'Admin\TicketController::delete/$1/$2');
    $routes->get('events/(:num)/tickets/(:num)/duplicate', 'Admin\TicketController::duplicate/$1/$2');

    // Manajemen Order
    $routes->get('orders', 'Admin\OrderController::index');
    $routes->get('orders/detail/(:num)', 'Admin\OrderController::detail/$1');
    $routes->post('orders/update-status', 'Admin\OrderController::updateStatus');
    $routes->get('orders/pdf/(:num)', 'Admin\OrderController::downloadPdf/$1');
});

// 6. Auth Routes
service('auth')->routes($routes);

// 7. Rute Profil Pengguna (Namespace App\Controllers\User)
$routes->group('profile', ['namespace' => 'App\Controllers\User', 'filter' => 'session'], static function ($routes) {
    $routes->get('/', 'ProfileController::index');
    $routes->get('edit', 'ProfileController::edit');
    $routes->post('update', 'ProfileController::update');
    $routes->get('transactions/(:num)', 'ProfileController::detail/$1');
    $routes->get('history', 'ProfileController::transactions');
});

// API Routes
$routes->group('api', ['namespace' => 'App\Controllers\Api'], function ($routes) {

    // Auth
    $routes->post('auth/login', 'AuthController::login');
    $routes->post('auth/register', 'AuthController::register');

    // Events — featured HARUS di atas (:segment)
    $routes->get('events/featured', 'EventController::featured');        // paling atas
    $routes->get('events/(:num)/tickets', 'TicketController::index/$1'); // ← naik ke sini
    $routes->get('events', 'EventController::index');
    $routes->get('events/(:segment)', 'EventController::show/$1');

    // Checkout public
    $routes->get('checkout/payment-methods', 'CheckoutController::paymentMethods');
    $routes->post('checkout/calculate', 'CheckoutController::calculate');

    // Protected
    $routes->group('', ['filter' => 'api_jwt'], function ($routes) {
        $routes->post('auth/logout', 'AuthController::logout');

        $routes->get('profile', 'ProfileController::index');
        $routes->post('profile/update', 'ProfileController::update');

        $routes->get('orders', 'OrderController::index');
        $routes->get('orders/(:num)', 'OrderController::detail/$1');

        $routes->post('checkout/start', 'CheckoutController::start');
        $routes->post('checkout/confirm', 'CheckoutController::confirm');
        $routes->post('checkout/cancel', 'CheckoutController::cancel');
    });
});