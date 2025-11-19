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


<!-- EVENT LIST -->
    <div class="max-w-9xl mx-auto my-4 p-2 mt-10">
        <div class="flex justify-between items-center px-4">
            <h1 class="text-3xl font-bold text-black mb-4">Temukan Konser Terbaru</h1>
            <a href="#" class="text-blue-600 hover:underline font-medium">Lihat Semua</a>
        </div>
        
        <div class="w-full overflow-x-auto scrollbar-none [&::-webkit-scrollbar]:hidden">
            <div class="flex flex-nowrap space-x-4 p-4 items-stretch">
                
                <?php if (empty($concerts)): ?>
                    <div class="w-full text-center py-10 text-gray-500">Belum ada konser tersedia.</div>
                <?php else: ?>
                    <?php foreach ($concerts as $event): ?>
                        <div class="shrink-0 w-80 md:w-96 bg-neutral-primary-soft flex flex-col border border-default rounded-base shadow-xs overflow-hidden transition-transform hover:-translate-y-1 duration-300">
                            <a href="/event/<?= $event['id'] ?>" class="block aspect-video relative">
                                <img class="w-full h-full object-cover" 
                                     src="<?= base_url($event['poster_image']) ?>" 
                                     alt="<?= esc($event['name']) ?>" />
                                <div class="absolute top-2 right-2 bg-white/90 px-2 py-1 rounded text-xs font-bold text-black shadow">
                                    <?= (new \DateTime($event['event_date']))->format('d M') ?>
                                </div>
                            </a>
                            
                            <div class="p-6 grow flex flex-col">
                                <a href="/event/<?= $event['id'] ?>">
                                    <h5 class="mb-2 text-xl md:text-2xl font-semibold tracking-tight text-heading line-clamp-2">
                                        <?= esc($event['name']) ?>
                                    </h5>
                                </a>
                                <p class="mb-2 text-body text-sm">
                                    <?= (new \DateTime($event['event_date']))->format('d F Y, H:i') ?> WIB
                                </p>
                                <p class="mb-6 text-gray-500 text-sm line-clamp-1">
                                    📍 <?= esc($event['venue']) ?>
                                </p>
                            </div>
                            
                            <div class="p-6 pt-0 mt-auto"> 
                                <a href="/event/<?= $event['id'] ?>" class="inline-flex items-center justify-center w-full text-body bg-neutral-secondary-medium box-border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none transition-colors">
                                    Lihat Detail
                                    <svg class="w-4 h-4 ms-1.5 rtl:rotate-180 -me-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m14 0-4 4m4-4-4-4"/></svg>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <div class="max-w-9xl mx-auto my-4 p-2">
        <div class="flex justify-between items-center px-4">
            <h1 class="text-3xl font-bold text-black mb-4">Jelajahi Festival Yang Seru</h1>
            <a href="#" class="text-blue-600 hover:underline font-medium">Lihat Semua</a>
        </div>
        
        <div class="w-full overflow-x-auto scrollbar-none [&::-webkit-scrollbar]:hidden">
            <div class="flex flex-nowrap space-x-4 p-4 items-stretch">
                
                <?php if (empty($festivals)): ?>
                    <div class="w-full text-center py-10 text-gray-500">Belum ada festival tersedia.</div>
                <?php else: ?>
                    <?php foreach ($festivals as $event): ?>
                        <div class="shrink-0 w-80 md:w-96 bg-neutral-primary-soft flex flex-col border border-default rounded-base shadow-xs overflow-hidden transition-transform hover:-translate-y-1 duration-300">
                            <a href="/event/<?= $event['id'] ?>" class="block aspect-video relative">
                                <img class="w-full h-full object-cover" src="<?= base_url($event['poster_image']) ?>" alt="<?= esc($event['name']) ?>" />
                                <div class="absolute top-2 right-2 bg-white/90 px-2 py-1 rounded text-xs font-bold text-black shadow">
                                    <?= (new \DateTime($event['event_date']))->format('d M') ?>
                                </div>
                            </a>
                            
                            <div class="p-6 grow flex flex-col">
                                <a href="/event/<?= $event['id'] ?>">
                                    <h5 class="mb-2 text-xl md:text-2xl font-semibold tracking-tight text-heading line-clamp-2">
                                        <?= esc($event['name']) ?>
                                    </h5>
                                </a>
                                <p class="mb-2 text-body text-sm">
                                    <?= (new \DateTime($event['event_date']))->format('d F Y') ?>
                                </p>
                                <p class="mb-6 text-gray-500 text-sm line-clamp-1">
                                    📍 <?= esc($event['venue']) ?>
                                </p>
                            </div>
                            
                            <div class="p-6 pt-0 mt-auto"> 
                                <a href="/event/<?= $event['id'] ?>" class="inline-flex items-center justify-center w-full text-body bg-neutral-secondary-medium box-border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none transition-colors">
                                    Lihat Detail
                                    <svg class="w-4 h-4 ms-1.5 rtl:rotate-180 -me-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m14 0-4 4m4-4-4-4"/></svg>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <div class="max-w-9xl mx-auto my-4 p-2 mb-20">
        <div class="flex justify-between items-center px-4">
            <h1 class="text-3xl font-bold text-black mb-4">Lihat Event Lainnya</h1>
            <a href="#" class="text-blue-600 hover:underline font-medium">Lihat Semua</a>
        </div>
        
        <div class="w-full overflow-x-auto scrollbar-none [&::-webkit-scrollbar]:hidden">
            <div class="flex flex-nowrap space-x-4 p-4 items-stretch">
                
                <?php if (empty($events)): ?>
                    <div class="w-full text-center py-10 text-gray-500">Belum ada event lainnya.</div>
                <?php else: ?>
                    <?php foreach ($events as $event): ?>
                        <div class="shrink-0 w-80 md:w-96 bg-neutral-primary-soft flex flex-col border border-default rounded-base shadow-xs overflow-hidden transition-transform hover:-translate-y-1 duration-300">
                            <a href="/event/<?= $event['id'] ?>" class="block aspect-video relative">
                                <img class="w-full h-full object-cover" src="<?= base_url($event['poster_image']) ?>" alt="<?= esc($event['name']) ?>" />
                                <div class="absolute top-2 right-2 bg-white/90 px-2 py-1 rounded text-xs font-bold text-black shadow">
                                    <?= (new \DateTime($event['event_date']))->format('d M') ?>
                                </div>
                            </a>
                            
                            <div class="p-6 grow flex flex-col">
                                <a href="/event/<?= $event['id'] ?>">
                                    <h5 class="mb-2 text-xl md:text-2xl font-semibold tracking-tight text-heading line-clamp-2">
                                        <?= esc($event['name']) ?>
                                    </h5>
                                </a>
                                <p class="mb-2 text-body text-sm">
                                    <?= (new \DateTime($event['event_date']))->format('d F Y') ?>
                                </p>
                                <p class="mb-6 text-gray-500 text-sm line-clamp-1">
                                    📍 <?= esc($event['venue']) ?>
                                </p>
                            </div>
                            
                            <div class="p-6 pt-0 mt-auto"> 
                                <a href="/event/<?= $event['id'] ?>" class="inline-flex items-center justify-center w-full text-body bg-neutral-secondary-medium box-border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none transition-colors">
                                    Lihat Detail
                                    <svg class="w-4 h-4 ms-1.5 rtl:rotate-180 -me-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m14 0-4 4m4-4-4-4"/></svg>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
        </div>
    </div>

