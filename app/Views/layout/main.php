<main class="w-full pt-24 grow">
<div class="max-w-9xl my-10 p-2 text-center">
    <h1 class="text-4xl md:text-5xl font-bold text-black mb-4 lg:mb-6">
            Temukan Pengalaman <span class="bg-blue-secondary-normal text-white p-2.5 inline-block">Seru</span> Berikutnya
        </h1>
        <p class="text-lg text-gray-600 mb-8">
            Beli tiket konser, festival, dan event favoritmu di Ticketly.
        </p>

        <div class="max-w-2xl mx-auto">
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/></svg>
                </div>
                <input type="search" id="hero-search" 
                       class="block w-full p-4 ps-12 text-md text-gray-900 border border-gray-300 rounded-lg bg-white focus:ring-yellow-accent-normal focus:border-yellow-accent-normal" 
                       placeholder="Cari sesuatu...">
                <button type="submit" class="text-black absolute end-2.5 bottom-2.5 bg-yellow-accent-normal hover:bg-yellow-accent-dark font-medium rounded-lg text-sm px-4 py-2">
                    Search
                </button>
            </div>
        </div>

        <div class="mt-6 flex justify-center gap-2">
           
        </div>
    </div>
</div>

<!-- CAROUSEL -->
<div id="default-carousel" class="relative w-full" data-carousel="slide">
    <div class="relative overflow-hidden aspect-video">
        
        <div class="hidden duration-700 ease-in-out" data-carousel-item>
            <a href="event/1">
            <img src="<?= base_url('assets/banner/tds-4.jpg') ?>" class="absolute block w-full h-full object-cover top-0 left-0" alt="..."></a>
        </div>

        <div class="hidden duration-700 ease-in-out" data-carousel-item>
            <img src="<?= base_url('assets/banner/riizing-loud.png') ?>" class="absolute block w-full h-full object-cover top-0 left-0" alt="...">
        </div>

        <div class="hidden duration-700 ease-in-out" data-carousel-item>
            <img src="<?= base_url('assets/banner/deadline-tour.webp') ?>" class="absolute block w-full h-full object-cover top-0 left-0" alt="...">
        </div>
        
        <div class="hidden duration-700 ease-in-out" data-carousel-item>
            <img src="<?= base_url('assets/banner/aesix-aespa.jpg') ?>" class="absolute block w-full h-full object-cover top-0 left-0" alt="...">
        </div>
        
        <div class="hidden duration-700 ease-in-out" data-carousel-item>
            <img src="<?= base_url('assets/banner/wish-login.jpg') ?>" class="absolute block w-full h-full object-cover top-0 left-0" alt="...">
        </div>
    </div>

    <div class="absolute z-30 flex -translate-x-1/2 bottom-5 left-1/2 space-x-3 rtl:space-x-reverse">
        <button type="button" class="w-3 h-3 rounded-full" aria-current="true" aria-label="Slide 1" data-carousel-slide-to="0"></button>
        <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide 2" data-carousel-slide-to="1"></button>
        <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide 3" data-carousel-slide-to="2"></button>
        <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide 4" data-carousel-slide-to="3"></button>
        <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide 5" data-carousel-slide-to="4"></button>
    </div>

    <button type="button" class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-prev>
        <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
            <svg class="w-5 h-5 text-white rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 19-7-7 7-7"/></svg>
            <span class="sr-only">Previous</span>
        </span>
    </button>
    <button type="button" class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-next>
        <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
            <svg class="w-5 h-5 text-white rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"/></svg>
            <span class="sr-only">Next</span>
        </span>
    </button>
</div>

