<main class="w-full pt-24 grow">
    <div class="max-w-4xl mx-auto p-4">
        <div class="p-6 md:p-10 rounded-lg border border-solid-black">

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

        <form action="/checkout/start" method="post">
            <?= csrf_field() ?> <input type="hidden" name="eventId" value="<?= $event['id'] ?>">
            <div class="space-y-4">

                <?php if (empty($ticket_types)): ?>
                    <p class="text-center text-gray-500">Tiket untuk event ini belum tersedia.</p>
                <?php else: ?>
                    
                    <?php
                        // Definisikan palet warna (sesuai mockup)
                        $colorSchemes = [
                            ['bg' => 'bg-green-300', 'text' => 'text-green-900', 'price_color' => 'text-green-700'],
                            ['bg' => 'bg-blue-300', 'text' => 'text-blue-900', 'price_color' => 'text-blue-700'],
                            ['bg' => 'bg-yellow-300', 'text' => 'text-yellow-900', 'price_color' => 'text-yellow-700'],
                            ['bg' => 'bg-purple-300', 'text' => 'text-purple-900', 'price_color' => 'text-purple-700'],
                            ['bg' => 'bg-red-300', 'text' => 'text-red-900', 'price_color' => 'text-red-700'],
                        ];
                        $colorIndex = 0;
                    ?>

                    <?php foreach ($ticket_types as $ticket): ?>
                        
                        <?php
                            // Ambil skema warna untuk card ini
                            $scheme = $colorSchemes[$colorIndex % count($colorSchemes)];
                            $colorIndex++;
                            
                            $isSoldOut = ($ticket['quantity_total'] - $ticket['quantity_sold']) <= 0;
                        ?>

                        <div class="bg-white rounded-lg overflow-hidden shadow-sm border border-gray-300 
                                    transition-all duration-300 hover:scale-[1.02] hover:shadow-xl">
                            
                            <div class="p-3 text-center <?= $isSoldOut ? 'bg-gray-300' : $scheme['bg'] ?> <?= $isSoldOut ? 'text-gray-600' : $scheme['text'] ?>">
                                <h3 class="text-xl font-semibold">
                                    <?= esc($ticket['name']) ?>
                                </h3>
                            </div>

                            <div class="p-6">
                                <ul class="list-disc list-inside text-gray-700 mb-4">
                                    <li>Tempat duduk di area <?= strtolower(esc($ticket['name'])) ?></li>
                                </ul>
                                
                                <p class="text-sm text-blue-600 mb-4 flex items-center">
                                    <svg class="w-4 h-4 me-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm.13 14.242a.908.908 0 0 1-1.313.046l-3.036-2.923a.909.909 0 0 1 1.286-1.287l2.378 2.296 5.068-5.067a.909.909 0 0 1 1.286 1.287l-5.716 5.716a.89.89 0 0 1-.04.033Z"/></svg>
                                    Penjualan berakhir 3 Juli 2025 (19:59 WIB)
                                </p>

                                <hr class="my-4">

                                <div class="flex justify-between items-center">
                                    <p class="text-2xl font-bold <?= $isSoldOut ? 'text-gray-500' : $scheme['price_color'] ?>">
                                        Rp <?= number_format($ticket['price'], 0, ',', '.') ?>
                                    </p>
                                    
                                    <div class="w-32 text-right">
                                        <?php if ($isSoldOut): ?>
                                            <span class="inline-block px-6 py-2.5 bg-gray-400 text-white font-medium text-sm rounded-lg cursor-not-allowed">
                                                Habis
                                            </span>
                                        <?php else: ?>
                                            <label for="ticket_<?= $ticket['id'] ?>" class="block text-sm font-medium text-gray-700 mb-1 text-right">Jumlah</label>
                                            <input type="number" 
                                                   name="quantity[<?= $ticket['id'] ?>]" 
                                                   id="ticket_<?= $ticket['id'] ?>"
                                                   class="w-full border-gray-300 rounded-md shadow-sm text-center"
                                                   value="0" 
                                                   min="0" 
                                                   max="4">
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div> <?php endforeach; ?>
                <?php endif; ?>
                </div>
            
            <div class="mt-8 text-right">
                <button type="submit" class="text-white bg-brand box-border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                    Lanjut ke Pembayaran
                </button>
            </div>
        </form>
        </div>
    </div>
</main>