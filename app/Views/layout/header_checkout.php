<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Ticketly Checkout') ?></title>
    <link rel="icon" href="<?= base_url('assets/favicon.png') ?>" type="image/png">
    <link href="<?= base_url('output.css') ?>" rel="stylesheet">
    <script src="<?= base_url('flowbite.min.js') ?>"></script>
    <script src="<?= base_url('js/app.js') ?>"></script>
    <script>var CI_TIME_LEFT = <?= isset($time_left) ? $time_left : 0 ?>;</script>
</head>


<body class="bg-gray-50 flex flex-col min-h-screen font-sans">

<!-- Navbar dan Stepper Checkout-->
<header class="bg-white border-b border-gray-200 fixed top-0 left-0 w-full z-50 shadow-sm transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col xl:flex-row justify-between items-center py-4 gap-6">
                
                <a href="<?= base_url('/') ?>" class="flex items-center space-x-3 rtl:space-x-reverse">
                    <img src="<?= base_url('assets/ticketly-logo.png') ?>" class="h-14" alt="ticketly Logo">
                </a>
                
                <div class="w-full xl:w-auto overflow-x-auto [&::-webkit-scrollbar]:hidden pb-2 xl:pb-0">
                    <ol class="flex items-center w-full min-w-[800px] xl:min-w-0 gap-4">
                        
                        <?php 
                            $s1_done   = (isset($step) && $step > 1);
                            $s1_active = (isset($step) && $step == 1);
                            $s1_text   = $s1_active ? 'text-blue-600' : ($s1_done ? 'text-green-600' : 'text-gray-500');
                            $s1_bg     = $s1_active ? 'bg-blue-100 ring-4 ring-blue-50' : ($s1_done ? 'bg-green-100' : 'bg-gray-100');
                            $s1_bar    = $s1_done ? 'after:border-green-200' : 'after:border-gray-100';
                        ?>
                        <li class="flex w-full items-center <?= $s1_text ?> after:content-[''] after:w-full after:h-1 after:border-b after:border-4 after:inline-block after:mx-4 <?= $s1_bar ?>">
                            <div class="flex items-center whitespace-nowrap">
                                <span class="flex items-center justify-center w-10 h-10 rounded-full lg:h-12 lg:w-12 shrink-0 <?= $s1_bg ?>">
                                    <svg class="w-5 h-5 lg:w-6 lg:h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0 0a8.949 8.949 0 0 0 4.951-1.488A3.987 3.987 0 0 0 13 16h-2a3.987 3.987 0 0 0-3.951 3.512A8.948 8.948 0 0 0 12 21Zm3-11a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                                </span>
                                <span class="ms-3">
                                    <h3 class="font-medium leading-tight text-sm md:text-base">Data Diri</h3>
                                    <p class="text-xs hidden md:block">Info pemesan</p>
                                </span>
                            </div>
                        </li>

                        <?php 
                            $s2_done   = (isset($step) && $step > 2);
                            $s2_active = (isset($step) && $step == 2);
                            $s2_text   = $s2_active ? 'text-blue-600' : ($s2_done ? 'text-green-600' : 'text-gray-500');
                            $s2_bg     = $s2_active ? 'bg-blue-100 ring-4 ring-blue-50' : ($s2_done ? 'bg-green-100' : 'bg-gray-100');
                            $s2_bar    = $s2_done ? 'after:border-green-200' : 'after:border-gray-100';
                        ?>
                        <li class="flex w-full items-center <?= $s2_text ?> after:content-[''] after:w-full after:h-1 after:border-b after:border-4 after:inline-block after:mx-4 <?= $s2_bar ?>">
                            <div class="flex items-center whitespace-nowrap">
                                <span class="flex items-center justify-center w-10 h-10 rounded-full lg:h-12 lg:w-12 shrink-0 <?= $s2_bg ?>">
                                    <svg class="w-5 h-5 lg:w-6 lg:h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M6 14h2m3 0h5M3 7v10a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1Z"/></svg>
                                </span>
                                <span class="ms-3">
                                    <h3 class="font-medium leading-tight text-sm md:text-base">Pembayaran</h3>
                                    <p class="text-xs hidden md:block">Metode bayar</p>
                                </span>
                            </div>
                        </li>

                        <?php 
                            $s3_done   = (isset($step) && $step > 3);
                            $s3_active = (isset($step) && $step == 3);
                            $s3_text   = $s3_active ? 'text-blue-600' : ($s3_done ? 'text-green-600' : 'text-gray-500');
                            $s3_bg     = $s3_active ? 'bg-blue-100 ring-4 ring-blue-50' : ($s3_done ? 'bg-green-100' : 'bg-gray-100');
                            $s3_bar    = $s3_done ? 'after:border-green-200' : 'after:border-gray-100';
                        ?>
                        <li class="flex w-full items-center <?= $s3_text ?> after:content-[''] after:w-full after:h-1 after:border-b after:border-4 after:inline-block after:mx-4 <?= $s3_bar ?>">
                            <div class="flex items-center whitespace-nowrap">
                                <span class="flex items-center justify-center w-10 h-10 rounded-full lg:h-12 lg:w-12 shrink-0 <?= $s3_bg ?>">
                                    <svg class="w-5 h-5 lg:w-6 lg:h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2m-6 9 2 2 4-4"/></svg>
                                </span>
                                <span class="ms-3">
                                    <h3 class="font-medium leading-tight text-sm md:text-base">Konfirmasi</h3>
                                    <p class="text-xs hidden md:block">Cek pesanan</p>
                                </span>
                            </div>
                        </li>

                        <?php 
                            $s4_active = (isset($step) && $step == 4);
                            $s4_text   = $s4_active ? 'text-blue-600' : 'text-gray-500';
                            $s4_bg     = $s4_active ? 'bg-blue-100 ring-4 ring-blue-50' : 'bg-gray-100';
                        ?>
                        <li class="flex items-center <?= $s4_text ?>">
                            <div class="flex items-center whitespace-nowrap">
                                <span class="flex items-center justify-center w-10 h-10 rounded-full lg:h-12 lg:w-12 shrink-0 <?= $s4_bg ?>">
                                    <svg class="w-5 h-5 lg:w-6 lg:h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8H5a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2Zm0 0V6a3 3 0 1 0-6 0v2"/></svg>
                                </span>
                                <span class="ms-3">
                                    <h3 class="font-medium leading-tight text-sm md:text-base">Bayar</h3>
                                    <p class="text-xs hidden md:block">Selesaikan</p>
                                </span>
                            </div>
                        </li>

                    </ol>
                </div>

            </div>
        </div>
    </header>

    <!-- Popup Salin -->
    <div id="custom-copy-popup" class="fixed inset-0 z-100 hidden items-center justify-center pointer-events-none">
        <div class="bg-gray-900/90 backdrop-blur-md text-white px-6 py-3 rounded-full shadow-2xl flex items-center gap-3 animate-fade-in-up transform transition-all scale-100">
            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <span class="font-medium text-sm tracking-wide">Nomor VA Berhasil Disalin</span>
        </div>
    </div>


    <!-- Pop Up Timer Checkout -->
    <?php if (!isset($enable_floating_timer) || $enable_floating_timer === true): ?>
    <div id="checkout-timer-alert" class="fixed top-44 ta:top-24 left-0 w-full z-9999 flex justify-center pointer-events-none transition-all duration-300 animate-fade-in-down">    
            <div class="pointer-events-auto flex items-center gap-3.5 px-6 py-3 bg-blue-50 text-blue-800 border border-blue-200 rounded-full shadow-2xl shadow-blue-900/20 transition-colors duration-300">  
                <div class="flex items-center justify-center w-8 h-8 bg-white text-blue-600 rounded-full shadow-sm shrink-0">
                    <svg class="w-4 h-4 animate-pulse" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-[11px] font-extrabold uppercase tracking-widest opacity-60 pt-px">Sisa Waktu</span>
                    <span id="timer-countdown" class="font-sans font-black text-xl tabular-nums leading-none pb-0.5">00:00</span>
                </div>
            </div>
        </div>
    <?php endif; ?>


    <!-- Modal Timeout Checkout -->
    <div id="timeout-modal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full bg-gray-900/60 backdrop-blur-sm">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow-xl border border-gray-200">
                <div class="p-4 md:p-5 text-center">
                    <svg class="mx-auto mb-4 text-red-600 w-12 h-12" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                    <h3 class="mb-2 text-lg font-bold text-gray-900">Waktu Pemesanan Habis!</h3>
                    <p class="mb-6 text-gray-500">Maaf, sesi Anda telah berakhir. Tiket telah dikembalikan ke stok.</p>
                    <a href="/" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-24 right-5 z-50 flex flex-col gap-2"></div>

    <script>
        var CI_TIME_LEFT = <?= isset($time_left) ? $time_left : 0 ?>;
    
        var CI_FLASH_MESSAGES = {
            success: <?= json_encode(session()->getFlashdata('success')) ?>,
            error:   <?= json_encode(session()->getFlashdata('error')) ?>,
            warning: <?= json_encode(session()->getFlashdata('warning')) ?>,
            errors:  <?= json_encode(session()->getFlashdata('errors')) ?>
        };
    </script>