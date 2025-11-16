<main class="w-full pt-24">
    <div class="max-w-4xl mx-auto p-4">
        <div class="p-6 md:p-10 rounded-lg border border-solid-black">

        <!-- Progress Bar Checkout -->
        <ol class="flex items-center w-full text-sm font-medium text-center text-body sm:text-base mb-8">
            <li class="flex md:w-full items-center text-fg-brand sm:after:content-[''] after:w-full after:h-1 after:border-b after:border-default after:border-px after:hidden sm:after:inline-block after:mx-6 xl:after:mx-10">
                <span class="flex items-center after:content-['/'] sm:after:hidden after:mx-2 after:text-fg-disabled">
                    <span class="me-2">1</span> Data <span class="hidden sm:inline-flex sm:ms-2">Diri</span>
                </span>
            </li>
            <li class="flex md:w-full items-center after:content-[''] after:w-full after:h-1 after:border-b after:border-default after:border-px after:hidden sm:after:inline-block after:mx-6 xl:after:mx-10">
                <span class="flex items-center after:content-['/'] sm:after:hidden after:mx-2 after:text-fg-disabled">
                    <span class="me-2">2</span>
                    Pembayaran <span class="hidden sm:inline-flex sm:ms-2"></span>
                </span>
            </li>
            <li class="flex items-center">
                <span class="me-2">3</span>
                Konfirmasi
            </li>
        </ol>

        <!-- Timer Sisa Waktu -->
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-6 text-center" role="alert">
            <strong class="font-bold">Sisa waktu untuk memesan tiket: </strong>
            <span class="font-mono text-lg" id="checkout-timer">
                <?php
                    // Ambil sisa waktu dari session
                    $timeLeft = session('checkout_time_left') ?? 300;
                    echo floor($timeLeft / 60) . ':' . str_pad($timeLeft % 60, 2, '0', STR_PAD_LEFT);
                ?>
            </span>
        </div>

        <!-- Form Data Diri -->
        <h2 class="text-2xl font-bold text-black mb-4">Informasi Personal</h2>
        
        <form action="/checkout/process_personal_info" method="POST">
            <?= csrf_field() ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900">Nama Depan *</label>
                    <input type="text" id="first_name" name="first_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                </div>
                <div>
                    <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900">Nama Belakang</label>
                    <input type="text" id="last_name" name="last_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                </div>
                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email *</label>
                    <input type="email" id="email" name="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                </div>
                <div>
                    <label for="phone_number" class="block mb-2 text-sm font-medium text-gray-900">Nomor Telepon *</label>
                    <input type="tel" id="phone_number" name="phone_number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                </div>
                <div class="md:col-span-2">
                    <label for="identity_number" class="block mb-2 text-sm font-medium text-gray-900">Nomor Identitas (KTP/SIM/NIK/Paspor, dll) *</label>
                    <input type="text" id="identity_number" name="identity_number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                </div>
                <div>
                    <label for="birth_date" class="block mb-2 text-sm font-medium text-gray-900">Tanggal Lahir</label>
                    <input type="date" id="birth_date" name="birth_date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                </div>
            </div>

            <!-- Tombol Lanjut ke Pembayaran -->
            <div class="mt-8 text-right">
                <button type="button" onclick="window.location.href='<?=  base_url('/checkout/cancel') ?>'" class="text-white bg-danger box-border border border-transparent hover:bg-danger-strong focus:ring-4 focus:ring-danger-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
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