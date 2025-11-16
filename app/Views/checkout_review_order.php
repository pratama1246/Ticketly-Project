<main class="w-full pt-24">
    <div class="max-w-4xl mx-auto p-4">

        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-6 text-center" role="alert">
            <strong class="font-bold">Sisa waktu untuk memesan tiket: </strong>
            <span class="font-mono text-lg" id="checkout-timer">
                <?php
                    $timeLeft = session('checkout_time_left') ?? 900;
                    echo floor($timeLeft / 60) . ':' . str_pad($timeLeft % 60, 2, '0', STR_PAD_LEFT);
                ?>
            </span>
        </div>

        <ol class="items-center w-full space-y-4 sm:flex sm:space-x-8 sm:space-y-0 rtl:space-x-reverse mb-8">
            <li class="flex items-center text-green-600 space-x-3 rtl:space-x-reverse">
                <span class="flex items-center justify-center w-10 h-10 bg-green-100 rounded-full lg:h-12 lg:w-12 shrink-0">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11.917 9.724 16.5 19 7.5"/></svg>
                </span>
                <span><h3 class="font-medium leading-tight">Data Diri</h3><p class="text-sm">Info diisi</p></span>
            </li>
            <li class="flex items-center text-green-600 space-x-3 rtl:space-x-reverse">
                <span class="flex items-center justify-center w-10 h-10 bg-green-100 rounded-full lg:h-12 lg:w-12 shrink-0">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11.917 9.724 16.5 19 7.5"/></svg>
                </span>
                <span><h3 class="font-medium leading-tight">Pembayaran</h3><p class="text-sm">Metode dipilih</p></span>
            </li>
            <li class="flex items-center text-blue-600 space-x-3 rtl:space-x-reverse">
                <span class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-full lg:h-12 lg:w-12 shrink-0">
                    <svg class="w-5 h-5 text-blue-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m11.5 11.5 2.071 2.071M12 18a6 6 0 1 0 0-12 6 6 0 0 0 0 12Z"/></svg>
                </span>
                <span><h3 class="font-medium leading-tight">Konfirmasi</h3><p class="text-sm">Review pesanan</p></span>
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
                <button type="submit" class="w-full md:w-auto bg-green-600 text-white font-bold text-lg py-4 px-12 rounded-lg hover:bg-green-700 transition duration-300">
                    Konfirmasi & Bayar
                </button>
            </div>
        </form>

    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        const timerElement = document.getElementById('checkout-timer');
        if (timerElement) {
            let totalSeconds = <?= session('checkout_time_left') ?? 900 ?>;
            const timerInterval = setInterval(() => {
                totalSeconds--;
                if (totalSeconds <= 0) {
                    clearInterval(timerInterval);
                    window.location.reload(); // Filter akan menangani redirect
                }
                let minutes = Math.floor(totalSeconds / 60);
                let seconds = totalSeconds % 60;
                timerElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            }, 1000);
        }
    });
</script>