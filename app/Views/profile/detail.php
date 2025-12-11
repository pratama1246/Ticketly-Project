<main class="w-full pt-16 grow bg-yellow-accent-light min-h-screen overflow-x-auto">
    <div class="max-w-7xl mx-auto px-4 py-8 md:py-12">
        
        <div class="flex items-center justify-between mb-6">
            <div>
                <a href="/profile/history" class="bg-yellow-accent-normal hover:bg-yellow-accent-normal-hover text-gray-700 hover:text-gray-900 flex items-center gap-3 w-max px-3 py-2 mt-4 mb-4 rounded-base">
                    <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <h1 class="text-xs md:text-sm font-bold text-gray-900 m-0">Kembali</h1>
                </a>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Detail Pesanan</h1>
                <p class="text-gray-500 text-sm">ID Transaksi: #<?= esc($order['trx_id']) ?></p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <div class="lg:col-span-2">
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h2 class="font-bold text-gray-900">Daftar Tiket</h2>
                    </div>
                    
                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 overflow-x-auto min-w-[700px]">
                            <thead class="text-xs text-gray-700 uppercase bg-white border-b">
                                <tr>
                                    <th class="px-6 py-3">Event</th>
                                    <th class="px-6 py-3">Tipe</th>
                                    <th class="px-6 py-3">Kursi</th>
                                    <th class="px-6 py-3 text-right">Harga</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php foreach($items as $item): 
                                    $seat = $item['label'] ? $item['label'] : ($item['seat_row'] ? $item['seat_row'].'-'.$item['seat_number'] : '-');
                                ?>
                                <tr class="bg-white hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        <?= esc($item['event_name']) ?>
                                        <br>
                                        <span class="text-xs text-gray-400 font-mono"><?= esc($item['ticket_code']) ?></span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded border border-blue-200">
                                            <?= esc($item['ticket_name']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-gray-900">
                                        <?= esc($seat) ?>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        Rp <?= number_format($item['price_per_ticket'], 0, ',', '.') ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="bg-gray-50 border-t border-gray-200">
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-right font-bold text-gray-900">Total Pembayaran</td>
                                    <td class="px-6 py-4 text-right font-bold text-blue-700 text-lg">
                                        Rp <?= number_format($order['order_total'], 0, ',', '.') ?>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 mb-6">
                    <h3 class="font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">Status Pesanan</h3>
                    
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm text-gray-500">Status Saat Ini</span>
                        <?php if($order['status'] == 'completed'): ?>
                            <span class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1 rounded-full border border-green-200">Lunas</span>
                        <?php elseif($order['status'] == 'pending'): ?>
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-3 py-1 rounded-full border border-yellow-200">Menunggu Pembayaran</span>
                        <?php else: ?>
                            <span class="bg-red-100 text-red-800 text-xs font-medium px-3 py-1 rounded-full border border-red-200"><?= ucfirst($order['status']) ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Tanggal Order</span>
                            <span class="font-medium text-gray-900"><?= \CodeIgniter\I18n\Time::parse($order['created_at'])->toLocalizedString('d MMM yyyy') ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Metode Bayar</span>
                            <span class="font-medium text-gray-900 uppercase"><?= esc($order['payment_method']) ?></span>
                        </div>
                    </div>

                    <?php if($order['status'] == 'pending'): ?>
                        <div class="mt-6 pt-4 border-t border-gray-100">
                            <a href="/checkout/pay/<?= $order['id'] ?>" class="block w-full text-center text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">
                                Lanjut Pembayaran
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                    <h4 class="text-sm font-bold text-blue-900 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Butuh Bantuan?
                    </h4>
                    <p class="text-xs text-blue-700 leading-relaxed">
                        Jika kamu mengalami kendala dengan pesanan ini, silakan hubungi layanan pelanggan kami dengan menyertakan Order ID <strong>#<?= esc($order['trx_id']) ?></strong>.
                    </p>
                </div>
            </div>

        </div>
    </div>
</main>