<!-- Halaman Detail Event -->
<main class="w-full pt-24 grow">
    <div class="max-w-7xl mx-auto p-4">
        <div class="p-6 md:p-10 rounded-lg border border-solid-black">

        <!-- Nama Event -->
            <div class="mt-10">
                <h1 class="text-3xl lg:text-5xl font-bold text-black">
                    <?= esc($event['name']) ?>
                </h1>

        <!-- Tanggal Event -->
            <p class="text-gray-500 text-s font-medium flex items-center gap-1 mt-4">
                <svg class="w-3.5 h-3.5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                </svg>
                    <?php 
                        use CodeIgniter\I18n\Time; // Import class Time
                        $time = Time::parse($event['event_date']);
                        echo $time->toLocalizedString('EEEE, d MMMM yyyy'); 
                    ?>
            </p>

        <!-- Waktu Event: -->
            <p class="text-gray-500 text-s font-medium flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>
                    <?= (new \DateTime($event['event_date']))->format('H:i') ?> WIB
            </p>
        
        <!-- Lokasi Event -->
            <p class="text-gray-500 text-s font-medium flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                    <?= esc($event['venue']) ?>
            </p>

        <!-- Status Event -->
            <span class="bg-green-500 text-white text-xs font-medium px-2.5 py-1 rounded mt-4 inline-block">
                Sedang Berlangsung
            </span>

        <!-- Gambar Poster Event -->
            <img src="<?= base_url(esc($event['poster_image'])) ?>" alt="<?= esc($event['name']) ?>" class="w-full rounded-lg my-6">

        <!-- Seat Map Event -->
            <div class="bg-blue-900 p-6 rounded-lg">
                <h2 class="text-2xl font-bold text-white mb-4 text-center">
                    SEAT MAP <?= esc($event['name']) ?>
                </h2>
                <img src="<?= base_url(esc($event['seatmap_image'])) ?>" alt="<?= esc($event['name']) ?>" class="w-full rounded-lg my-6">
            </div>

        <!-- Deskripsi Event -->
            <div class="prose max-w-none mt-10">
                    <?= $event['description'] ?>
                </div>
        
        <!-- Tombol Beli Sekarang -->
            <div class="mt-8 text-center">
                <a href="/event/<?= $event['id'] ?>/select" class="w-full md:w-auto inline-block bg-blue-600 text-white font-bold text-lg py-3 px-10 rounded-lg hover:bg-blue-700 transition duration-300">
                    Beli Tiket Sekarang
                </a>
            </div>
        </div>
    </div>
</main>