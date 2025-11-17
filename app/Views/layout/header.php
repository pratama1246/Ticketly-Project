<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="<?= base_url('assets/favicon.png') ?>" type="image/png">
        <link href="<?= base_url('output.css') ?>" rel="stylesheet">
        <script src="<?= base_url('flowbite.min.js') ?>"></script>
    <title>Ticketly - Nikmati Konsermu</title>
</head>

<!-- NAVBAR -->
<<body class="font-default bg-yellow-bright-light text-heading flex flex-col min-h-screen">
<nav class="bg-yellow-bright-light fixed w-full z-50 top-0 start-0 border-b border-default">
  <div class="max-w-7xl flex flex-wrap items-center justify-between mx-auto p-4">
    <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
         <img src="<?= base_url('assets/ticketly-logo.png') ?>" class="h-14" alt="ticketly Logo">
    </a>
    <div class="flex md:order-2 space-x-3 md:space-x-3 rtl:space-x-reverse">

        <!-- LOGIN LOGIC -->
        <?php if (auth()->loggedIn()): ?>
            <button type="button" class="flex text-sm rounded-full md:me-0 focus:ring-4 focus:ring-neutral-tertiary" id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown" data-dropdown-placement="bottom">
                <span class="sr-only">Open user menu</span>
                <img class="w-10 h-10 rounded-full" src="<?= base_url('assets/profile_default.png') ?>" alt="user photo">
            </button>

            <div class="z-50 hidden bg-neutral-primary-medium border border-default-medium rounded-base shadow-lg w-44" id="user-dropdown">
                <div class="px-4 py-3 text-sm border-b border-default">
                    <span class="block text-heading font-medium"><?= auth()->user()->username // atau nama depan ?></span>
                    <span class="block text-body truncate"><?= auth()->user()->email ?></span>
                </div>
                <ul class="p-2 text-sm text-body font-medium" aria-labelledby="user-menu-button">
                    <li>
                        <a href="#" class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded">Dashboard</a>
                    </li>
                    <li>
                        <a href="#" class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded">Settings</a>
                    </li>
                    <li>
                        <a href="<?= base_url('logout') ?>" class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded">Sign out</a>
                    </li>
                </ul>
            </div>
            <?php else: ?>
            <button type="button" onclick="window.location.href='<?= base_url('login') ?>'" class="text-black bg-yellow-accent-normal hover:bg-yellow-accent-strong box-border border border-transparent focus:ring-4 focus:ring-yellow-accent-medium shadow-xs font-medium leading-5 rounded-base text-sm px-3 py-2 focus:outline-none">
                Login or Sign Up
            </button>
        <?php endif; ?>

        <button data-collapse-toggle="navbar-sticky" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-body rounded-base md:hidden hover:bg-neutral-secondary-soft hover:text-heading focus:outline-none focus:ring-2 focus:ring-neutral-tertiary" aria-controls="navbar-sticky" aria-expanded="false">
        <span class="sr-only">Open main menu</span>
            <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M5 7h14M5 12h14M5 17h14"/></svg>
        </button>
        </div>
        <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-sticky">
            <ul class="flex flex-col p-4 md:p-0 mt-4 font-medium border border-default rounded-base bg-neutral-secondary-soft md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-yellow-bright-light">
                <li>
                    <a href="/" class="block py-2 px-3 text-white bg-brand rounded-sm md:bg-transparent md:text-yellow-bright-dark-active md:p-0 " aria-current="page">Home</a>
                </li>
                <li>
                    <a href="#" class="block py-2 px-3 text-heading rounded hover:bg-neutral-tertiary md:text-black md:hover:bg-transparent md:border-0 md:hover:text-yellow-bright-normal-hover md:p-0 md:dark:hover:bg-transparent">Tentang</a>
                </li>
                <li>
                    <a href="#" class="block py-2 px-3 text-heading rounded hover:bg-neutral-tertiary md:text-black md:hover:bg-transparent md:border-0 md:hover:text-yellow-bright-normal-hover md:p-0 md:dark:hover:bg-transparent">Konser</a>
                </li>
                <li>
                    <a href="#" class="block py-2 px-3 text-heading rounded hover:bg-neutral-tertiary md:text-black md:hover:bg-transparent md:border-0 md:hover:text-yellow-bright-normal-hover md:p-0 md:dark:hover:bg-transparent">Event</a>
                </li>
                <li>
                    <a href="#" class="block py-2 px-3 text-heading rounded hover:bg-neutral-tertiary md:text-black md:hover:bg-transparent md:border-0 md:hover:text-yellow-bright-normal-hover md:p-0 md:dark:hover:bg-transparent">Festival</a>
                </li>
            </ul>
        </div>
  </div>
</nav>