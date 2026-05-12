<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="<?= base_url('assets/favicon.png') ?>" type="image/png">
        <link href="<?= base_url('output.css') ?>" rel="stylesheet">
        <script src="<?= base_url('flowbite.min.js') ?>"></script>
        <script src="<?= base_url('js/app.js') ?>"></script>
    <title><?= esc($title ?? 'Ticketly') ?> - Ticketly</title>
    <?= csrf_meta() ?>
</head>

<body class="font-default bg-yellow-bright-light text-heading flex flex-col min-h-screen">

<!-- NAVBAR -->
<nav class="bg-yellow-bright-light fixed w-full z-50 top-0 start-0 border-b border-default">
  <div class="flex flex-wrap items-center justify-between mx-auto p-6">
    <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
         <img src="<?= base_url('assets/ticketly-logo.png') ?>" class="h-14" alt="ticketly Logo">
    </a>
    <div class="flex md:order-2 space-x-3 md:space-x-3 rtl:space-x-reverse">

        <?php if (auth()->loggedIn()): ?>
            <button type="button" class="flex text-sm rounded-full md:me-0 focus:ring-4 focus:ring-neutral-tertiary" id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown" data-dropdown-placement="bottom">
                <span class="sr-only">Buka menu pengguna</span>
                <?php $foto = auth()->user()->foto ? 'uploads/profile/' . auth()->user()->foto : 'assets/profile_default.png'; ?>
                        <img class="w-10 h-10 rounded-full" src="<?= base_url($foto) ?>" alt="user photo">
            </button>

            <div class="z-50 hidden bg-neutral-primary-medium border border-default-medium rounded-base shadow-lg w-44" id="user-dropdown">
                <div class="px-4 py-3 text-sm border-b border-default">
                    <span class="block text-heading font-medium"><?= auth()->user()->username?></span>
                    <span class="block text-body truncate"><?= auth()->user()->email ?></span>
                </div>
                <ul class="p-2 text-sm text-body font-medium" aria-labelledby="user-menu-button">
                    <?php if (auth()->user()->inGroup('admin')) : ?>
                        <li>
                            <a href="/admin/dashboard" class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded">Dashboard Admin</a>
                        </li>
                    <?php endif; ?>
                    <li><a href="/profile" class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded">Profile Saya</a></li>
                    <li><a href="/profile/edit" class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded">Edit Profile</a></li>
                    <li><a href="/profile/history" class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded">Riwayat Transaksi</a></li>
                    <li><a href="<?= base_url('logout') ?>" class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded">Sign out</a></li>
                </ul>
            </div>

            <?php else: ?>
                <button type="button" onclick="window.location.href='<?= base_url('login') ?>'" class="text-black bg-yellow-accent-normal hover:bg-yellow-accent-strong box-border border border-transparent focus:ring-4 focus:ring-yellow-accent-medium shadow-xs font-medium leading-5 rounded-base text-sm px-3 py-2 focus:outline-none">
                    <span class="inline md:hidden">Masuk</span>
                    <span class="hidden md:inline">Masuk atau Daftar</span>
                </button>
            <?php endif; ?>

            <button data-collapse-toggle="navbar-sticky" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-body rounded-base md:hidden hover:bg-neutral-secondary-soft hover:text-heading focus:outline-none focus:ring-2 focus:ring-neutral-tertiary" aria-controls="navbar-sticky" aria-expanded="false">
                <span class="sr-only">Open main menu</span>
                    <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M5 7h14M5 12h14M5 17h14"/></svg>
            </button>
        </div>

        <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-sticky">
            <ul class="flex flex-col p-4 md:p-0 mt-4 font-medium border border-default bg-white md:bg-transparent rounded-base md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0">
                <li><a href="/" class="block py-2 px-3 text-white bg-brand rounded-sm md:text-heading md:bg-transparent md:p-0 " aria-current="page">Home</a></li>
                <li><a href="/tentang" class="block py-2 px-3 text-heading rounded hover:bg-neutral-tertiary md:text-black md:hover:bg-transparent md:border-0 md:hover:text-yellow-bright-normal-hover md:p-0 md:dark:hover:bg-transparent">Tentang</a></li>
                <li><a href="/concerts" class="block py-2 px-3 text-heading rounded hover:bg-neutral-tertiary md:text-black md:hover:bg-transparent md:border-0 md:hover:text-yellow-bright-normal-hover md:p-0 md:dark:hover:bg-transparent">Konser</a></li>
                <li><a href="/events" class="block py-2 px-3 text-heading rounded hover:bg-neutral-tertiary md:text-black md:hover:bg-transparent md:border-0 md:hover:text-yellow-bright-normal-hover md:p-0 md:dark:hover:bg-transparent">Event</a></li>
                <li><a href="/festivals" class="block py-2 px-3 text-heading rounded hover:bg-neutral-tertiary md:text-black md:hover:bg-transparent md:border-0 md:hover:text-yellow-bright-normal-hover md:p-0 md:dark:hover:bg-transparent">Festival</a></li>
            </ul>
        </div>
  </div>
