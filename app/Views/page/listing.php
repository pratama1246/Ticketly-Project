<main class="w-full pt-16 grow bg-yellow-bright-light min-h-screen">

    <div class="max-w-7xl mx-auto px-4 py-8 md:py-12">
        <h1 class="text-3xl md:text-5xl font-bold text-gray-900 mb-4 mt-6"><?= esc($title) ?></h1>
        <p class="text-md md:text-lg text-gray-600 max-w-2xl"><?= esc($desc) ?></p>
    </div>

    <div class="max-w-7xl mx-auto px-4 pb-20">
        <?php if (empty($events)): ?>
            <div class="flex flex-col items-center justify-center py-20 text-center bg-white rounded-2xl shadow-sm border border-gray-100">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                
                <?php if (!empty($keyword)): ?>
                    <h3 class="text-xl font-bold text-gray-900">
                        Oops! Tidak ditemukan hasil untuk "<?= esc($keyword) ?>"
                    </h3>
                    <p class="text-gray-500 mt-2 max-w-md mx-auto">
                        Mungkin coba kata kunci lain, atau periksa ejaanmu.
                    </p>
                    <a href="/events" class="mt-6 inline-block px-6 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-full hover:bg-blue-100 transition-colors">
                        Lihat Semua Event
                    </a>
                <?php else: ?>
                    <h3 class="text-xl font-bold text-gray-900">Belum ada event saat ini</h3>
                    <p class="text-gray-500 mt-2">
                        Nantikan update event seru lainnya di sini.
                    </p>
                <?php endif; ?>
            </div>

        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                 <?php foreach ($events as $event): ?>
                        <div class="snap-center shrink-0 w-80 md:w-104 bg-white flex flex-col rounded-xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-2 group">
                            
                            <a href="/event/<?= $event['slug'] ?>" class="block relative aspect-video overflow-hidden">
                                <img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" 
                                     src="<?= base_url($event['poster_image']) ?>" 
                                     alt="<?= esc($event['name']) ?>" />
                                
                                <div class="absolute inset-0 bg-linear-to-t from-black/70 via-transparent to-transparent opacity-60 group-hover:opacity-80 transition-opacity duration-300"></div>
                                
                                <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-md px-2.5 py-1 rounded-md text-xs font-bold text-gray-900 shadow-sm z-10">
                                    <div class="text-center leading-tight">
                                        <?php 
                                            $start = \CodeIgniter\I18n\Time::parse($event['event_date']);
                                            $month = $start->format('M'); // Nama Bulan (JAN, FEB)
                                            $dateDisplay = $start->format('d'); // Tanggal (01, 15)

                                            // Logika Cek Range Tanggal
                                            if (!empty($event['event_end_date'])) {
                                                $end = \CodeIgniter\I18n\Time::parse($event['event_end_date']);
                                                
                                                // Jika hari berbeda...
                                                if ($start->format('Y-m-d') !== $end->format('Y-m-d')) {
                                                    // ...dan masih di bulan yang sama, tampilkan "10-12"
                                                    if ($start->getMonth() === $end->getMonth()) {
                                                        $dateDisplay .= '-' . $end->format('d');
                                                    }
                                                    // Jika beda bulan, tetap tampilkan tgl mulai saja biar ga berantakan di kotak kecil
                                                }
                                            }
                                        ?>
                                        <span class="block text-red-600 uppercase text-2xs"><?= $month ?></span>
                                        <span class="block text-lg tracking-tighter"><?= $dateDisplay ?></span>
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
                                        <<?php
                                            $startT = new \DateTime($event['event_date']);
                                            $timeStr = $startT->format('H:i');
                                            
                                            // Cek End Date
                                            if (!empty($event['event_end_date'])) {
                                                $endT = new \DateTime($event['event_end_date']);
                                                // Kalau hari sama, tampilin range jam
                                                if ($startT->format('Y-m-d') === $endT->format('Y-m-d')) {
                                                    $timeStr .= ' - ' . $endT->format('H:i');
                                                }
                                            }
                                            echo $timeStr . ' WIB';
                                        ?>
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
        <?php endif; ?>
    </div>
</main>