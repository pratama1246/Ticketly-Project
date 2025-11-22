<main class="w-full pt-24 grow">

<!-- CAROUSEL -->     
       <div id="default-carousel" class="relative w-full" data-carousel="slide">
        <div class="relative overflow-hidden aspect-video shadow-2xl">
            
            <?php if (!empty($featured)): ?>
                <?php foreach ($featured as $index => $item): ?>
                    
                    <div class="hidden duration-1000 ease-in-out" data-carousel-item>
                        <a href="/event/<?= $item['slug'] ?>" class="group block w-full h-full relative">
                            
                            <img src="<?= base_url($item['poster_image']) ?>" 
                                 class="absolute block w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" 
                                 alt="<?= esc($item['name']) ?>">
                            
                            <div class="absolute inset-0 bg-linear-to-t from-black/90 via-black/50 to-transparent"></div>

                            <div class="absolute bottom-0 left-0 w-full px-6 pb-14 pt-6 md:px-16 md:pb-24 md:pt-12 flex flex-col justify-end h-full pointer-events-none">
                                <div class="max-w-4xl space-y-2 md:space-y-4">
                                    
                                    <span class="inline-block py-1 px-3 rounded-full bg-blue-600/90 text-white text-xs md:text-sm font-bold tracking-wider uppercase mb-2 backdrop-blur-sm">
                                        <?= esc(ucfirst($item['category'] ?? 'Featured')) ?>
                                    </span>

                                    <h2 class="text-2xl md:text-5xl lg:text-6xl font-extrabold text-white leading-tight drop-shadow-lg">
                                        <?= esc($item['name']) ?>
                                    </h2>

                                    <div class="flex flex-col md:flex-row md:items-center gap-3 text-gray-200 text-sm md:text-lg font-medium">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <?= \CodeIgniter\I18n\Time::parse($item['event_date'])->toLocalizedString('d MMMM yyyy') ?>
                                        </div>
                                        
                                        <span class="hidden md:inline text-gray-500">•</span>
                                        
                                        <div class="flex items-center gap-2">
                                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            <span class="truncate max-w-[200px] md:max-w-none"><?= esc($item['venue']) ?></span>
                                        </div>
                                    </div>

                                    <div class="pt-4">
                                        <span class="inline-flex items-center gap-2 text-white font-bold border-b-2 border-yellow-400 pb-1 hover:text-yellow-300 transition-colors">
                                            Lihat Detail & Beli Tiket
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                        </span>
                                    </div>

                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="hidden duration-700 ease-in-out" data-carousel-item>
                    <img src="<?= base_url('assets/banner-default.jpg') ?>" class="absolute block w-full h-full object-cover" alt="Default">
                </div>
            <?php endif; ?>

        </div>

        <div class="absolute z-30 flex -translate-x-1/2 bottom-5 left-1/2 space-x-3 rtl:space-x-reverse">
            <?php if (!empty($featured)): ?>
                <?php foreach ($featured as $index => $item): ?>
                    <button type="button" class="w-3 h-3 md:w-4 md:h-4 rounded-full bg-white/50 hover:bg-white" 
                            aria-current="<?= $index === 0 ? 'true' : 'false' ?>" 
                            aria-label="Slide <?= $index + 1 ?>" 
                            data-carousel-slide-to="<?= $index ?>"></button>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <button type="button" class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-prev>
            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/10 group-hover:bg-white/30 group-focus:ring-4 group-focus:ring-white/50 backdrop-blur-sm">
                <svg class="w-4 h-4 text-white rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/></svg>
                <span class="sr-only">Previous</span>
            </span>
        </button>
        <button type="button" class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-next>
            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/10 group-hover:bg-white/30 group-focus:ring-4 group-focus:ring-white/50 backdrop-blur-sm">
                <svg class="w-4 h-4 text-white rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/></svg>
                <span class="sr-only">Next</span>
            </span>
        </button>
    </div>

<!-- JUDUL SECTION -->
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
            <div class="w-full overflow-x-auto [&::-webkit-scrollbar]:hidden snap-x snap-mandatory scroll-smooth pb-6 px-2">
                <div class="flex flex-nowrap space-x-5 items-stretch">
                    
                    <?php foreach ($events as $event): ?>
                        <div class="snap-center shrink-0 w-80 md:w-104 bg-white flex flex-col rounded-xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-2 group">
                            
                            <a href="/event/<?= $event['slug'] ?>" class="block relative aspect-video overflow-hidden">
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
                                <a href="/event/<?= $event['slug'] ?>">
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
                                    <a href="/event/<?= $event['slug'] ?>" class="block w-full text-center text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 font-medium rounded-lg text-sm px-5 py-2.5 transition-all duration-300 shadow-md hover:shadow-lg">
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
    renderEventSection('Konser Terbaru', $concerts, '', '/concerts');
    renderEventSection('Festival Seru', $festivals, '', '/festivals');
    renderEventSection('Event Lainnya', $events, '', '/events');
    ?>

</main>