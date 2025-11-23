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
                                            <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <?= \CodeIgniter\I18n\Time::parse($item['event_date'])->toLocalizedString('d MMMM yyyy') ?>
                                        </div>

                                        <span class="hidden md:inline text-gray-500">•</span>

                                        <div class="flex items-center gap-2">
                                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <span class="truncate max-w-[200px] md:max-w-none"><?= esc($item['venue']) ?></span>
                                        </div>
                                    </div>

                                    <div class="pt-4">
                                        <span class="inline-flex items-center gap-2 text-white font-bold border-b-2 border-yellow-400 pb-1 hover:text-yellow-300 transition-colors">
                                            Lihat Detail & Beli Tiket
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                            </svg>
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
                <svg class="w-4 h-4 text-white rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4" />
                </svg>
                <span class="sr-only">Previous</span>
            </span>
        </button>
        <button type="button" class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-next>
            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/10 group-hover:bg-white/30 group-focus:ring-4 group-focus:ring-white/50 backdrop-blur-sm">
                <svg class="w-4 h-4 text-white rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                </svg>
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
                    <svg class="w-5 h-5 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                    </svg>
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
    function renderEventSection($title, $events, $icon, $link)
    {
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
                    <svg class="w-4 h-4 ms-1 transform transition-transform group-hover:translate-x-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m14 0-4 4m4-4-4-4" />
                    </svg>
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
                                        <svg class="w-3.5 h-3.5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                        </svg>
                                        <?= esc($event['venue']) ?>
                                    </p>
                                    <p class="text-gray-500 text-xs font-medium flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                        </svg>
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

    <!-- Promo Section -->
    <section class="max-w-7xl mx-auto px-4 mb-20">
        <div class="relative rounded-2xl overflow-hidden bg-linear-to-r from-purple-900 to-blue-900 shadow-2xl">
            <img src="https://images.unsplash.com/photo-1459749411177-7129615a6f5c?auto=format&fit=crop&q=80&w=1600" 
                 class="absolute inset-0 w-full h-full object-cover opacity-20 mix-blend-overlay" alt="Concert">
            
            <div class="relative z-10 p-8 md:p-12 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="text-white">
                    <span class="bg-yellow-400 text-yellow-900 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide mb-3 inline-block">Promo Terbatas</span>
                    <h2 class="text-3xl md:text-4xl font-bold mb-2">Payday Sale! Diskon 20%</h2>
                    <p class="text-blue-100 max-w-lg">Gunakan kode <span class="font-mono font-bold text-white bg-white/20 px-2 rounded">PAYDAY25</span> untuk semua tiket konser internasional. Berlaku sampai akhir bulan.</p>
                </div>
                <a href="/events" class="bg-white text-blue-900 hover:bg-blue-50 font-bold py-3.5 px-8 rounded-xl shadow-lg transition-transform hover:scale-105 whitespace-nowrap">
                    Cek Promo Sekarang
                </a>
            </div>
        </div>
    </section>

    <!-- How to Buy Section -->
    <section class="max-w-7xl mx-auto px-4 mb-24 text-center">
        <div class="mb-12">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Cara Beli Tiket</h2>
            <p class="text-gray-500 mt-2">Dapatkan tiketmu hanya dalam hitungan menit.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 relative">
            <div class="hidden md:block absolute top-8 left-0 w-full h-0.5 bg-gray-100 -z-10"></div>

            <div class="bg-white p-6 rounded-xl border border-gray-100 hover:shadow-lg transition-all duration-300 group">
                <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold group-hover:bg-blue-600 group-hover:text-white transition-colors">1</div>
                <h3 class="font-bold text-lg mb-2">Pilih Event</h3>
                <p class="text-sm text-gray-500">Cari konser atau festival favoritmu di halaman utama.</p>
            </div>

            <div class="bg-white p-6 rounded-xl border border-gray-100 hover:shadow-lg transition-all duration-300 group">
                <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold group-hover:bg-blue-600 group-hover:text-white transition-colors">2</div>
                <h3 class="font-bold text-lg mb-2">Pilih Tiket</h3>
                <p class="text-sm text-gray-500">Tentukan kategori tiket dan jumlah yang diinginkan.</p>
            </div>

            <div class="bg-white p-6 rounded-xl border border-gray-100 hover:shadow-lg transition-all duration-300 group">
                <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold group-hover:bg-blue-600 group-hover:text-white transition-colors">3</div>
                <h3 class="font-bold text-lg mb-2">Bayar</h3>
                <p class="text-sm text-gray-500">Selesaikan pembayaran via Transfer Bank atau E-Wallet.</p>
            </div>

            <div class="bg-white p-6 rounded-xl border border-gray-100 hover:shadow-lg transition-all duration-300 group">
                <div class="w-16 h-16 bg-green-50 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold group-hover:bg-green-600 group-hover:text-white transition-colors">4</div>
                <h3 class="font-bold text-lg mb-2">Selesai!</h3>
                <p class="text-sm text-gray-500">E-Tiket otomatis dikirim ke email dan WhatsApp kamu.</p>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="bg-gray-50 py-16 mb-20 border-y border-gray-100">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Kata Mereka</h2>
                <p class="text-gray-500 mt-2">Pengalaman seru dari pengguna Ticketly.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex items-center gap-1 text-yellow-400 mb-3">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    </div>
                    <p class="text-gray-600 italic mb-4">"Gak nyangka beli tiket konser internasional semudah ini. Bayar pake QRIS langsung beres!"</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center font-bold text-gray-500">AS</div>
                        <div>
                            <h4 class="font-bold text-sm text-gray-900">Andi Saputra</h4>
                            <p class="text-xs text-gray-500">Fans NCT Dream</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex items-center gap-1 text-yellow-400 mb-3">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    </div>
                    <p class="text-gray-600 italic mb-4">"Website ticket paling sat-set yang pernah gue coba. Gak pake loading lama pas war tiket!"</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center font-bold text-gray-500">SP</div>
                        <div>
                            <h4 class="font-bold text-sm text-gray-900">Siti Putri</h4>
                            <p class="text-xs text-gray-500">Event Enthusiast</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex items-center gap-1 text-yellow-400 mb-3">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    </div>
                    <p class="text-gray-600 italic mb-4">"Udah dua kali beli tiket di sini. Customer service-nya ramah banget pas nanya soal penukaran tiket."</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center font-bold text-gray-500">BD</div>
                        <div>
                            <h4 class="font-bold text-sm text-gray-900">Budi Darmawan</h4>
                            <p class="text-xs text-gray-500">Mahasiswa</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="max-w-4xl mx-auto px-4 mb-24">
        <div class="text-center mb-10">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Pertanyaan Populer</h2>
            <p class="text-gray-500 mt-2">Hal yang sering ditanyakan oleh pengguna Ticketly.</p>
        </div>

        <div class="space-y-4">
            <details class="group bg-white border border-gray-200 rounded-xl overflow-hidden transition-all duration-300 hover:shadow-md open:ring-2 open:ring-blue-100">
                <summary class="flex justify-between items-center font-medium cursor-pointer list-none p-5 text-gray-800 hover:bg-gray-50">
                    <span>Bagaimana cara menerima tiket setelah bayar?</span>
                    <span class="transition group-open:rotate-180">
                        <svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24"><path d="M6 9l6 6 6-6"></path></svg>
                    </span>
                </summary>
                <div class="text-gray-600 p-5 pt-0 text-sm leading-relaxed">
                    Setelah pembayaran berhasil diverifikasi otomatis, E-Tiket akan langsung dikirim ke <strong>Email</strong> dan nomor <strong>WhatsApp</strong> yang Anda daftarkan. Pastikan nomor HP aktif dan email tidak penuh.
                </div>
            </details>
            
            <details class="group bg-white border border-gray-200 rounded-xl overflow-hidden transition-all duration-300 hover:shadow-md open:ring-2 open:ring-blue-100">
                <summary class="flex justify-between items-center font-medium cursor-pointer list-none p-5 text-gray-800 hover:bg-gray-50">
                    <span>Apakah tiket bisa di-refund (dibatalkan)?</span>
                    <span class="transition group-open:rotate-180">
                        <svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24"><path d="M6 9l6 6 6-6"></path></svg>
                    </span>
                </summary>
                <div class="text-gray-600 p-5 pt-0 text-sm leading-relaxed">
                    Secara umum, tiket yang sudah dibeli <strong>tidak dapat dikembalikan</strong> (non-refundable), kecuali jika acara dibatalkan sepihak oleh penyelenggara. Harap baca syarat & ketentuan di halaman detail event sebelum membeli.
                </div>
            </details>

            <details class="group bg-white border border-gray-200 rounded-xl overflow-hidden transition-all duration-300 hover:shadow-md open:ring-2 open:ring-blue-100">
                <summary class="flex justify-between items-center font-medium cursor-pointer list-none p-5 text-gray-800 hover:bg-gray-50">
                    <span>Apakah tiket bisa dipindahtangankan ke orang lain?</span>
                    <span class="transition group-open:rotate-180">
                        <svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24"><path d="M6 9l6 6 6-6"></path></svg>
                    </span>
                </summary>
                <div class="text-gray-600 p-5 pt-0 text-sm leading-relaxed">
                    Ya, tiket umumnya bisa dipindahtangankan (misal diberikan ke teman) asalkan QR Code belum pernah di-scan (check-in) sebelumnya. Namun, untuk event tertentu yang mewajibkan verifikasi KTP (Wajib ID), data pemegang tiket harus sesuai.
                </div>
            </details>

            <details class="group bg-white border border-gray-200 rounded-xl overflow-hidden transition-all duration-300 hover:shadow-md open:ring-2 open:ring-blue-100">
                <summary class="flex justify-between items-center font-medium cursor-pointer list-none p-5 text-gray-800 hover:bg-gray-50">
                    <span>Saya sudah bayar tapi belum terima email tiket?</span>
                    <span class="transition group-open:rotate-180">
                        <svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24"><path d="M6 9l6 6 6-6"></path></svg>
                    </span>
                </summary>
                <div class="text-gray-600 p-5 pt-0 text-sm leading-relaxed">
                    Mohon tunggu 5-10 menit setelah pembayaran. Cek folder <strong>Spam / Junk / Promotions</strong> di email Anda. Jika masih belum ada, silakan hubungi CS kami melalui WhatsApp dengan menyertakan bukti transfer.
                </div>
            </details>

            <details class="group bg-white border border-gray-200 rounded-xl overflow-hidden transition-all duration-300 hover:shadow-md open:ring-2 open:ring-blue-100">
                <summary class="flex justify-between items-center font-medium cursor-pointer list-none p-5 text-gray-800 hover:bg-gray-50">
                    <span>Apakah E-Tiket perlu dicetak (print)?</span>
                    <span class="transition group-open:rotate-180">
                        <svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24"><path d="M6 9l6 6 6-6"></path></svg>
                    </span>
                </summary>
                <div class="text-gray-600 p-5 pt-0 text-sm leading-relaxed">
                    <strong>Tidak perlu.</strong> Anda cukup menunjukkan E-Tiket (QR Code) melalui layar HP Anda kepada petugas di lokasi acara untuk dipindai (scan). Hemat kertas, selamatkan bumi! 🌿
                </div>
            </details>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="max-w-7xl mx-auto px-4 mb-20">
        <div class="bg-gray-900 rounded-2xl p-8 md:p-16 text-center relative overflow-hidden">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full bg-[url('https://www.transparenttextures.com/patterns/stardust.png')] opacity-20"></div>
            
            <div class="relative z-10 max-w-2xl mx-auto">
                <h2 class="text-2xl md:text-4xl font-bold text-white mb-4">Jangan Ketinggalan Info Konser!</h2>
                <p class="text-gray-400 mb-8">Dapatkan update event terbaru, promo tiket early-bird, dan penawaran eksklusif langsung di inbox emailmu.</p>
                
                <form class="flex flex-col sm:flex-row gap-3">
                    <input type="email" placeholder="Masukkan alamat emailmu..." 
                           class="w-full px-5 py-3.5 rounded-lg text-gray-900 focus:ring-2 focus:ring-blue-500 outline-none border-none" required>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-8 rounded-lg transition-all whitespace-nowrap">
                        Berlangganan
                    </button>
                </form>
                <p class="text-xs text-gray-500 mt-4">Kami tidak akan mengirimkan spam. Unsubscribe kapan saja.</p>
            </div>
        </div>
    </section>


    <section class="py-16 bg-white border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-10">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-[0.2em]">Official Partners & Sponsors</h3>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-8 items-center justify-items-center opacity-70 hover:opacity-100 transition-opacity duration-300">
                <div class="w-full flex justify-center grayscale hover:grayscale-0 transition-all duration-300 hover:scale-110 cursor-pointer p-4">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Bank_Central_Asia.svg/2560px-Bank_Central_Asia.svg.png" class="h-8 object-contain" alt="BCA">
                </div>
                <div class="w-full flex justify-center grayscale hover:grayscale-0 transition-all duration-300 hover:scale-110 cursor-pointer p-4">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/86/Gopay_logo.svg/2560px-Gopay_logo.svg.png" class="h-8 object-contain" alt="GoPay">
                </div>
                <div class="w-full flex justify-center grayscale hover:grayscale-0 transition-all duration-300 hover:scale-110 cursor-pointer p-4">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/eb/Logo_ovo_purple.svg/2560px-Logo_ovo_purple.svg.png" class="h-8 object-contain" alt="OVO">
                </div>
                <div class="w-full flex justify-center grayscale hover:grayscale-0 transition-all duration-300 hover:scale-110 cursor-pointer p-4">
                    <img src="<?= base_url('/assets/partner/logo-pnc.png') ?>" class="h-10 object-contain" alt="BNI">
                </div>
                <div class="w-full flex justify-center grayscale hover:grayscale-0 transition-all duration-300 hover:scale-110 cursor-pointer p-4">
                    <img src="<?= base_url('/assets/partner/logo-jkb.png') ?>" class="h-9 object-contain" alt="Laravel">
                </div>
                <div class="w-full flex justify-center grayscale hover:grayscale-0 transition-all duration-300 hover:scale-110 cursor-pointer p-4">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/27/PHP-logo.svg/2560px-PHP-logo.svg.png" class="h-10 object-contain" alt="PHP">
                </div>
            </div>
        </div>
    </section>

    <!-- WHY BUY FROM US SECTION -->
    <section class="bg-blue-secondary-dark py-16 text-white rounded-t-[1rem] md:rounded-t-[3rem] relative overflow-hidden mt-10">

        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Kenapa Beli di Ticketly?</h2>
                <p class="text-blue-200 max-w-2xl mx-auto text-lg">Platform tiket event terpercaya dengan jutaan pengguna di seluruh Indonesia.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">

                <div class="p-8 bg-white/5 backdrop-blur-sm rounded-2xl border border-white/10 hover:bg-white/10 transition-all group hover:-translate-y-2 duration-300">
                    <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Transaksi 100% Aman</h3>
                    <p class="text-blue-100 text-sm leading-relaxed">Pembayaran terjamin aman dengan enkripsi standar bank.</p>
                </div>

                <div class="p-8 bg-white/5 backdrop-blur-sm rounded-2xl border border-white/10 hover:bg-white/10 transition-all group hover:-translate-y-2 duration-300">
                    <div class="w-16 h-16 bg-purple-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg shadow-purple-500/30 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">E-Tiket Instan</h3>
                    <p class="text-blue-100 text-sm leading-relaxed">Tiket langsung dikirim ke email & WhatsApp tanpa menunggu lama.</p>
                </div>

                <div class="p-8 bg-white/5 backdrop-blur-sm rounded-2xl border border-white/10 hover:bg-white/10 transition-all group hover:-translate-y-2 duration-300">
                    <div class="w-16 h-16 bg-pink-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg shadow-pink-500/30 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Bantuan 24/7</h3>
                    <p class="text-blue-100 text-sm leading-relaxed">Tim support kami siap membantumu kapan saja jika ada kendala.</p>
                </div>

            </div>
        </div>
    </section>

</main>