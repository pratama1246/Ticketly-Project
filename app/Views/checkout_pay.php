<main class="w-full pt-24 mb-20 grow transition-all duration-300">
    <div class="max-w-5xl mx-auto p-4">

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
                        <span id="va-number" class="text-3xl md:text-4xl font-mono font-bold text-blue-600 tracking-wider">
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
                        <button type="submit" class="inline-flex items-center justify-center w-full sm:w-auto px-8 py-3 text-base font-bold text-white bg-green-600 rounded-lg hover:bg-green-700 transition-all shadow-lg hover:shadow-xl">
                            Saya Sudah Bayar
                        </button>
                    </form>
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
</main>

<script>
function copyVA() {
    const vaText = document.getElementById('va-number').innerText.replace(/\s/g, '');
    navigator.clipboard.writeText(vaText);
    
    // Pake SweetAlert yang udah ada di header
    Swal.fire({
        icon: 'success',
        title: 'Disalin!',
        text: 'Nomor VA berhasil disalin.',
        timer: 1500,
        showConfirmButton: false
    });
}
</script>