<?= $this->extend('\App\Views\shield\layout') ?>
<?= $this->section('content') ?>

<div class="flex items-center justify-center py-10">

    <!-- CARD (SAMA PERSIS STRUKTURNYA DENGAN LOGIN) -->
    <div class="w-full max-w-[1150px] min-h-[520px]
                bg-[#ffe398] rounded-[25px] border border-black
                p-10 md:p-18 flex flex-col md:flex-row gap-4">

        <!-- LEFT SIDE (SAMA PERSIS FORMATNYA) -->
        <div class="w-full md:w-1/2 flex flex-col justify-between">

            <div>
                <img src="/assets/ticketly-logo.png" class="w-40 mb-6">

                <h2 class="text-3xl font-bold text-gray-900 mb-2">
                    Create Account
                </h2>

                <p class="text-gray-700 w-72">
                    Daftar dulu biar kamu bisa checkout tiket konser favoritmu~
                </p>
            </div>

            <div class="hidden md:block"></div>
        </div>

        <!-- RIGHT SIDE (FORM) -->
        <div class="w-full md:w-1/2">

            <!-- ERROR MESSAGE -->
            <?php if (session('error')): ?>
                <div class="mb-4 p-3 bg-red-200 text-red-700 rounded-lg">
                    <?= session('error') ?>
                </div>
            <?php endif; ?>

            <form action="<?= url_to('register') ?>" method="post" class="space-y-5">
                <?= csrf_field() ?>

                <!-- USERNAME -->
                <div>
                    <label class="block font-semibold mb-1">Username</label>
                    <input type="text" name="username"
                           class="w-full rounded-md border border-gray-300 px-4 py-2
                                  focus:ring-2 focus:ring-blue-400 outline-none"
                           required>
                </div>

                <!-- EMAIL -->
                <div>
                    <label class="block font-semibold mb-1">Email</label>
                    <input type="email" name="email"
                           class="w-full rounded-md border border-gray-300 px-4 py-2
                                  focus:ring-2 focus:ring-blue-400 outline-none"
                           required>
                </div>

                <!-- PASSWORD -->
                <div>
                    <label class="block font-semibold mb-1">Password</label>
                    <input type="password" name="password"
                           class="w-full rounded-md border border-gray-300 px-4 py-2
                                  focus:ring-2 focus:ring-blue-400 outline-none"
                           required>
                </div>

                <!-- CONFIRM PASSWORD -->
                <div>
                    <label class="block font-semibold mb-1">Confirm Password</label>
                    <input type="password" name="password_confirm"
                           class="w-full rounded-md border border-gray-300 px-4 py-2
                                  focus:ring-2 focus:ring-blue-400 outline-none"
                           required>
                </div>

                <!-- BUTTONS -->
                <div class="flex items-center justify-end gap-4 pt-3">
                    <a href="<?= url_to('login') ?>" class="text-blue-600 font-semibold hover:underline">
                        Log In
                    </a>

                    <button type="submit"
                            class="bg-blue-500 text-white px-8 py-2 rounded-md font-semibold shadow-md
                                   hover:bg-blue-600 transition">
                        Sign Up
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<?= $this->endSection() ?>
