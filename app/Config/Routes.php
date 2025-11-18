<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// 1. Rute Halaman Utama
$routes->get('/', 'Home::index');

// 2. Rute Event (Publik)
$routes->get('/event/(:num)', 'EventController::detail/$1');
$routes->get('/event/(:num)/select', 'EventController::select/$1');

// 3. Rute Proses Checkout
// Rute ini memulai proses checkout dan TIDAK perlu filter timer
$routes->post('/checkout/start', 'CheckoutController::start');

// Grup untuk semua halaman checkout yang DILINDUNGI timer DAN LOGIN
$routes->group('checkout', ['filter' => 'checkout_timer'], static function ($routes) {
    // Step 1: Data Diri
    $routes->get('personal_info', 'CheckoutController::personalInfo');
    $routes->post('process_personal_info', 'CheckoutController::processPersonalInfo');
    
    // Step 2: Metode Pembayaran
    $routes->get('payment_method', 'CheckoutController::paymentMethod');
    $routes->post('process_payment', 'CheckoutController::processPayment');
    
    // Step 3: Review & Submit
    $routes->get('review_order', 'CheckoutController::reviewOrder');
    $routes->post('submit_order', 'CheckoutController::submitOrder');
});

// 4. Rute Status Pesanan (Setelah Checkout Selesai)
$routes->get('/checkout/timeout', 'CheckoutController::timeout');
$routes->get('/order/success', 'CheckoutController::orderSuccess');
$routes->get('/checkout/cancel', 'CheckoutController::cancel');

$routes->group('admin', ['filter' => 'group:admin'], static function ($routes) {
    
    // Rute default untuk /admin
    $routes->get('/', 'Admin\DashboardController::index');

    // ======================================================
    // MANAJEMEN EVENT (Secara Manual, BUKAN resource)
    // ======================================================
    
    // R (Read): Menampilkan daftar event
    $routes->get('events', 'Admin\EventController::index');
    
    // C (Create): Menampilkan form 'new'
    $routes->get('events/new', 'Admin\EventController::new');
    // C (Create): Memproses form 'new'
    $routes->post('events', 'Admin\EventController::create');

    // R (Read): Menampilkan satu event (kita redirect ke edit)
    $routes->get('events/([0-9]+)', 'Admin\EventController::show/$1');

    // U (Update): Menampilkan form 'edit' (INI YANG MEMPERBAIKI ERROR-MU)
    $routes->get('events/edit/([0-9]+)', 'Admin\EventController::edit/$1');
    // U (Update): Memproses form 'edit'
    $routes->put('events/([0-9]+)', 'Admin\EventController::update/$1');
    $routes->post('events/update/([0-9]+)', 'Admin\EventController::update/$1'); // Fallback jika PUT gagal

    // D (Delete): Menghapus event
    $routes->delete('events/([0-9]+)', 'Admin\EventController::delete/$1');
});

// 5. Rute Autentikasi (Login, Register, dll.)
service('auth')->routes($routes);