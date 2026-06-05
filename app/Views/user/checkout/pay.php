<?= $this->extend('layout/checkout') ?>
<?= $this->section('content') ?>

<main class="w-full pt-[136px] md:pt-[108px] mb-20 grow transition-all duration-300">
    <input type="hidden" id="csrf_security" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">

    <div class="max-w-3xl mx-auto w-full space-y-6 px-4 animate-fade-in">

        <!-- Header Poster -->
        <div class="relative w-full border border-slate-200 rounded-2xl overflow-hidden shadow-flat-md h-48 sm:h-60 bg-slate-100">
            <img src="<?= base_url($event['poster_image']) ?>" 
                 alt="<?= esc($event['name']) ?>" 
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/60 to-transparent"></div>
            <div class="absolute bottom-4 left-4 right-4 text-white">
                <span class="inline-block mb-1 text-xs font-bold uppercase tracking-wider text-yellow-accent-normal">Detail Event</span>
                <h2 class="font-black text-lg sm:text-2xl leading-tight mb-1 text-white"><?= esc($event['name']) ?></h2>
                <p class="text-xs sm:text-sm text-slate-300 flex items-center gap-1.5 font-bold">
                    <svg class="w-4 h-4 text-yellow-accent-normal shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <?= esc($event['venue']) ?>
                </p>
            </div>
        </div>

        <!-- Timer & Info Card -->
        <div class="card-flat">
            <div class="text-center pb-5 border-b-2 border-dashed border-slate-200">
                <h1 class="text-lg sm:text-xl font-extrabold text-slate-800">Selesaikan Pembayaran Sebelum Habis</h1>
                
                <div class="inline-flex items-center gap-2.5 bg-yellow-accent-light text-slate-900 border border-slate-200 px-6 py-2.5 my-4 font-black text-2xl rounded-xl shadow-flat-sm">
                    <svg class="w-6 h-6 text-slate-800 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span id="body-timer-text">15:00</span>
                </div>
                
                <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Batas Waktu Pembayaran</p>
                <p class="text-slate-900 font-black text-lg sm:text-xl">
                    <?php 
                        $createdAt = \CodeIgniter\I18n\Time::parse($order['created_at']);
                        $deadline  = $createdAt->addMinutes(15);
                        echo $deadline->toLocalizedString('EEEE, d MMMM yyyy (HH:mm') . ' WIB)';
                    ?>
                </p>
                <p class="text-xs text-slate-400 mt-2">Jika melewati batas waktu, tiket pemesanan Anda akan otomatis dilepas kembali ke publik.</p>
            </div>

            <!-- VA and Details -->
            <div class="py-6 space-y-6">
                <?php 
                    $vaNumberRaw = '8800' . rand(1000000000, 9999999999);
                    $vaNumberFormatted = implode(' ', str_split($vaNumberRaw, 4));
                ?>
                
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 shadow-flat-sm">
                    <div class="flex justify-between items-center mb-4 pb-3 border-b-2 border-slate-200">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Metode Pembayaran</span>
                        <div class="bg-white border border-slate-200 px-3 py-1 rounded-xl">
                            <img src="<?= base_url('assets/payment/' . ($order['payment_method'] ?? 'bca') . '.svg') ?>" class="h-6 w-auto object-contain">
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Nomor Virtual Account</p>
                        <div class="flex justify-between items-center bg-white border border-slate-200 rounded-xl p-3">
                            <span id="va-number" class="text-lg sm:text-2xl font-black text-slate-800 tracking-wider">
                                <?= $vaNumberFormatted ?>
                            </span>
                            <button type="button" id="btn-copy-va" class="btn-flat-gray text-xs py-1.5 px-3 rounded-lg flex items-center gap-1 shadow-none border hover:bg-slate-100">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                Salin
                            </button>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t-2 border-dashed border-slate-200">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total Pembayaran</p>
                        <span class="text-2xl font-black text-palette-orange-dark">
                            Rp <?= number_format($order['order_total'], 0, ',', '.') ?>
                        </span>
                    </div>
                </div>

                <div class="space-y-3 pt-3">
                    <h3 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider">Detail Pesanan</h3>
                    <div class="flex justify-between text-sm text-slate-600 bg-slate-50 border border-slate-200 rounded-xl p-3.5">
                        <div class="space-y-2">
                            <div class="flex items-center gap-1">
                                <span class="font-bold text-slate-400 text-xs uppercase tracking-wider">Order ID:</span>
                                <span class="font-mono font-bold text-slate-800"><?= esc($order['trx_id'] ?? '#'.$order['id']) ?></span>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-400 text-xs uppercase tracking-wider mb-0.5">Tanggal Event:</span>
                                <span class="font-bold text-slate-800">
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
                </div>

            </div>

            <!-- Buttons -->
            <div class="pt-2 gap-3 flex flex-col sm:flex-row">
                <form action="/checkout/confirm/<?= $order['id'] ?>" method="POST" class="grow w-full sm:w-auto">
                    <?= csrf_field() ?>
                    <button type="button" onclick="showPaymentConfirmModal()" id="btn-pay-trigger" 
                        class="w-full btn-flat-blue shadow-sm hover:translate-y-0.5">
                        Konfirmasi Pembayaran
                    </button>
                </form>
                
                <button type="button" onclick="window.showCancelModal()" 
                    class="btn-flat-gray hover:text-red-600 w-full sm:w-auto justify-center text-center">
                    Batalkan Pesanan
                </button>
            </div>
        </div>

        <!-- Instructions -->
        <div class="card-flat">
            <h3 class="font-extrabold text-slate-800 mb-4 text-sm uppercase tracking-wider border-b-2 border-slate-100 pb-2">Instruksi Pembayaran</h3>
            <div class="space-y-4">
                <div class="text-sm text-slate-600 space-y-3">
                    <p class="flex gap-2.5">
                        <span class="flex items-center justify-center w-5 h-5 rounded-full bg-slate-800 text-white font-bold text-xs shrink-0 mt-0.5">1</span>
                        <span>Buka aplikasi Mobile Banking atau pergi ke mesin ATM Anda.</span>
                    </p>
                    <p class="flex gap-2.5">
                        <span class="flex items-center justify-center w-5 h-5 rounded-full bg-slate-800 text-white font-bold text-xs shrink-0 mt-0.5">2</span>
                        <span>Pilih menu <strong class="text-slate-900 font-extrabold">Transfer Virtual Account</strong>.</span>
                    </p>
                    <p class="flex gap-2.5">
                        <span class="flex items-center justify-center w-5 h-5 rounded-full bg-slate-800 text-white font-bold text-xs shrink-0 mt-0.5">3</span>
                        <span>Masukkan nomor VA: <span class="font-mono bg-yellow-accent-light border border-slate-400 font-bold px-1.5 py-0.5 rounded"><?= $vaNumberRaw ?></span></span>
                    </p>
                    <p class="flex gap-2.5">
                        <span class="flex items-center justify-center w-5 h-5 rounded-full bg-slate-800 text-white font-bold text-xs shrink-0 mt-0.5">4</span>
                        <span>Periksa detail nama merchant/event dan total tagihan Anda.</span>
                    </p>
                    <p class="flex gap-2.5">
                        <span class="flex items-center justify-center w-5 h-5 rounded-full bg-slate-800 text-white font-bold text-xs shrink-0 mt-0.5">5</span>
                        <span>Masukkan PIN Anda, selesaikan transaksi, dan simpan bukti transfer.</span>
                    </p>
                </div>
            </div>
        </div>

    </div>

