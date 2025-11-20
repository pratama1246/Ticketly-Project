<main class="w-full pt-24 grow">
    <div class="max-w-7xl mx-auto p-4 mb-20">
        <div class="p-6 md:p-10 rounded-lg border border-solid-black">

        <img src="<?= base_url(esc($event['poster_image'])) ?>" alt="<?= esc($event['name']) ?>" class="w-full rounded-lg my-6">

        <div class="mb-6">
            <h1 class="text-3xl lg:text-5xl font-bold text-black">
                <?= esc($event['name']) ?>
            </h1>

            <p class="text-gray-500 text-s font-medium flex items-center gap-1 mt-4">
                <svg class="w-3.5 h-3.5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                </svg>
                <?php 
                    use CodeIgniter\I18n\Time;
                    $time = Time::parse($event['event_date']);
                    echo $time->toLocalizedString('EEEE, d MMMM yyyy'); 
                ?>
            </p>

            <p class="text-gray-500 text-s font-medium flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>
                <?= (new \DateTime($event['event_date']))->format('H:i') ?> WIB
            </p>
            
            <p class="text-gray-500 text-s font-medium flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                <?= esc($event['venue']) ?>
            </p>
            <a href="/event/<?= esc($event['id']) ?>" class="mt-4 inline-block hover:underline">Kembali ke Detail Event</a>
        </div>
        
        <form action="/checkout/start" method="post" id="ticketForm">
            <?= csrf_field() ?> 
            <input type="hidden" name="eventId" value="<?= $event['id'] ?>">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                <div class="lg:col-span-2 space-y-6">
                    
                    <?php if (empty($ticket_types)): ?>
                        <div class="bg-white p-8 rounded-xl text-center shadow-sm border border-dashed border-gray-300">
                            <p class="text-gray-500">Tiket belum tersedia.</p>
                        </div>
                    <?php else: ?>
                        
                        <?php foreach ($ticket_types as $ticket): ?>
                            <?php
                                $isSoldOut = ($ticket['quantity_total'] - $ticket['quantity_sold']) <= 0;
                                
                                // -----------------------------------------------------
                                // LOGIKA WARNA CUSTOM (HEX)
                                // -----------------------------------------------------
                                $baseColor = !empty($ticket['ui_color']) ? $ticket['ui_color'] : '#3B82F6';
                                
                                // Warna Background Header (Pudar 20%)
                                $headerBg = $isSoldOut ? '#f3f4f6' : $baseColor ; 
                            ?>

                           <div class="bg-white rounded-lg overflow-hidden shadow-sm border border-gray-200 transition-all duration-300 hover:shadow-lg group">
                                
                                <div class="p-4 text-center relative" style="background-color: <?= $headerBg ?>;">
                                    
                                    <h3 class="text-xl font-bold tracking-wide uppercase text-gray-900">
                                        <?= esc($ticket['name']) ?>
                                    </h3>
                                    
                                    <?php if(!empty($ticket['ticket_category'])): ?>
                                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-bold px-2 py-0.5 rounded border bg-white text-gray-900 border-gray-300 shadow-sm">
                                        <?= esc($ticket['ticket_category']) ?>
                                    </span>
                                    <?php endif; ?>
                                </div>

                                <div class="p-6">
                                    <div class="mb-4 text-sm text-gray-600">
                                        <?php if (!empty($ticket['description'])): ?>
                                            <div class="prose prose-sm max-w-none [&_ul]:list-disc [&_ul]:ml-5 [&_ol]:list-decimal [&_ol]:ml-5 text-gray-700">
                                                <?= $ticket['description'] ?>
                                            </div>
                                        <?php else: ?>
                                            <ul class="list-disc list-inside space-y-1 text-gray-700">
                                                <li>Tempat duduk di area <b><?= strtolower(esc($ticket['name'])) ?></b></li>
                                                <li>Harga sudah termasuk pajak</li>
                                            </ul>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <hr class="my-4 border-gray-100">

                                    <div class="flex justify-between items-center">
                                        <p class="text-2xl font-bold <?= $isSoldOut ? 'text-gray-400' : 'text-gray-900' ?>">
                                            Rp <?= number_format($ticket['price'], 0, ',', '.') ?>
                                        </p>
                                        
                                        <div class="w-32 text-right">
                                            <?php if ($isSoldOut): ?>
                                                <span class="inline-block w-full py-2 bg-gray-100 text-gray-400 font-bold text-sm rounded border border-gray-200 text-center cursor-not-allowed">HABIS</span>
                                            <?php else: ?>
                                                <label for="ticket_<?= $ticket['id'] ?>" class="block text-xs font-medium text-gray-500 mb-1 text-right">Jumlah</label>
                                                <input type="number" 
                                                       name="quantity[<?= $ticket['id'] ?>]" 
                                                       id="ticket_<?= $ticket['id'] ?>"
                                                       class="ticket-input w-full border-gray-300 rounded-md shadow-sm text-center focus:ring-blue-500 focus:border-blue-500 font-bold text-gray-900"
                                                       value="0" 
                                                       min="0" 
                                                       max="4"
                                                       data-name="<?= esc($ticket['name']) ?>"
                                                       data-price="<?= $ticket['price'] ?>"> 
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        <?php endforeach; ?>
                        <?php endif; ?>
                    
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mt-6">
                    <h3 class="font-bold text-gray-900 mb-3 flex items-center gap-2 text-sm uppercase tracking-wide">
                        <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Ketentuan Umum
                        </h3>
                    <ul class="list-disc list-inside text-xs text-gray-500 space-y-2 pl-1">
                        <li>Tiket digital akan dikirim ke email setelah pembayaran.</li>
                        <li>Pastikan data diri sesuai dengan kartu identitas.</li>
                        <li>Tiket tidak dapat ditukar atau dikembalikan (non-refundable).</li>
                    </ul>
                </div>
            </div>
                
            <div class="lg:col-span-1">
                    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100 sticky top-24 z-20">
                        <h3 class="font-bold text-gray-900 text-lg mb-4 border-b border-gray-100 pb-3 flex items-center justify-between">
                            Ringkasan Pesanan
                        </h3>
                        
                        <div id="cartItems" class="space-y-3 text-sm text-gray-600 mb-6 min-h-[60px]">
                            <div class="flex flex-col items-center justify-center h-full py-4 text-gray-400 bg-gray-50 rounded-lg border border-dashed border-gray-200">
                                <p class="text-xs">Belum ada tiket dipilih</p>
                            </div>
                        </div>

                        <div class="border-t border-gray-100 pt-4 bg-gray-50 -mx-6 px-6 -mb-6 rounded-b-xl pb-6">
                            <div class="flex justify-between items-end mb-4">
                                <span class="text-gray-600 font-medium text-sm">Total Estimasi</span>
                                <span class="text-2xl font-bold text-blue-600" id="totalPrice">Rp 0</span>
                            </div>
                            
                            <button type="submit" id="btnCheckout" disabled class="w-full bg-gray-300 text-gray-500 font-bold py-3 px-4 rounded-lg cursor-not-allowed transition-all shadow-sm hover:shadow text-center flex justify-center items-center gap-2">
                                Pesan Sekarang
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
</main>