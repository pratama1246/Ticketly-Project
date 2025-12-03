<div class="flex justify-between items-center mb-6">

<!-- Kembali Ke Manajemen Event -->
<div class="flex items-center gap-4">
        <a href="/admin/events" class="text-gray-500 hover:text-gray-700 flex items-center gap-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <h1 class="text-2xl font-bold text-black m-0">Manajemen Event</h1>
        </a>
    </div>
    
    <!-- Tombol: Hapus, Edit, Kelola Tiket -->
    <div class="flex gap-3">
        <button onclick="deleteEvent(<?= $event['id'] ?>)" class="text-white bg-danger box-border border border-transparent hover:bg-danger-strong focus:ring-4 focus:ring-danger-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
            Hapus Event
        </button>
        <a href="/admin/events/edit/<?= $event['id'] ?>" class="text-white bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
            Edit Event
        </a>
        <a href="/admin/events/<?= $event['id'] ?>/tickets" class="text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
            Kelola Tiket
        </a>
    </div>
</div>

<!-- Event Details -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="space-y-6">
        <!-- Poster Event -->
        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
            <h3 class="font-bold text-gray-700 mb-3">Poster Event</h3>
            <img src="<?= base_url($event['poster_image']) ?>" alt="Poster" class="w-full rounded-lg object-cover">
        </div>

        <!-- Seatmap Event -->
        <?php if($event['seatmap_image']): ?>
        <div class="bg-gray-900 p-4 rounded-lg shadow-sm border border-gray-200">
            <h3 class="font-bold text-white mb-3">Seat Map</h3>
            <img src="<?= base_url($event['seatmap_image']) ?>" alt="Seatmap" class="w-full rounded-lg object-cover">
        </div>
        <?php endif; ?>
    </div>

    <div class="lg:col-span-2 space-y-6">

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">

            <!-- Status Event -->
            <div class="flex justify-between items-start mb-4">
                <h2 class="text-2xl font-bold text-gray-900"><?= esc($event['name']) ?></h2>
                <span class="<?= $event['status'] === 'published' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?> text-xs font-medium px-2.5 py-0.5 rounded border <?= $event['status'] === 'published' ? 'border-green-400' : 'border-gray-500' ?>">
                    <?= ucfirst($event['status']) ?>
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-gray-600 mb-6">

                <!-- Info Lokasi -->
                <div>
                    <p class="mb-1 text-gray-500">Lokasi</p>
                    <p class="font-medium text-gray-900 text-base flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <?= esc($event['venue']) ?>
                    </p>
                </div>

                <!-- Waktu Pelaksanaan -->
                <div>
                    <p class="mb-1 text-gray-500">Waktu Pelaksanaan</p>
                    <div class="font-medium text-gray-900 text-base">
                        <?php 
                            $start = \CodeIgniter\I18n\Time::parse($event['event_date']);
                            echo $start->toLocalizedString('d MMMM yyyy, HH:mm') . ' WIB';
                        ?>
                        <?php if(!empty($event['event_end_date'])): ?>
                            <br>
                            <span class="text-gray-500 text-xs">Sampai:</span>
                            <?= \CodeIgniter\I18n\Time::parse($event['event_end_date'])->toLocalizedString('d MMMM yyyy') ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Kategori Event -->
                <div>
                    <p class="mb-1 text-gray-500">Kategori</p>
                    <p class="font-medium text-gray-900 capitalize"><?= esc($event['category']) ?></p>
                </div>

                <!-- Tampilkan di Carousel Utama (Featured) -->
                <div>
                    <p class="mb-1 text-gray-500">Featured</p>
                    <p class="font-medium text-gray-900"><?= $event['is_featured'] ? 'Ya (Tampil di Carousel)' : 'Tidak' ?></p>
                </div>
            </div>

            <!-- Deskripsi Event -->
            <div>
                <p class="mb-2 text-gray-500">Deskripsi</p>
                <div class="prose prose-sm max-w-none text-gray-700 bg-gray-50 p-4 rounded-lg border border-gray-100">
                    <?= $event['description'] ?>
                </div>
            </div>
        </div>
        
        <!-- Daftar Tiket Event -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-gray-900 text-lg">Daftar Tiket</h3>
                <a href="/admin/events/<?= $event['id'] ?>/tickets" class="text-sm text-blue-600 hover:underline">Kelola Tiket &rarr;</a>
            </div>

            <?php if(empty($tickets)): ?>
                <p class="text-gray-500 text-sm italic">Belum ada tiket yang dibuat untuk event ini.</p>
            <?php else: ?>
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th class="px-4 py-2">Nama Tiket</th>
                                <th class="px-4 py-2">Harga</th>
                                <th class="px-4 py-2">Terjual</th>
                                <th class="px-4 py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($tickets as $t): ?>
                            <tr class="border-b">
                                <td class="px-4 py-2 font-medium text-gray-900">
                                    <span class="w-3 h-3 inline-block rounded-full mr-2" style="background-color: <?= $t['ui_color'] ?>"></span>
                                    <?= esc($t['name']) ?>
                                </td>
                                <td class="px-4 py-2">Rp <?= number_format($t['price'], 0, ',', '.') ?></td>
                                <td class="px-4 py-2">
                                    <?= $t['quantity_sold'] ?> / <?= $t['quantity_total'] ?>
                                </td>
                                <td class="px-4 py-2">
                                    <?php if($t['quantity_sold'] >= $t['quantity_total']): ?>
                                        <span class="text-red-600 font-bold text-xs">Sold Out</span>
                                    <?php else: ?>
                                        <span class="text-green-600 font-bold text-xs">Tersedia</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
        
    </div>
</div>