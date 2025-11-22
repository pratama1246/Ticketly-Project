    <script type="text/javascript"
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="Mid-client-luIJNWBXVVrc0zac"></script>
    <main class="w-full grow mb-20">
        <div class="max-w-4xl mx-auto p-4">

                <h2 class="text-2xl font-bold text-black mb-4">Metode Pembayaran</h2>

                <form action="/checkout/process_payment" method="POST">
                    <?= csrf_field() ?>

                    <div class="space-y-6">

                    <?php if (!empty($ewallets)): ?>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">E-Wallet</h3>
                        <div class="space-y-3">
                            <?php foreach ($ewallets as $wallet): ?>
                                <label for="pay_<?= $wallet['code'] ?>" class="flex items-center p-4 border border-gray-300 rounded-lg has-checked:bg-blue-50 has-checked:border-blue-500 cursor-pointer transition-all hover:shadow-sm group">
                                    <input type="radio" id="pay_<?= $wallet['code'] ?>" name="payment_method" value="<?= $wallet['code'] ?>" class="w-5 h-5 text-blue-600 focus:ring-blue-500">
                                    <img src="<?= base_url($wallet['logo_image']) ?>" alt="<?= $wallet['name'] ?>" class="ml-4 h-8 w-auto object-contain grayscale group-hover:grayscale-0 group-has-checked:grayscale-0 transition-all">
                                    <span class="ml-3 text-md font-medium text-gray-900"><?= esc($wallet['name']) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($vas)): ?>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Virtual Account</h3>
                        <div class="space-y-3">
                            <?php foreach ($vas as $va): ?>
                                <label for="pay_<?= $va['code'] ?>" class="flex items-center p-4 border border-gray-300 rounded-lg has-checked:bg-blue-50 has-checked:border-blue-500 cursor-pointer transition-all hover:shadow-sm group">
                                    <input type="radio" id="pay_<?= $va['code'] ?>" name="payment_method" value="<?= $va['code'] ?>" class="w-5 h-5 text-blue-600 focus:ring-blue-500">
                                    <img src="<?= base_url($va['logo_image']) ?>" alt="<?= $va['name'] ?>" class="ml-4 h-8 w-auto object-contain grayscale group-hover:grayscale-0 group-has-checked:grayscale-0 transition-all">
                                    <span class="ml-3 text-md font-medium text-gray-900"><?= esc($va['name']) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($others)): ?>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Paylater & Digital Bank</h3>
                        <div class="space-y-3">
                            <?php foreach ($others as $other): ?>
                                <label for="pay_<?= $other['code'] ?>" class="flex items-center p-4 border border-gray-300 rounded-lg has-checked:bg-blue-50 has-checked:border-blue-500 cursor-pointer transition-all hover:shadow-sm group">
                                    <input type="radio" id="pay_<?= $other['code'] ?>" name="payment_method" value="<?= $other['code'] ?>" class="w-5 h-5 text-blue-600 focus:ring-blue-500">
                                    <img src="<?= base_url($other['logo_image']) ?>" alt="<?= $other['name'] ?>" class="ml-4 h-8 w-auto object-contain grayscale group-hover:grayscale-0 group-has-checked:grayscale-0 transition-all">
                                    <span class="ml-3 text-md font-medium text-gray-900"><?= esc($other['name']) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

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