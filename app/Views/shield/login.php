<?= $this->extend('\App\Views\Shield\layout') ?>
<?= $this->section('content') ?>

<div class="flex items-center justify-center py-10">
    <div class="w-full max-w-[1200px] bg-[#ffe398] rounded-[25px] border border-black p-8 md:p-18 flex flex-col md:flex-row gap-4">

        <!-- LEFT PANEL -->
        <div class="w-full md:w-1/2">
            <img src="/assets/ticketly-logo.png" class="w-40 mb-6">

            <h2 class="text-3xl font-bold text-gray-900 mb-2">Masuk</h2>
            <p class="text-gray-700 w-72 mb-8">
                Selamat datang kembali di Ticketly. Masukkan email dan kata sandi yang terdaftar.
            </p>

            <!-- extra spacing -->
            <div class="mt-10"></div>
        </div>

        <!-- RIGHT PANEL (THE FORM) -->
        <div class="w-full md:w-1/2">

            <?php if (session('error')): ?>
                <div class="mb-4 p-3 bg-red-200 text-red-700 rounded-lg">
                    <?= session('error') ?>
                </div>
            <?php endif; ?>

            <form action="<?= url_to('login') ?>" method="post" class="space-y-5">
                <?= csrf_field() ?>

                <!-- EMAIL -->
                <div>
                    <label class="block font-medium mb-1">Email</label>
                    <input
                        type="email"
                        name="email"
                        class="w-full rounded-md border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none"
                        required>
                </div>

                <!-- PASSWORD -->
                <div>
                    <label class="block font-medium mb-1 text-gray-900">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" class="w-full rounded-md border border-gray-300 px-4 py-2 pr-10 focus:ring-2 focus:ring-blue-400 outline-none bg-white" required>
                        <button type="button" id="toggle-password-btn" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none">

                            <svg id="eye-open" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>

                            <svg id="eye-closed" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- REMEMBER + FORGOT -->
                <div class="flex items-center justify-between text-sm mt-2">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="remember" class="w-4 h-4">
                        <span>Remember me</span>
                    </label>

                    <a href="<?= url_to('magic-link') ?>" class="text-blue-600 hover:underline font-medium">
                        Lupa Password?
                    </a>
                </div>

                <!-- BUTTONS -->
                <div class="flex items-center justify-end gap-4 pt-4">
                    <a href="<?= url_to('register') ?>" class="text-blue-600 font-semibold hover:underline">
                        Sign Up
                    </a>

                    <button type="submit" class="bg-blue-500 text-white px-8 py-2 rounded-md font-semibold shadow-md hover:bg-blue-600 transition">
                        Log In
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<?= $this->endSection() ?>