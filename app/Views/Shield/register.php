<?= $this->extend('\App\Views\Shield\layout') ?>
<?= $this->section('content') ?>

<div class="flex items-center justify-center py-10">

    <div class="w-full max-w-[1150px] min-h-[520px] bg-[#ffe398] rounded-[25px] border border-black p-10 md:p-18 flex flex-col md:flex-row gap-4">
        <div class="w-full md:w-1/2 flex flex-col justify-between">

            <div>
                <img src="/assets/ticketly-logo.png" class="w-40 mb-6">

                <h2 class="text-3xl font-bold text-gray-900 mb-2">
                    Buat Akun
                </h2>

                <p class="text-gray-700 w-72">
                    Daftar dulu biar kamu bisa checkout tiket konser favoritmu~
                </p>
            </div>

            <div class="hidden md:block"></div>
        </div>

        <!-- RIGHT SIDE (FORM) -->
        <div class="w-full md:w-1/2">

            <?php
                // Kumpulkan semua error dari Shield
                $allErrors = [];

                // 1. Dari $errors variable (Shield validation)
                if (!empty($errors)) {
                    foreach ($errors as $e) {
                        $allErrors[] = $e;
                    }
                }

                // 2. Dari flash session 'error' (single string)
                $flashError = session()->getFlashdata('error');
                if (!empty($flashError)) {
                    $allErrors[] = $flashError;
                }

                // 3. Dari flash session 'errors' (array)
                $flashErrors = session()->getFlashdata('errors');
                if (!empty($flashErrors)) {
                    foreach ((array)$flashErrors as $e) {
                        $allErrors[] = $e;
                    }
                }
            ?>

            <?php if (!empty($allErrors)): ?>
                <div class="mb-5 bg-red-50 border border-red-300 text-red-800 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 mt-0.5 shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <div>
                            <p class="font-semibold text-sm mb-1">Pendaftaran gagal:</p>
                            <ul class="list-disc list-inside text-sm space-y-0.5">
                                <?php foreach ($allErrors as $err): ?>
                                    <li><?= esc($err) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <form action="<?= url_to('register') ?>" method="post" class="space-y-5">
                <?= csrf_field() ?>

                <!-- USERNAME -->
                <div>
                    <label class="block font-semibold mb-1">Username</label>
                    <input 
                        type="text" 
                        name="username" 
                        value="<?= esc(old('username')) ?>"
                        class="w-full rounded-md border <?= isset($errors['username']) ? 'border-red-400 bg-red-50' : 'border-gray-300' ?> px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none"
                        placeholder="Minimal 3 karakter, huruf & angka"
                        required>
                    <?php if (isset($errors['username'])): ?>
                        <p class="text-red-600 text-xs mt-1"><?= esc($errors['username']) ?></p>
                    <?php endif; ?>
                    <p class="text-gray-500 text-xs mt-1">3–30 karakter, hanya huruf, angka, dan titik (.)</p>
                </div>

                <!-- EMAIL -->
                <div>
                    <label class="block font-medium mb-1">Email</label>
                    <input 
                        type="email" 
                        name="email" 
                        value="<?= esc(old('email')) ?>"
                        class="w-full rounded-md border <?= isset($errors['email']) ? 'border-red-400 bg-red-50' : 'border-gray-300' ?> px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none"
                        required>
                    <?php if (isset($errors['email'])): ?>
                        <p class="text-red-600 text-xs mt-1"><?= esc($errors['email']) ?></p>
                    <?php endif; ?>
                </div>

                <!-- PASSWORD -->
                <div>
                    <label class="block font-medium mb-1 text-gray-900">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" 
                            class="w-full rounded-md border <?= isset($errors['password']) ? 'border-red-400 bg-red-50' : 'border-gray-300' ?> px-4 py-2 pr-10 focus:ring-2 focus:ring-blue-400 outline-none bg-white" 
                            placeholder="<?= lang('Auth.password') ?>" autocomplete="off" required>
                        <button type="button" id="toggle-password-btn" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none">
                            <svg class="eye-open h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg class="eye-closed h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                    <?php if (isset($errors['password'])): ?>
                        <p class="text-red-600 text-xs mt-1"><?= esc($errors['password']) ?></p>
                    <?php endif; ?>
                    <p class="text-gray-500 text-xs mt-1">Minimal 8 karakter</p>
                </div>

                <!-- CONFIRM PASSWORD -->
                <div>
                    <label class="block font-medium mb-1 text-gray-900">Konfirmasi Password</label>
                    <div class="relative">
                        <input type="password" name="password_confirm" id="password_confirm" 
                            class="w-full rounded-md border <?= isset($errors['password_confirm']) ? 'border-red-400 bg-red-50' : 'border-gray-300' ?> px-4 py-2 pr-10 focus:ring-2 focus:ring-blue-400 outline-none bg-white"
                            placeholder="<?= lang('Auth.passwordConfirm') ?>" 
                            autocomplete="off"
                            required>
                        <button type="button" id="toggle-password-confirm-btn" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none">
                            <svg class="eye-open h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg class="eye-closed h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                    <?php if (isset($errors['password_confirm'])): ?>
                        <p class="text-red-600 text-xs mt-1"><?= esc($errors['password_confirm']) ?></p>
                    <?php endif; ?>
                </div>

                <!-- BUTTONS -->
                <div class="flex items-center justify-end gap-4 pt-3">
                    <a href="<?= url_to('login') ?>" class="text-blue-600 font-semibold hover:underline">
                        Sudah punya akun? Masuk
                    </a>

                    <button type="submit" class="bg-blue-500 text-white px-8 py-2 rounded-md font-semibold shadow-md hover:bg-blue-600 transition">
                        Daftar
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<?= $this->endSection() ?>
