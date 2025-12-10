<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// 1. Rute Halaman Utama
$routes->get('/', 'Home::index');

// Halaman Statis & Listing
$routes->get('/tentang', 'PageController::tentang');
$routes->get('/concerts', 'PageController::concerts');
$routes->get('/festivals', 'PageController::festivals');
$routes->get('/events', 'PageController::events'); // Untuk kategori "Event Lainnya"

// 2. Rute Event (Publik) - Menggunakan SLUG
$routes->get('/event/(:segment)', 'EventController::detail/$1');
$routes->get('/event/(:segment)/select', 'EventController::select/$1');

// 3. Rute Proses Checkout
$routes->post('/checkout/start', 'CheckoutController::start');

// Grup Checkout (Protected)
$routes->group('checkout', static function ($routes) {
    $routes->get('personal_info', 'CheckoutController::personalInfo');
    $routes->post('process_personal_info', 'CheckoutController::processPersonalInfo');

    $routes->get('payment_method', 'CheckoutController::paymentMethod');
    $routes->post('process_payment', 'CheckoutController::processPayment');

    $routes->get('review_order', 'CheckoutController::reviewOrder');
    $routes->post('create_order', 'CheckoutController::createOrder');
});

// 4. Rute Status Pesanan
$routes->get('/checkout/cancel', 'CheckoutController::cancel');
$routes->get('/checkout/pay/(:num)', 'CheckoutController::pay/$1');
$routes->post('/checkout/confirm/(:num)', 'CheckoutController::confirmPayment/$1');

// 5. Rute Admin
$routes->group('admin', ['filter' => 'group:admin'], static function ($routes) {
    $routes->get('/', 'Admin\DashboardController::index');

    // Manajemen Event (Manual Route)
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

    // --- MANAJEMEN ORDER ---
    $routes->get('orders', 'Admin\OrderController::index');
    $routes->get('orders/detail/(:num)', 'Admin\OrderController::detail/$1');
    $routes->post('orders/update-status', 'Admin\OrderController::updateStatus');
    $routes->get('orders/pdf/(:num)', 'Admin\OrderController::downloadPdf/$1');
});

// 6. Auth Routes
service('auth')->routes($routes);

// 7. Rute Profil Pengguna
$routes->group('profile', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'ProfileController::index');
    $routes->get('edit', 'ProfileController::edit');
    $routes->post('update', 'ProfileController::update');
});