<!-- EVENT SECTIONS -->
    <?php 
    function renderEventSection($title, $events, $icon, $link) {
        if (empty($events)) return; 
    ?>
        <div class="mx-auto my-8 p-2 md:p-4">
            <div class="flex justify-between items-end px-2 mb-6 border-l-4 border-blue-600 pl-4 ml-2">
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900 flex items-center gap-2">
                        <?= $icon ?> <?= $title ?>
                    </h2>
                </div>
                <a href="<?= $link ?>" class="group text-blue-600 hover:text-blue-800 font-semibold text-sm flex items-center transition-colors mr-2">
                    Lihat Semua 
                    <svg class="w-4 h-4 ms-1 transform transition-transform group-hover:translate-x-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m14 0-4 4m4-4-4-4"/></svg>
                </a>
            </div>
            
            <!-- Card Container -->
            <div class="w-full overflow-x-auto scrollbar-none [&::-webkit-scrollbar]:hidden snap-x snap-mandatory scroll-smooth pb-6 px-2">
                <div class="flex flex-nowrap space-x-5 items-stretch">
                    
                    <?php foreach ($events as $event): ?>
                        <div class="snap-center shrink-0 w-80 md:w-104 bg-white flex flex-col rounded-xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-2 group">
                            
                            <a href="/event/<?= $event['id'] ?>" class="block relative aspect-video overflow-hidden">
                                <img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" 
                                     src="<?= base_url($event['poster_image']) ?>" 
                                     alt="<?= esc($event['name']) ?>" />
                                
                                <div class="absolute inset-0 bg-linear-to-t from-black/70 via-transparent to-transparent opacity-60 group-hover:opacity-80 transition-opacity duration-300"></div>
                                
                                <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-md px-2.5 py-1 rounded-md text-xs font-bold text-gray-900 shadow-sm z-10">
                                <div class="text-center leading-tight">
                                        <span class="block text-red-600 uppercase text-2xs"><?= (new \DateTime($event['event_date']))->format('M') ?></span>
                                        <span class="block text-lg"><?= (new \DateTime($event['event_date']))->format('d') ?></span>
                                    </div>
                                </div>
                            </a>
                            
                            <div class="p-5 flex flex-col grow">
                                <a href="/event/<?= $event['id'] ?>">
                                    <h5 class="mb-2 text-lg font-bold tracking-tight text-gray-900 line-clamp-2 leading-snug group-hover:text-blue-700 transition-colors">
                                        <?= esc($event['name']) ?>
                                    </h5>
                                </a>
                                
                                <div class="mt-2 mb-4 space-y-1">
                                    <p class="text-gray-500 text-xs font-medium flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                                        <?= esc($event['venue']) ?>
                                    </p>
                                    <p class="text-gray-500 text-xs font-medium flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>
                                        <?= (new \DateTime($event['event_date']))->format('H:i') ?> WIB
                                    </p>
                                </div>
                                
                                <div class="mt-auto"> 
                                    <a href="/event/<?= $event['id'] ?>" class="block w-full text-center text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 font-medium rounded-lg text-sm px-5 py-2.5 transition-all duration-300 shadow-md hover:shadow-lg">
                                        Selengkapnya
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>
            </div>
        </div>
    
    <?php 
    }
    
    // Panggil fungsi render
    renderEventSection('Konser Terbaru', $concerts, '', '/events/concert');
    renderEventSection('Festival Seru', $festivals, '', '/events/festival');
    renderEventSection('Event Lainnya', $events, '', '/events/other');
    ?>

<!-- ABOUT US -->
<div class="mx-auto p-4 mb-20 px-6 md:px-8">
        <div class="p-8 md:p-12 rounded-2xl bg-white border border-gray-100 shadow-lg text-center md:text-left">
            <div class="flex flex-col md:flex-row items-center gap-8">
                <div class="shrink-0">
                     <div class="w-20 h-20 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center">
                        <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                     </div>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">Tentang Ticketly</h1>
                    <div class="prose text-gray-600 max-w-none space-y-4">
                        <p>Ticketly adalah platform penjualan tiket daring terkemuka yang didedikasikan untuk memberikan pengalaman terbaik bagi para penggemar acara di seluruh dunia. Kami memahami betapa pentingnya momen spesial dalam hidup Anda.</p>
                        <p>Dengan antarmuka yang ramah pengguna dan sistem pembayaran yang aman, Ticketly memudahkan Anda untuk menemukan, memilih, dan membeli tiket untuk acara favorit Anda dengan cepat dan nyaman.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>