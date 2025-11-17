<!-- Halaman Detail Event -->
<main class="w-full pt-24 flex-grow">
    <div class="max-w-7xl mx-auto p-4">
        <div class="p-6 md:p-10 rounded-lg border border-solid-black">
        
        <!-- Tombol Beli Sekarang -->
        <div class="mt-8 text-center">
                <a href="/event/<?= $event['id'] ?>/select" class="w-full md:w-auto inline-block bg-blue-600 text-white font-bold text-lg py-3 px-10 rounded-lg hover:bg-blue-700 transition duration-300">
                    Beli Tiket Sekarang
                </a>
            </div>

        <!-- Nama Event -->
            <div class="mt-10">
            <h1 class="text-3xl font-bold text-black">
                <?= esc($event['name']) ?>
            </h1>

        <!-- Tanggal Event -->
            <p class="text-lg text-gray-600 mt-2">
                <?php 
                    $date = new \DateTime(esc($event['event_date']));
                    echo $date->format('d F Y'); 
                ?>
            </p>

        <!-- Status Event -->
            <span class="bg-green-500 text-white text-xs font-medium px-2.5 py-1 rounded mt-4 inline-block">
                Sedang Berlangsung
            </span>

        <!-- Gambar Poster Event -->
            <img src="<?= base_url('assets/' . esc($event['poster_image'])) ?>" alt="<?= esc($event['name']) ?>" class="w-full rounded-lg my-6">

        <!-- Seat Map Event -->
            <div class="bg-blue-900 p-6 rounded-lg">
                <h2 class="text-2xl font-bold text-white mb-4 text-center">
                    SEAT MAP <?= esc($event['name']) ?>
                </h2>
                <img src="<?= base_url('assets/' . esc($event['seatmap_image'])) ?>" alt="Seat Map" class="w-full rounded-lg my-6">
            </div>

        <!-- Deskripsi Event -->
            <div class="mt-10">
                <div class="prose dark:prose-invert max-w-none text-black">
                    <?= $event['description'] ?>
                </div>
            </div>

        </div>
    </div>
</main>