</nav>

<!-- Modal Pop Up -->
<?php 
        $checkoutSession = session()->get('checkout_process');
        $hasCheckoutSession = !empty($checkoutSession) && 
                              session()->has('checkout_expire') && 
                              (session()->get('checkout_expire') > time());

        $hasPendingOrder = session()->has('pending_order_id');

        $resumeLink = '/checkout/personal_info';
        
        if ($hasPendingOrder) {
            $resumeLink = '/checkout/pay/' . session()->get('pending_order_id');
            
        } elseif ($hasCheckoutSession) {
            if (!empty($checkoutSession['payment_method'])) {
                $resumeLink = '/checkout/review_order';
            } elseif (!empty($checkoutSession['personal_data'])) {
                $resumeLink = '/checkout/payment_method';
            } else {
                $resumeLink = '/checkout/personal_info';
            }
        }
    ?>

<?php if ($hasCheckoutSession || $hasPendingOrder): ?>
        <script>
            var HAS_ACTIVE_SESSION = true;
        </script>

        <!-- Pop Up Modal Active Session -->
        <div id="active-session-modal" tabindex="-1" class="fixed inset-0 z-100 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm animate-fade-in">
            <div class="relative w-full max-w-md p-4">
                <div class="relative bg-white rounded-xl shadow-2xl border-t-4 border-yellow-400 overflow-hidden">
                    <div class="p-6 text-center">
                        <div class="mx-auto mb-4 text-yellow-500 bg-yellow-50 w-16 h-16 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>

                        <h3 class="mb-2 text-xl font-bold text-gray-900">Transaksi Belum Selesai!</h3>
                        <p class="text-gray-500 mb-6 text-sm">
                            Halo! Kamu masih punya sesi pemesanan tiket yang belum diselesaikan. Stok tiket sedang kami tahan untukmu.
                        </p>
                        
                        <!-- Tombol Lanjutkan dan Batalkan -->
                        <div class="flex flex-col gap-3">
                            <a href="<?= $resumeLink ?>" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-bold rounded-lg text-sm px-5 py-3 text-center shadow-lg transition-all">
                                <?= $hasPendingOrder ? 'Bayar Sekarang' : 'Lanjutkan Pesanan' ?>
                            </a>
                            <a href="/checkout/cancel" class="w-full text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:ring-4 focus:outline-none focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-3 text-center transition-all">
                                Batalkan Pesanan Ini
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <script>
        var CI_FLASH_MESSAGES = {
            success: <?= json_encode(session()->getFlashdata('success')) ?>,
            error:   <?= json_encode(session()->getFlashdata('error')) ?>,
            warning: <?= json_encode(session()->getFlashdata('warning')) ?>,
            errors:  <?= json_encode(session()->getFlashdata('errors')) ?>
        };
    </script>
