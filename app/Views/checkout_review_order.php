<main class="w-full pt-24">
    <div class="max-w-4xl mx-auto p-4 border border-solid-black rounded-lg">
        <div class="p-6 md:p-10 rounded-lg border border-solid-black">

        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-6 text-center" role="alert">
            <strong class="font-bold">Sisa waktu untuk memesan tiket: </strong>
            <span class="font-mono text-lg" id="checkout-timer">
                <?php
                    $timeLeft = session('checkout_time_left') ?? 300;
                    echo floor($timeLeft / 60) . ':' . str_pad($timeLeft % 60, 2, '0', STR_PAD_LEFT);
                ?>
            </span>
        </div>

        <ol class="flex items-center w-full text-sm font-medium text-center text-body sm:text-base mb-8">
            <li class="flex md:w-full items-center text-fg-brand sm:after:content-[''] after:w-full after:h-1 after:border-b after:border-default after:border-px after:hidden sm:after:inline-block after:mx-6 xl:after:mx-10">
                <span class="flex items-center after:content-['/'] sm:after:hidden after:mx-2 after:text-fg-disabled">
                    <svg class="w-5 h-5 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                    Data <span class="hidden sm:inline-flex sm:ms-2">Diri</span>
                </span>
            </li>
            <li class="flex md:w-full items-center text-fg-brand sm:after:content-[''] after:w-full after:h-1 after:border-b after:border-default after:border-px after:hidden sm:after:inline-block after:mx-6 xl:after:mx-10">
                <span class="flex items-center after:content-['/'] sm:after:hidden after:mx-2 after:text-fg-disabled">
                    <svg class="w-5 h-5 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                    Pembayaran <span class="hidden sm:inline-flex sm:ms-2"></span>
                </span>
            </li>
            <li class="flex items-center text-fg-brand">
                <span class="me-2">3</span>
                Konfirmasi
            </li>
        </ol>

        <h2 class="text-2xl font-bold text-black mb-4">Konfirmasi Pesanan</h2>
        
        <form action="/checkout/submit_order" method="POST">
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
                    Lanjut ke Pembayaran
                </button>
            </div>
        </form>
        </div>
    </div>
</main>