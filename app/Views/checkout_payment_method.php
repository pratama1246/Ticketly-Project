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
                    <svg class="w-5 h-5 text-green-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11.917 9.724 16.5 19 7.5"/></svg>
                </span>
                <span>
                    <h3 class="font-medium leading-tight">Data Diri</h3>
                    <p class="text-sm">Info personal diisi</p>
                </span>
            </li>
            <li class="flex items-center text-blue-600 space-x-3 rtl:space-x-reverse">
                <span class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-full lg:h-12 lg:w-12 shrink-0">
                    <svg class="w-5 h-5 text-blue-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M6 14h2m3 0h5M3 7v10a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1Z"/></svg>
                </span>
                <span>
                    <h3 class="font-medium leading-tight">Pembayaran</h3>
                    <p class="text-sm">Pilih metode bayar</p>
                </span>
            </li>
            <li class="flex items-center text-gray-500 space-x-3 rtl:space-x-reverse">
                <span class="flex items-center justify-center w-10 h-10 bg-gray-100 rounded-full lg:h-12 lg:w-12 shrink-0">
                    <svg class="w-5 h-5 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m11.5 11.5 2.071 2.071M12 18a6 6 0 1 0 0-12 6 6 0 0 0 0 12Z"/></svg>
                </span>
                <span>
                    <h3 class="font-medium leading-tight">Konfirmasi</h3>
                    <p class="text-sm">Review pesanan</p>
                </span>
            </li>
        </ol>

        <h2 class="text-2xl font-bold text-black mb-4">Metode Pembayaran</h2>
        
        <form action="/checkout/process_payment" method="POST">
            <?= csrf_field() ?>
            
            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">E-Wallet</h3>
                    <div class="space-y-3">
                        <?php 
                            // Daftar E-Wallet dari Mockup
                            $ewallets = [
                                'ovo' => ['name' => 'OVO', 'logo' => 'assets/payment/ovo.png'],
                                'dana' => ['name' => 'DANA', 'logo' => 'assets/payment/dana.png'],
                                'gopay' => ['name' => 'GoPay', 'logo' => 'assets/payment/gopay.png'],
                                'shopeepay' => ['name' => 'ShopeePay', 'logo' => 'assets/payment/shopeepay.png']
                            ];
                        ?>
                        <?php foreach ($ewallets as $key => $wallet): ?>
                        <label for="<?= $key ?>" class="flex items-center p-4 border border-gray-300 rounded-lg has-[:checked]:bg-blue-50 has-[:checked]:border-blue-500 cursor-pointer">
                            <input type="radio" id="<?= $key ?>" name="payment_method" value="<?= $key ?>" class="w-5 h-5 text-blue-600 focus:ring-blue-500">
                            <span class="ml-4 text-md font-medium text-gray-900"><?= $wallet['name'] ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Virtual Account</h3>
                    <div class="space-y-3">
                        <?php 
                            // Daftar VA dari Mockup
                            $vas = [
                                'bca_va' => ['name' => 'BCA Virtual Account', 'logo' => 'assets/payment/bca.png'],
                                'bri_va' => ['name' => 'BRI Virtual Account', 'logo' => 'assets/payment/bri.png'],
                                'bni_va' => ['name' => 'BNI Virtual Account', 'logo' => 'assets/payment/bni.png']
                            ];
                        ?>
                        <?php foreach ($vas as $key => $va): ?>
                        <label for="<?= $key ?>" class="flex items-center p-4 border border-gray-300 rounded-lg has-[:checked]:bg-blue-50 has-[:checked]:border-blue-500 cursor-pointer">
                            <input type="radio" id="<?= $key ?>" name="payment_method" value="<?= $key ?>" class="w-5 h-5 text-blue-600 focus:ring-blue-500">
                            <span class="ml-4 text-md font-medium text-gray-900"><?= $va['name'] ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="mt-8 text-right">
                <button type="submit" class="bg-blue-600 text-white font-bold text-lg py-3 px-10 rounded-lg hover:bg-blue-700 transition duration-300">
                    Lanjut ke Konfirmasi
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