<!-- ABOUT US -->
<div class="max-w-8xl mx-auto p-4 mb-20">
        <div class="p-6 md:p-10 rounded-lg border border-solid-black">
            <h1 class="text-2xl font-bold text-black">Tentang Kami</h1>
            <p class="mt-4 text-body">Ticketly adalah platform penjualan tiket daring terkemuka yang didedikasikan untuk memberikan pengalaman terbaik bagi para penggemar acara di seluruh dunia. Kami memahami betapa pentingnya momen spesial dalam hidup Anda, dan itulah mengapa kami berkomitmen untuk menyediakan akses mudah dan aman ke berbagai acara, mulai dari konser musik, pertunjukan teater, festival budaya, hingga acara olahraga.</p>
            <p class="mt-4 text-body">Dengan antarmuka yang ramah pengguna dan sistem pembayaran yang aman, Ticketly memudahkan Anda untuk menemukan, memilih, dan membeli tiket untuk acara favorit Anda dengan cepat dan nyaman. Kami juga menawarkan berbagai fitur tambahan, seperti notifikasi acara, rekomendasi personal, dan dukungan pelanggan 24/7 untuk memastikan pengalaman Anda bersama kami selalu menyenangkan.</p>
            <p class="mt-4 text-body">Bergabunglah dengan jutaan pengguna yang telah mempercayai Ticketly sebagai mitra mereka dalam menikmati hiburan berkualitas. Kami berkomitmen untuk terus meningkatkan layanan kami dan menghadirkan inovasi terbaru agar setiap momen bersama Ticketly menjadi kenangan tak terlupakan.</p>
        </div>
</div>
</main>