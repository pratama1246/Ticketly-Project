<?= $this->extend(config('Auth')->views['layout']) ?>

<?= $this->section('title') ?><?= lang('Auth.emailActivateTitle') ?> <?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="flex items-center justify-center py-10">
    <div class="w-full max-w-[1150px] min-h-[520px] bg-[#ffe398] rounded-[25px] border border-black p-10 md:p-18 flex flex-col md:flex-row gap-4">
        <div class="w-full md:w-1/2">

            <img src="/assets/ticketly-logo.png" class="w-40 mb-6">
            <h5 class="text-3xl font-bold text-gray-900 mb-2"><?= lang('Auth.emailActivateTitle') ?></h5>

            <?php if (session('error')) : ?>
                <div class="flex items-start sm:items-center p-4 mb-4 text-sm text-fg-danger-strong rounded-base bg-danger-soft" role="alert"><?= esc(session('error')) ?></div>
            <?php endif ?>

            <p class="text-gray-700 w-72 mb-8"><?= lang('Auth.emailActivateBody') ?></p>
            <img src="/assets/icon/password-shield.svg" class="w-80 mb-6">
        </div>

        <div class="w-full md:w-1/2">
            <form action="<?= url_to('auth-action-verify') ?>" method="post">
                <?= csrf_field() ?>

                <div>
                    <label for="floatingTokenInput" class="text-xl font-bold text-gray-900 mt-20">Masukkan OTP Anda</label>

                    <input type="hidden" name="token" id="real-token" required>

                    <div class="flex justify-center gap-2 sm:gap-3 mt-6" id="otp-container">
                        <input type="text" maxlength="1" class="otp-box w-10 h-10 sm:w-12 sm:h-12 text-center text-xl font-bold text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" inputmode="numeric" autocomplete="one-time-code" />
                        <input type="text" maxlength="1" class="otp-box w-10 h-10 sm:w-12 sm:h-12 text-center text-xl font-bold text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" inputmode="numeric" />
                        <input type="text" maxlength="1" class="otp-box w-10 h-10 sm:w-12 sm:h-12 text-center text-xl font-bold text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" inputmode="numeric" />
                        <input type="text" maxlength="1" class="otp-box w-10 h-10 sm:w-12 sm:h-12 text-center text-xl font-bold text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" inputmode="numeric" />
                        <input type="text" maxlength="1" class="otp-box w-10 h-10 sm:w-12 sm:h-12 text-center text-xl font-bold text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" inputmode="numeric" />
                        <input type="text" maxlength="1" class="otp-box w-10 h-10 sm:w-12 sm:h-12 text-center text-xl font-bold text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" inputmode="numeric" />
                    </div>
                </div>
                <div class="flex items-center justify-end gap-4 pt-4">
                    <button type="submit" class="bg-blue-500 text-white px-8 py-2 rounded-md font-semibold shadow-md hover:bg-blue-600 transition">
                        <?= lang('Auth.send') ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
