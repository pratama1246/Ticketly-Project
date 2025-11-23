<?php
/** login_tailwind.php
 * Tailwind-styled login view for Shield.
 * Save to app/Views/Shield/login.php and set in app/Config/Auth.php:
 * 'login' => '\App\Views\Shield\login'
 */
?>
<?= $this->extend('\App\Views\shield\layout') ?>

<?= $this->section('content') ?>

<div class="flex items-center justify-center py-10">
    <div class="w-full max-w-[1200px] bg-[#ffe398] rounded-[25px] border border-black p-8 md:p-18 flex flex-col md:flex-row gap-4">

        <!-- LEFT PANEL -->
        <div class="w-full md:w-1/2">
            <img src="/assets/ticketly-logo.png" class="w-40 mb-6">

            <h2 class="text-3xl font-bold text-gray-900 mb-2">Log In</h2>
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
                        required
                    >
                </div>

                <!-- PASSWORD -->
                <div>
                    <label class="block font-medium mb-1">Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        class="w-full rounded-md border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none"
                        required
                    >
                </div>

                <!-- REMEMBER + FORGOT -->
                <div class="flex items-center justify-between text-sm mt-2">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="remember" class="w-4 h-4">
                        <span>Remember me</span>
                    </label>

                    <a href="<?= url_to('forgot-password') ?>" class="text-blue-600 hover:underline font-medium">
                        Forgot Password?
                    </a>
                </div>

                <!-- BUTTONS -->
                <div class="flex items-center justify-end gap-4 pt-4">
                    <a 
                        href="<?= url_to('register') ?>" 
                        class="text-blue-600 font-semibold hover:underline"
                    >
                        Sign Up
                    </a>

                    <button 
                        type="submit"
                        class="bg-blue-500 text-white px-8 py-2 rounded-md font-semibold shadow-md hover:bg-blue-600 transition">
                        Log In
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<?= $this->endSection() ?>
