<!-- Halaman Detail Event -->
<main class="w-full pt-24 mb-20 grow">
    <div class="max-w-7xl mx-auto p-4">

        <a href="/" class="bg-yellow-accent-normal hover:bg-yellow-accent-normal-hover text-gray-700 hover:text-gray-900 flex items-center gap-3 w-max px-3 py-2 rounded-base">
            <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <h1 class="text-xs md:text-sm font-bold text-gray-900 m-0">Kembali Ke Halaman Utama</h1>
        </a>

        <!-- Nama Event -->
            <div class="mt-6">
                <h1 class="text-3xl lg:text-5xl font-bold text-black">
                    <?= esc($event['name']) ?>
                </h1>

        <!-- Tanggal Event -->
            <p class="text-gray-500 text-s font-medium flex items-center gap-1 mt-4">
                <svg class="w-3.5 h-3.5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                </svg>
                    <?php 
                    $start = \CodeIgniter\I18n\Time::parse($event['event_date']);
                    
                    if (!empty($event['event_end_date'])) {
                        $end = \CodeIgniter\I18n\Time::parse($event['event_end_date']);
                        
                        // Logika Tampilan Tanggal
                        if ($start->format('Y-m-d') === $end->format('Y-m-d')) {
                            // Satu Hari: "10 Januari 2026"
                            echo $start->toLocalizedString('d MMMM yyyy');
                        } else {
                            // Multi-Day: "10 - 12 Januari 2026" atau "30 Des - 02 Jan 2026"
                            if ($start->getMonth() == $end->getMonth() && $start->getYear() == $end->getYear()) {
                                echo $start->format('d') . ' - ' . $end->toLocalizedString('d MMMM yyyy');
                            } else {
                                echo $start->toLocalizedString('d MMM') . ' - ' . $end->toLocalizedString('d MMM yyyy');
                            }
                        }
                    } else {
                        // Default Single Day
                        echo $start->toLocalizedString('d MMMM yyyy');
                    }
                ?>
            </p>

        <!-- Waktu Event: -->
            <p class="text-gray-500 text-s font-medium flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>
                    <?php 
                    $startTime = $start->format('H:i');
                    
                    if (!empty($event['event_end_date'])) {
                        $endTime = \CodeIgniter\I18n\Time::parse($event['event_end_date'])->format('H:i');
                        
                        // Cek apakah tanggalnya sama? Kalau beda hari, mungkin cuma nampilin jam mulai (Open Gate)
                        // Tapi kalau sama hari, tampilin range jam (15:00 - 23:00)
                        $isSameDay = ($start->format('Y-m-d') === \CodeIgniter\I18n\Time::parse($event['event_end_date'])->format('Y-m-d'));

                        if ($isSameDay) {
                            echo $startTime . ' - ' . $endTime . ' WIB';
                        } else {
                            echo 'Mulai pukul ' . $startTime . ' WIB';
                        }
                    } else {
                        echo $startTime . ' WIB';
                    }
                ?>
            </p>
        
        <!-- Lokasi Event -->
            <p class="text-gray-500 text-s font-medium flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                    <?= esc($event['venue']) ?>
            </p>

        <!-- Status Event -->
            <span class="<?= $status['color'] ?> text-xs font-bold px-3 py-1.5 rounded-full mt-4 inline-flex items-center shadow-sm">
                <?= $status['icon'] ?>
                <?= $status['text'] ?>
            </span>

        <!-- Gambar Poster Event -->
            <div class="w-full rounded-xl overflow-hidden mb-8 mt-8 flex justify-center items-center">
                <img src="<?= base_url(esc($event['poster_image'])) ?>" 
                     alt="<?= esc($event['name']) ?>" 
                     class="w-auto max-w-full h-auto max-h-[600px] md:max-h-[700px] object-contain shadow-sm rounded-lg">
            </div>

        <!-- Seat Map Event -->
            <?php if (!empty($event['seatmap_image'])): ?>
                <div class="bg-gray-900 p-6 rounded-xl mt-10 shadow-lg border border-gray-800">
                    <h2 class="text-xl md:text-2xl font-bold text-white mb-6 text-center flex items-center justify-center gap-2">SEAT MAP <?= esc($event['name']) ?>
                    </h2>
                    
                    <div class="flex justify-center">
                        <img src="<?= base_url(esc($event['seatmap_image'])) ?>" 
                             alt="Seat Map <?= esc($event['name']) ?>" 
                             class="w-full md:w-3/4 h-auto object-contain rounded bg-transparent">
                    </div>
                </div>
            <?php endif; ?>

        <!-- Deskripsi Event -->
            <div class="prose max-w-none mt-10 text-2sm">
                    <?= $event['description'] ?>
                </div>
        
        <!-- Tombol Beli Sekarang -->
            <div class="mt-8 text-center">
                <?php if ($status['purchasable']): ?>
                    <a href="/event/<?= $event['slug'] ?>/select" class="w-full md:w-auto inline-block bg-blue-600 text-white font-bold text-lg py-3 px-10 rounded-lg hover:bg-blue-700 transition duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        Beli Tiket Sekarang
                    </a>
                <?php else: ?>
                    <button disabled class="w-full md:w-auto inline-block bg-gray-300 text-gray-500 font-bold text-lg py-3 px-10 rounded-lg cursor-not-allowed shadow-none">
                        <?= $status['text'] === 'Telah Berakhir' ? 'Event Telah Berakhir' : 'Tiket Habis Terjual' ?>
                    </button>
                <?php endif; ?>
            </div>
        </div>
</main>