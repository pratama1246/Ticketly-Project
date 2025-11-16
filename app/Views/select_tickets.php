<main class="w-full pt-24">

    <div class="max-w-4xl mx-auto p-4">

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
        </div>

        <form action="/checkout/start" method="post">
            <?= csrf_field() ?> <input type="hidden" name="eventId" value="<?= $event['id'] ?>">

            <div class="space-y-4">

                <?php if (empty($ticket_types)): ?>
                    <p class="text-center text-gray-500">Tiket untuk event ini belum tersedia.</p>
                <?php else: ?>
                    <?php foreach ($ticket_types as $ticket): ?>
                        <div class="flex justify-between items-center p-6 border border-gray-300 rounded-lg shadow-sm bg-white">
                            <div>
                                <h3 class="text-xl font-semibold text-black"><?= esc($ticket['name']) ?></h3>
                                <p class="text-lg text-blue-600 font-bold mt-1">
                                    Rp <?= number_format($ticket['price'], 0, ',', '.') ?>
                                </p>
                                <p class="text-sm text-gray-500 mt-1">
                                    Stok tersedia: <?= esc($ticket['quantity_total'] - $ticket['quantity_sold']) ?>
                                </p>
                            </div>
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

            <div class="mt-8 text-right">
        <button type="submit" class="bg-blue-600 text-white font-bold text-lg py-3 px-10 rounded-lg hover:bg-blue-700 transition duration-300">
            Lanjut ke Pembayaran
        </button>
    </div>
</form>
</main>