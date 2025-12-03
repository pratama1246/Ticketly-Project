<main class="w-full pt-40 mb-20 grow transition-all duration-300">
    <div class="max-w-5xl mx-auto p-4">

        <input type="hidden" id="csrf_security" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
        
        <div class="mb-8 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 text-blue-600 rounded-full mb-4 animate-pulse">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Menunggu Pembayaran</h2>
            
            <div class="flex flex-col items-center justify-center gap-2 text-gray-500">
                <span>Selesaikan pembayaran Anda dalam waktu:</span>
                
                <div class="bg-red-50 text-red-600 px-6 py-2 rounded-full border border-red-100 flex items-center gap-2 shadow-sm">
                    <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span id="body-timer-text" class="font-mono font-bold text-2xl tabular-nums pt-0.5">15:00</span>
                    <span class="text-sm font-medium uppercase tracking-wide opacity-70">Menit</span>
                </div>
            </div>    
            </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <div class="lg:col-span-2 bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <span class="font-bold text-gray-700">Metode Pembayaran</span>
                    <img src="<?= base_url('assets/payment/' . ($order['payment_method'] ?? 'bca') . '.svg') ?>" class="h-8 w-auto object-contain">
                </div>
                
                <div class="p-8 text-center">
                    <p class="text-sm text-gray-500 mb-2">Nomor Virtual Account</p>
                    
                    <div class="flex justify-center items-center gap-3 mb-6">
                        <span id="va-number" class="text-3xl md:text-4xl font-sans font-bold text-blue-600 tracking-wider">
                            <?= '8800' . rand(1000000000, 9999999999) ?>
                        </span>
                        <button type="button" id="btn-copy-va" class="text-gray-400 hover:text-blue-600 transition-colors" title="Salin">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        </button>
                    </div>

                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-sm text-yellow-800 max-w-md mx-auto mb-6">
                        <strong class="block mb-1">Total Pembayaran</strong>
                        <span class="text-2xl font-bold">Rp <?= number_format($order['order_total'], 0, ',', '.') ?></span>
                    </div>

                    <form action="/checkout/confirm/<?= $order['id'] ?>" method="POST" class="mt-6">
                        <?= csrf_field() ?>
                        <button type="button" onclick="showPaymentConfirmModal()" id="btn-pay-trigger" class="inline-flex items-center justify-center w-full sm:w-auto px-8 py-3 text-base font-bold text-white bg-green-600 rounded-lg hover:bg-green-700 transition-all shadow-lg hover:shadow-xl">
                            Saya Sudah Bayar
                        </button>
                    </form>
                    <button type="button" onclick="window.showCancelModal()" class="inline-flex items-center justify-center w-full sm:w-auto px-8 py-3 mt-2 text-base font-bold text-white bg-red-600 rounded-lg hover:bg-red-700 transition-all shadow-lg hover:shadow-xl">
                            Batalkan Pesanan
                        </button>
                    <p class="mt-4 text-xs text-gray-400">Sistem akan memverifikasi pembayaran secara otomatis.</p>
                </div>
            </div>

            <div class="lg:col-span-1 bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                <h3 class="font-bold text-gray-900 mb-4 border-b pb-2">Detail Pesanan</h3>
                
                <img src="<?= base_url($event['poster_image']) ?>" alt="Event" class="w-full h-40 object-cover rounded-lg mb-4 shadow-sm">
                
                <h4 class="font-bold text-lg text-gray-900 leading-tight mb-2"><?= esc($event['name']) ?></h4>
                
                <div class="space-y-2 text-sm text-gray-600 mb-6">
                    <p class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <?= (new \DateTime($event['event_date']))->format('d F Y') ?>
                    </p>
                    <p class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <?= esc($event['venue']) ?>
                    </p>
                </div>

                <div class="border-t pt-4">
                    <span class="text-xs font-bold text-gray-400 uppercase">Order ID</span>
                    <p class="font-mono font-bold text-gray-900 text-sm"><?= esc($order['trx_id'] ?? '#'.$order['id']) ?></p>
                </div>
            </div>

        </div>
    </div>

<!-- Modal Sections -->
<div id="payment-confirm-modal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 max-h-full bg-gray-900/50 backdrop-blur-sm">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-xl shadow-2xl overflow-hidden animate-fade-in-up">
            <div class="p-5 text-center">
                <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="mb-2 text-xl font-bold text-gray-900">Sudah Melakukan Pembayaran?</h3>
                <p class="mb-6 text-gray-500 text-sm">Pastikan nominal transfer sesuai hingga digit terakhir. Pesanan akan diproses otomatis.</p>
                
                <div class="flex justify-center gap-3">
                    <button onclick="closePaymentModals()" type="button" class="py-2.5 px-5 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 hover:bg-gray-100">
                        Cek Lagi
                    </button>
                    <button onclick="processPaymentAjax(<?= $order['id'] ?>)" id="btn-process-ajax" type="button" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5 flex items-center gap-2">
                        <span id="btn-ajax-text">Ya, Sudah Bayar</span>
                        <svg id="btn-ajax-spinner" class="hidden w-4 h-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="payment-success-modal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 max-h-full bg-green-900/80 backdrop-blur-md">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-xl shadow-2xl overflow-hidden animate-bounce-in text-center p-8">
            <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Pembayaran Berhasil!</h2>
            <p class="text-gray-600 mb-4">Terima kasih! E-Tiket telah dikirim ke email:</p>
            
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 mb-6">
                <span id="success-email" class="font-bold text-gray-800 block">email@example.com</span>
                <span class="text-xs text-gray-400 mt-1 block">ID: <span id="success-trx">TRX-123</span></span>
            </div>

            <a href="/" class="inline-block w-full text-white bg-green-600 hover:bg-green-700 font-bold rounded-lg text-sm px-5 py-3 transition-all">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>

<div id="payment-error-modal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 max-h-full bg-red-900/80 backdrop-blur-md">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-xl shadow-2xl overflow-hidden animate-bounce-in text-center p-8">
            <div class="w-20 h-20 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Gagal Memproses</h2>
            <p id="error-message" class="text-gray-600 mb-6">Terjadi kesalahan atau waktu habis.</p>
            <a href="/" class="inline-block w-full text-white bg-red-600 hover:bg-red-700 font-bold rounded-lg text-sm px-5 py-3 transition-all">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>

</main>