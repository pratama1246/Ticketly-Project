<main class="w-full pt-24">
    <div class="max-w-4xl mx-auto p-4">
        <div class="p-6 md:p-10 rounded-lg border border-solid-black">

        <!-- Judul Pilih Tiket dan Tanggal Event -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-black">
                Pilih Tiket - <?= esc($event['name']) ?>
            </h1>
            <p class="text-lg text-gray-600 mt-2">
                <?php 
                    $date = new \DateTime(esc($event['event_date']));
                    echo $date->format('d F Y'); 
                ?>
            </p>
            <a href="/event/<?= esc($event['id']) ?>" class="mt-4 inline-block hover:underline">Kembali ke Detail Event</a>
        </div>

        <!-- Form Pilih Tiket -->
        <form action="/checkout/start" method="post">
            <?= csrf_field() ?> <input type="hidden" name="eventId" value="<?= $event['id'] ?>">
            <div class="space-y-4">

                <!-- Logika Untuk Menampilkan Jenis Tiket -->
                <?php if (empty($ticket_types)): ?>
                    <p class="text-center text-gray-500">Tiket untuk event ini belum tersedia.</p>
                <?php else: ?>
                    <?php foreach ($ticket_types as $ticket): ?>

                        <!-- Template untuk menampilkan setiap jenis tiket -->
                        <div class="flex justify-between items-center p-6 rounded-sm border border-solid-black bg-yellow-accent-light shadow-sm animated-border-card"> <span class="line line-top"></span>
                            <span class="line line-right"></span>
                            <span class="line line-bottom"></span>
                            <span class="line line-left"></span>
                            <div>
                                <h3 class="text-xl font-semibold text-black"><?= esc($ticket['name']) ?></h3>
                                <p class="text-lg text-black font-bold mt-1">
                                    Rp <?= number_format($ticket['price'], 0, ',', '.') ?>
                                </p>
                                <p class="text-sm text-gray-500 mt-1">
                                    Stok tersedia: <?= esc($ticket['quantity_total'] - $ticket['quantity_sold']) ?>
                                </p>
                            </div>

                            <!-- Input Jumlah Tiket -->
                            <div class="w-32">
                                <label for="ticket_<?= $ticket['id'] ?>" class="block text-sm font-medium text-gray-700 mb-1 text-right">Jumlah</label>
                                <input type="number" 
                                       name="quantity[<?= $ticket['id'] ?>]" 
                                       id="ticket_<?= $ticket['id'] ?>"
                                       class="w-full border-gray-300 rounded-md shadow-sm text-center"
                                       value="0" 
                                       min="0" 
                                       max="4"> </div>
                            </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                </div>
            
            <!-- Tombol Lanjut ke Pembayaran -->
            <div class="mt-8 text-right">
            <button type="submit" class="text-white bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
            Lanjut ke Pembayaran
        </button>
    </div>
</form>
        </div>
    </div>
</main>