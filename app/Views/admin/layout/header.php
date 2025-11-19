<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Admin Panel') ?> - Ticketly</title>
    <link rel="icon" href="<?= base_url('assets/favicon.png') ?>" type="image/png">
    <link href="<?= base_url('output.css') ?>" rel="stylesheet">
    <script src="https://cdn.tiny.cloud/1/o9x8q1gyscpyjvhjh1e6iurf4bdsr8lip0piuc59rsr5e85v/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>
    <script src="<?= base_url('flowbite.min.js') ?>"></script>
    <script src="<?= base_url('js/app.js') ?>"></script>
</head>

<body class="bg-gray-50">

<!-- NAVBAR ADMIN -->
<nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200">
  <div class="px-3 py-3 lg:px-5 lg:pl-3">
    <div class="flex items-center justify-between">
      <div class="flex items-center justify-start rtl:justify-end">
        <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
            <span class="sr-only">Buka sidebar</span>
            <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path></svg>
         </button>
        <a href="/admin" class="flex ms-2 md:me-24">
          <img src="<?= base_url('assets/ticketly-logo.png') ?>" class="h-12 me-3" alt="Ticketly Logo" />
        </a>
      </div>
      <div class="flex items-center">
          <button type="button" class="flex text-sm rounded-full focus:ring-4 focus:ring-gray-300" id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown" data-dropdown-placement="bottom">
            <span class="sr-only">Open user menu</span>
            <img class="w-8 h-8 rounded-full" src="<?= base_url('assets/profile_default.png') ?>" alt="user photo">
          </button>
          <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow" id="user-dropdown">
            <div class="px-4 py-3">
              <span class="block text-sm text-gray-900"><?= auth()->user()->username ?? 'Admin' ?></span>
              <span class="block text-sm text-gray-500 truncate"><?= auth()->user()->email ?? '' ?></span>
            </div>
            <ul class="py-1" aria-labelledby="user-menu-button">
              <li><a href="/" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" target="_blank">Lihat Situs Publik</a></li>
              <li><a href="<?= base_url('logout') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sign out</a></li>
            </ul>
          </div>
        </div>
    </div>
  </div>
</nav>

<!-- SIDEBAR ADMIN -->
<aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0" aria-label="Sidebar">
   <div class="h-full px-3 pb-4 overflow-y-auto bg-white">
      <ul class="space-y-2 font-medium">
         <li>
            <?php $uri = service('uri'); ?>
            <a href="/admin" class="flex items-center p-2 rounded-lg group <?= $uri->getSegment(2) == '' ? 'bg-gray-200 text-black' : 'text-gray-900 hover:bg-gray-100' ?>">
               <svg class="w-5 h-5 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21"><path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z"/><path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z"/></svg>
               <span class="ms-3">Dashboard</span>
            </a>
         </li>
         <li>
            <a href="/admin/events" class="flex items-center p-2 rounded-lg group <?= $uri->getSegment(2) == 'events' ? 'bg-gray-200 text-black' : 'text-gray-900 hover:bg-gray-100' ?>">
               <svg class="w-5 h-5 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18"><path d="M17 0h-5.768a1 1 0 0 0-.9.58L8.382 5H5.4a1 1 0 0 0-.9.58L1.58 11H0v7h18v-7h-1.58l-2.92-5.42A1 1 0 0 0 12.568 5h-2.982L11.5 1.58A1 1 0 0 0 10.6 1H9.4a1 1 0 0 0-.9.58L6.5 5H3.568a1 1 0 0 0-.9.58L0 11v5h2v-3h14v3h2v-5l-2.618-5.42a1 1 0 0 0-.9-.58H13.4l-2-3.42A1 1 0 0 0 10.6 1H9.4Z"/></svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Events</span>
            </a>
         </li>
         <li>
            <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
               <svg class="w-5 h-5 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V4Zm-2 13H4V8h14v9Z"/></svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Orders</span>
            </a>
         </li>
      </ul>
   </div>
</aside>

<div class="p-4 sm:ml-64">
   <div class="mt-14">