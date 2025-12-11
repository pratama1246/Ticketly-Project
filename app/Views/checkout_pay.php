<main class="w-full pt-60 md:pt-42 mb-20 grow transition-all duration-300">
    <input type="hidden" id="csrf_security" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">

    <div class="max-w-4xl mx-auto w-full space-y-6">

        <div class="relative w-full rounded-t-2xl overflow-hidden shadow-lg">
            <img src="<?= base_url($event['poster_image']) ?>" 
                 alt="<?= esc($event['name']) ?>" 
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-linear-to-t from-black/80 to-transparent"></div>
            <div class="absolute bottom-4 left-4 text-white">
                <h2 class="font-bold text-lg leading-tight mb-1"><?= esc($event['name']) ?></h2>
                <p class="text-xs text-gray-300 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <?= esc($event['venue']) ?>
                </p>
            </div>
        </div>

        <div class="bg-white rounded-b-2xl rounded-t-none shadow-xl border border-gray-100 overflow-hidden -mt-6 relative z-10">
            
            <div class="text-center pt-8 pb-4 border-b border-dashed border-gray-200">
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Masih ada waktu untuk menyelesaikan pembayaran</h1>
                <div class="inline-flex items-center gap-2 text-blue-700 px-4 mt-4 mb-4 py-1.5 font-sans font-bold text-2xl">
                    <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span id="body-timer-text">15:00</span>
                </div>
                <p class="text-gray-500 text-md mb-2">Batas waktu untuk melakukan pembayaran</p>
                <h1>
                    <p class="text-gray-900 font-bold text-2xl">
                        <?php 
                            $createdAt = \CodeIgniter\I18n\Time::parse($order['created_at']);
                            
                            $deadline  = $createdAt->addMinutes(15);
                            
                            echo $deadline->toLocalizedString('EEEE, d MMMM yyyy (HH:mm') . ' WIB)';
                        ?>
                    </p>
                </h1>
                <p class="text-sm text-gray-400 mt-2 px-6">Jika melewati batas waktu, pesanan Anda akan dibatalkan otomatis.</p>
            </div>

            <div class="p-6 space-y-6">
                
                <div class="bg-gray-50 rounded-xl border border-gray-200 p-4">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-sm font-semibold text-gray-600">Metode Pembayaran</span>
                        <img src="<?= base_url('assets/payment/' . ($order['payment_method'] ?? 'bca') . '.svg') ?>" class="h-6 w-auto object-contain">
                    </div>

                    <div class="space-y-1">
                        <p class="text-xs text-gray-400 uppercase tracking-wide">Nomor Virtual Account</p>
                        <div class="flex justify-between items-end">
                            <span id="va-number" class="text-2xl font-bold text-gray-800 tracking-wider">
                                <?= '8800' . rand(1000000000, 9999999999) ?>
                            </span>
                            <button type="button" id="btn-copy-va" class="text-blue-600 hover:text-blue-700 text-sm font-medium flex items-center gap-1 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                Salin
                            </button>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <p class="text-xs text-gray-400 uppercase tracking-wide">Total Pembayaran</p>
                        <span class="text-xl font-bold text-orange-600">
                            Rp <?= number_format($order['order_total'], 0, ',', '.') ?>
                        </span>
                    </div>
                </div>

                <div class="space-y-3">
                    <h3 class="text-sm font-bold text-gray-800">Detail Pesanan</h3>
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Order ID</span>
                        <span class="font-mono font-medium text-gray-900"><?= esc($order['trx_id'] ?? '#'.$order['id']) ?></span>
                    </div>
                     <div class="flex justify-between text-sm text-gray-600">
                        <span>Tanggal Event</span>
                        <span class="font-medium text-gray-900 text-right">
                              <?php 
                                $s = \CodeIgniter\I18n\Time::parse($event['event_date']);
                                if (!empty($event['event_end_date'])) {
                                    $e = \CodeIgniter\I18n\Time::parse($event['event_end_date']);
                                    if ($s->format('Y-m-d') === $e->format('Y-m-d')) {
                                        echo $s->toLocalizedString('d F Y') . ' • ' . $s->format('H:i') . ' - ' . $e->format('H:i') . ' WIB';
                                    } else {
                                        echo $s->toLocalizedString('d MMMM') . ' - ' . $e->toLocalizedString('d MMMM Y');
                                    }
                                } else {
                                    echo $s->toLocalizedString('d F Y') . ' • ' . $s->format('H:i') . ' WIB';
                                }
                            ?>
                        </span>
                    </div>
                </div>

            </div>

            <div class="p-6 pt-0 space-y-3">
                <form action="/checkout/confirm/<?= $order['id'] ?>" method="POST" class="w-full">
                    <?= csrf_field() ?>
                    <button type="button" onclick="showPaymentConfirmModal()" id="btn-pay-trigger" 
                        class="w-full py-3.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-200 transition-all transform active:scale-95 flex items-center justify-center gap-2">
                        Konfirmasi Pembayaran
                    </button>
                </form>
                
                <button type="button" onclick="window.showCancelModal()" 
                    class="w-full py-3 px-4 bg-white border border-gray-200 text-gray-500 font-medium rounded-xl hover:bg-gray-50 hover:text-red-600 transition-colors">
                    Batalkan Pesanan
                </button>
            </div>

        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 mb-4 text-sm uppercase tracking-wider">Instruksi Pembayaran</h3>
            <div class="space-y-4">
                <div class="text-sm text-gray-600 space-y-2">
                    <p>1. Buka aplikasi Mobile Banking atau ATM Anda.</p>
                    <p>2. Pilih menu <strong>Transfer Virtual Account</strong>.</p>
                    <p>3. Masukkan nomor VA: <span class="font-mono bg-gray-100 px-1 rounded">8800...</span></p>
                    <p>4. Periksa detail nama dan total tagihan.</p>
                    <p>5. Masukkan PIN Anda dan simpan bukti transaksi.</p>
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