<!-- Modal Confirmation -->
<div id="payment-confirm-modal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed inset-0 z-50 justify-center items-center w-full bg-slate-950/60 backdrop-blur-xs transition-all duration-300">
    <div class="relative p-4 w-full max-w-md">
        <div class="relative bg-white border border-slate-200 rounded-2xl shadow-lg overflow-hidden animate-fade-in-up">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-blue-primary-light text-blue-primary-normal border border-slate-200 rounded-full flex items-center justify-center mx-auto mb-4 shadow-flat-sm">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="mb-2 text-xl font-extrabold text-slate-900">Sudah Melakukan Pembayaran?</h3>
                <p class="mb-6 text-slate-500 text-sm font-medium">Pastikan nominal transfer sesuai hingga digit terakhir. Pesanan akan diproses otomatis.</p>
                
                <div class="flex justify-center gap-3 border-t-2 border-slate-100 pt-4">
                    <button onclick="closePaymentModals()" type="button" class="btn-flat-gray py-2 px-4">
                        Cek Lagi
                    </button>
                    <button onclick="processPaymentAjax(<?= $order['id'] ?>)" id="btn-process-ajax" type="button" class="btn-flat-blue py-2 px-4 shadow-sm">
                        <span id="btn-ajax-text">Ya, Sudah Bayar</span>
                        <svg id="btn-ajax-spinner" class="hidden w-4 h-4 animate-spin shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Success -->
<div id="payment-success-modal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed inset-0 z-50 justify-center items-center w-full bg-slate-950/70 backdrop-blur-xs transition-all duration-300">
    <div class="relative p-4 w-full max-w-md">
        <div class="relative bg-white border border-slate-200 rounded-2xl shadow-lg overflow-hidden text-center p-8 animate-fade-in-up">
            <div class="w-20 h-20 bg-green-50 text-green-600 border border-slate-200 rounded-full flex items-center justify-center mx-auto mb-4 shadow-flat-sm">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h2 class="text-2xl font-black text-slate-900 mb-2">Pembayaran Berhasil!</h2>
            <p class="text-slate-600 text-sm font-medium mb-4">Terima kasih! E-Tiket telah dikirim ke email:</p>
            
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 mb-6 shadow-flat-sm">
                <span id="success-email" class="font-extrabold text-slate-800 block text-base">email@example.com</span>
                <span class="text-xs text-slate-400 mt-1.5 block font-bold">ID: <span id="success-trx" class="font-mono text-slate-800">TRX-123</span></span>
            </div>

            <a href="/" class="btn-flat-yellow w-full text-center">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>

<!-- Modal Error -->
<div id="payment-error-modal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed inset-0 z-50 justify-center items-center w-full bg-slate-950/70 backdrop-blur-xs transition-all duration-300">
    <div class="relative p-4 w-full max-w-md">
        <div class="relative bg-white border border-slate-200 rounded-2xl shadow-lg overflow-hidden text-center p-8 animate-fade-in-up">
            <div class="w-20 h-20 bg-red-50 text-red-600 border border-slate-200 rounded-full flex items-center justify-center mx-auto mb-4 shadow-flat-sm">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
            <h2 class="text-2xl font-black text-slate-900 mb-2">Gagal Memproses</h2>
            <p id="error-message" class="text-slate-600 text-sm font-medium mb-6">Terjadi kesalahan atau waktu habis.</p>
            <a href="/" class="btn-flat-blue bg-red-600 hover:bg-red-700 text-white w-full text-center">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>

</main>

<?= $this->endSection() ?>
