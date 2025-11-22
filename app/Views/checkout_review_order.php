<main class="w-full grow mb-20">
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
                        <span>Total Pembayaran</span>
                        <span>Rp <?= number_format($total_price, 0, ',', '.') ?></span>
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

           <div class="mt-8 text-right">
                <button type="button" onclick="window.location.href='<?=  base_url('/checkout/cancel') ?>'" class="text-white bg-danger box-border border border-transparent hover:bg-danger-strong focus:ring-4 focus:ring-danger-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                    Batal
                </button>
                <button type="submit" class="text-white bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                    Bayar Sekarang
                </button>
            </div>
        </form>
        </div>
    </div>
</main>