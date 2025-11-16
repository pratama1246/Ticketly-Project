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

// Grup untuk semua halaman checkout yang DILINDUNGI timer
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
// Halaman ini tidak perlu filter timer
$routes->get('/checkout/timeout', 'CheckoutController::timeout');
$routes->get('/order/success', 'CheckoutController::orderSuccess');
$routes->get('/checkout/cancel', 'CheckoutController::cancel');

// 5. Rute Autentikasi (Login, Register, dll.)
service('auth')->routes($routes);