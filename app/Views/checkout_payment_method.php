    <script type="text/javascript"
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="Mid-client-luIJNWBXVVrc0zac"></script>
    <main class="w-full pt-24 grow mb-20">
        <div class="max-w-4xl mx-auto p-4 border border-solid-black rounded-lg">
            <div class="p-6 md:p-10">

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
                            <svg class="w-5 h-5 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            Data <span class="hidden sm:inline-flex sm:ms-2">Diri</span>
                        </span>
                    </li>
                    <li class="flex md:w-full items-center text-fg-brand sm:after:content-[''] after:w-full after:h-1 after:border-b after:border-default after:border-px after:hidden sm:after:inline-block after:mx-6 xl:after:mx-10">
                        <span class="flex items-center after:content-['/'] sm:after:hidden after:mx-2 after:text-fg-disabled">
                            <span class="me-2">2</span>
                            Pembayaran <span class="hidden sm:inline-flex sm:ms-2"></span>
                        </span>
                    </li>

                    <button id="pay-button">Pay!</button>
                    <script type="text/javascript">
                        var payButton = document.getElementById('pay-button');
                        payButton.addEventListener('click', function() {
                            snap.pay('<SNAP_TOKEN>');
                        });
                    </script>

                    <li class="flex items-center">
                        <span class="me-2">3</span>
                        Konfirmasi
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
                                $ewallets = [
                                    'ovo' => ['name' => 'OVO', 'logo' => 'assets/payment/ovo.svg'],
                                    'dana' => ['name' => 'DANA', 'logo' => 'assets/payment/dana.svg'],
                                    'gopay' => ['name' => 'GoPay', 'logo' => 'assets/payment/gopay.svg'],
                                    'shopeepay' => ['name' => 'ShopeePay', 'logo' => 'assets/payment/shopeepay.svg']
                                ];
                                ?>
                                <?php foreach ($ewallets as $key => $wallet): ?>
                                    <label for="<?= $key ?>" class="flex items-center p-4 border border-gray-300 rounded-lg has-checked:bg-blue-50 has-checked:border-blue-500 cursor-pointer">
                                        <input type="radio" id="<?= $key ?>" name="payment_method" value="<?= $key ?>" class="w-5 h-5 text-blue-600 focus:ring-blue-500">

                                        <img src="<?= base_url($wallet['logo']) ?>" alt="<?= $wallet['name'] ?>" class="ml-4 h-8 w-auto object-contain">

                                        <span class="ml-3 text-md font-medium text-gray-900"><?= $wallet['name'] ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">Virtual Account</h3>
                            <div class="space-y-3">
                                <?php
                                $vas = [
                                    'bca_va' => ['name' => 'BCA Virtual Account', 'logo' => 'assets/payment/bca.svg'],
                                    'bri_va' => ['name' => 'BRI Virtual Account', 'logo' => 'assets/payment/bri.svg'],
                                    'bni_va' => ['name' => 'BNI Virtual Account', 'logo' => 'assets/payment/bni.svg']
                                ];
                                ?>
                                <?php foreach ($vas as $key => $va): ?>
                                    <label for="<?= $key ?>" class="flex items-center p-4 border border-gray-300 rounded-lg has-checked:bg-blue-50 has-checked:border-blue-500 cursor-pointer">
                                        <input type="radio" id="<?= $key ?>" name="payment_method" value="<?= $key ?>" class="w-5 h-5 text-blue-600 focus:ring-blue-500">

                                        <img src="<?= base_url($va['logo']) ?>" alt="<?= $va['name'] ?>" class="ml-4 h-8 w-auto object-contain">

                                        <span class="ml-3 text-md font-medium text-gray-900"><?= $va['name'] ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 text-right">
                        <button type="button" onclick="window.location.href='<?= base_url('/checkout/cancel') ?>'" class="text-white bg-danger box-border border border-transparent hover:bg-danger-strong focus:ring-4 focus:ring-danger-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
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