<?= $this->extend('layout/checkout') ?>
<?= $this->section('content') ?>

<main class="w-full pt-32 mb-20 grow transition-all duration-300">
    <div class="max-w-3xl mx-auto p-4 animate-fade-in">
        <div class="card-flat">
            <h2 class="text-2xl font-extrabold text-slate-900 mb-4 border-b-2 border-slate-100 pb-3">Metode Pembayaran</h2>

            <form action="/checkout/process_payment" method="POST" id="paymentMethodForm">
                <?= csrf_field() ?>

                <div class="space-y-6">

                <?php if (!empty($ewallets)): ?>
                <div>
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">E-Wallet</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <?php foreach ($ewallets as $wallet): ?>
                            <label for="pay_<?= $wallet['code'] ?>" class="flex items-center p-4 border border-slate-200 rounded-xl bg-white cursor-pointer transition-all duration-150 hover:scale-[1.01] transition-transform duration-200 hover:shadow-sm group has-[:checked]:bg-yellow-accent-light has-[:checked]:border-slate-200 has-[:checked]:shadow-md">
                                <input type="radio" id="pay_<?= $wallet['code'] ?>" name="payment_method" value="<?= $wallet['code'] ?>" class="sr-only" required>
                                <div class="w-5 h-5 rounded-full border border-slate-200 bg-white flex items-center justify-center mr-3 group-has-[:checked]:bg-slate-800 transition-colors shrink-0">
                                    <div class="w-2.5 h-2.5 rounded-full bg-white opacity-0 group-has-[:checked]:opacity-100 transition-opacity"></div>
                                </div>
                                <img src="<?= base_url($wallet['logo_image']) ?>" alt="<?= $wallet['name'] ?>" class="h-8 w-auto object-contain transition-all shrink-0">
                                <span class="ml-4 text-md font-extrabold text-slate-800"><?= esc($wallet['name']) ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($vas)): ?>
                <div>
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Virtual Account</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <?php foreach ($vas as $va): ?>
                            <label for="pay_<?= $va['code'] ?>" class="flex items-center p-4 border border-slate-200 rounded-xl bg-white cursor-pointer transition-all duration-150 hover:scale-[1.01] transition-transform duration-200 hover:shadow-sm group has-[:checked]:bg-yellow-accent-light has-[:checked]:border-slate-200 has-[:checked]:shadow-md">
                                <input type="radio" id="pay_<?= $va['code'] ?>" name="payment_method" value="<?= $va['code'] ?>" class="sr-only" required>
                                <div class="w-5 h-5 rounded-full border border-slate-200 bg-white flex items-center justify-center mr-3 group-has-[:checked]:bg-slate-800 transition-colors shrink-0">
                                    <div class="w-2.5 h-2.5 rounded-full bg-white opacity-0 group-has-[:checked]:opacity-100 transition-opacity"></div>
                                </div>
                                <img src="<?= base_url($va['logo_image']) ?>" alt="<?= $va['name'] ?>" class="h-8 w-auto object-contain transition-all shrink-0">
                                <span class="ml-4 text-md font-extrabold text-slate-800"><?= esc($va['name']) ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($others)): ?>
                <div>
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Paylater & Digital Bank</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <?php foreach ($others as $other): ?>
                            <label for="pay_<?= $other['code'] ?>" class="flex items-center p-4 border border-slate-200 rounded-xl bg-white cursor-pointer transition-all duration-150 hover:scale-[1.01] transition-transform duration-200 hover:shadow-sm group has-[:checked]:bg-yellow-accent-light has-[:checked]:border-slate-200 has-[:checked]:shadow-md">
                                <input type="radio" id="pay_<?= $other['code'] ?>" name="payment_method" value="<?= $other['code'] ?>" class="sr-only" required>
                                <div class="w-5 h-5 rounded-full border border-slate-200 bg-white flex items-center justify-center mr-3 group-has-[:checked]:bg-slate-800 transition-colors shrink-0">
                                    <div class="w-2.5 h-2.5 rounded-full bg-white opacity-0 group-has-[:checked]:opacity-100 transition-opacity"></div>
                                </div>
                                <img src="<?= base_url($other['logo_image']) ?>" alt="<?= $other['name'] ?>" class="h-8 w-auto object-contain transition-all shrink-0">
                                <span class="ml-4 text-md font-extrabold text-slate-800"><?= esc($other['name']) ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                </div>

                <div class="mt-8 pt-5 border-t-2 border-slate-100 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                    <a href="/checkout/personal_info" class="btn-flat-gray justify-center w-full sm:w-auto">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Kembali
                    </a>

                    <div class="flex flex-col-reverse sm:flex-row gap-3 w-full sm:w-auto">
                        <button type="button" onclick="showCancelModal()" class="btn-flat-gray hover:text-red-600 justify-center w-full sm:w-auto">
                            Batal
                        </button>
                        <button type="submit" class="btn-flat-blue justify-center w-full sm:w-auto">
                            Lanjut Review
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<?= $this->endSection() ?>