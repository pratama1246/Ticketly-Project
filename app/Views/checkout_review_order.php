<main class="w-full pt-60 md:pt-42 mb-20 grow transition-all duration-300">
    <div class="max-w-4xl mx-auto p-4">
        <h2 class="text-2xl font-bold text-black mb-4">Konfirmasi Pesanan</h2>
        
        <form action="/checkout/create_order" method="POST">
            <?= csrf_field() ?>
            
            <div class="bg-white border border-gray-300 rounded-lg shadow-sm p-6">
                
                <div class="pb-4 border-b border-gray-200">
                    <h3 class="text-xl font-semibold text-black mb-2"><?= esc($event['name']) ?></h3>
                    <p class="text-md text-gray-600"><?= (new \DateTime(esc($event['event_date'])))->format('d F Y') ?></p>
                </div>

                <div class="py-4 border-b border-gray-200">
                    <h4 class="text-lg font-semibold text-gray-800 mb-3">Tiket yang Dipesan</h4>
                    <ul class="space-y-2">
                        <?php foreach ($selected_tickets_details as $ticket): ?>
                        <li class="flex justify-between items-center text-gray-700">
                            <span><?= esc($ticket['name']) ?> (<?= esc($ticket['quantity']) ?>x)</span>
                            <span class="font-medium">Rp <?= number_format($ticket['subtotal'], 0, ',', '.') ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <hr class="my-3">
                    <div class="flex justify-between items-center font-bold text-black text-lg">
                        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Rincian Biaya</h3>
                        
                        <div class="space-y-3 mb-4 border-b border-gray-100 pb-4">
                            <?php foreach ($selected_tickets_details as $ticket): ?>
                            <div class="flex justify-between text-sm text-gray-700">
                                <span><?= esc($ticket['quantity']) ?>x <?= esc($ticket['name']) ?></span>
                                <span class="font-medium">Rp <?= number_format($ticket['subtotal'], 0, ',', '.') ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="space-y-2 text-sm text-gray-500 mb-4">
                                <div class="flex justify-between">
                                    <span>Subtotal Tiket</span>
                                    <span>Rp <?= number_format($sub_total, 0, ',', '.') ?></span>
                                </div>
                                
                                <div class="flex justify-between items-center text-orange-600">
                                    <span class="flex items-center gap-1">
                                        Pajak Hiburan (10%) 
                                        <svg class="w-3 h-3 cursor-help" title="Pajak Barang Jasa Tertentu (PBJT) sesuai aturan pemerintah daerah" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </span>
                                    <span>+ Rp <?= number_format($tax_amount, 0, ',', '.') ?></span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span>Biaya Platform</span>
                                    <span>+ Rp <?= number_format($platform_fee, 0, ',', '.') ?></span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span>Biaya Admin</span>
                                    <span>+ Rp <?= number_format($admin_fee, 0, ',', '.') ?></span>
                                </div>
                            </div>

                        <div class="flex justify-between items-center pt-4 border-t-2 border-dashed border-gray-200">
                            <span class="text-base font-bold text-gray-900">Total Bayar</span>
                            <span class="text-xl font-extrabold text-blue-600">Rp <?= number_format($grand_total, 0, ',', '.') ?></span>
                        </div>
                    </div>
                    </div>
                </div>

                <div class="py-4 border-b border-gray-200">
                    <h4 class="text-lg font-semibold text-gray-800 mb-3">Data Diri</h4>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-2 text-gray-700">
                        <dt class="font-medium">Nama Lengkap</dt>
                        <dd><?= esc($personal['first_name']) ?> <?= esc($personal['last_name']) ?></dd>
                        
                        <dt class="font-medium">Email</dt>
                        <dd><?= esc($personal['email']) ?></dd>
                        
                        <dt class="font-medium">No. Telepon</dt>
                        <dd><?= esc($personal['phone_number']) ?></dd>
                        
                        <dt class="font-medium">No. Identitas</dt>
                        <dd><?= esc($personal['identity_number']) ?></dd>
                    </dl>
                </div>

                <div class="pt-4">
                    <h4 class="text-lg font-semibold text-gray-800 mb-3">Metode Pembayaran</h4>
                    <p class="text-lg text-blue-600 font-bold"><?= esc($payment_method_name) ?></p>
                </div>
            </div>

          <div class="mt-8 flex justify-between items-center">
                <a href="/checkout/payment_method" class="text-gray-600 bg-gray-100 hover:bg-gray-200 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Kembali
                </a>

                <div class="flex gap-2">
                    <button type="button" onclick="showCancelModal()" class="text-white bg-danger hover:bg-danger-strong focus:ring-4 focus:ring-danger-medium font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none">
                        Batal
                    </button>
                    <button type="submit" class="text-white bg-brand hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none">
                        Bayar Sekarang
                    </button>
                </div>
            </div>
        </form>
        </div>
    </div>
</main>