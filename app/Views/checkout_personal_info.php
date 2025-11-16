<main class="w-full pt-24">
    <div class="max-w-4xl mx-auto p-4">

        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-6 text-center" role="alert">
            <strong class="font-bold">Sisa waktu untuk memesan tiket: </strong>
            <span class="font-mono text-lg" id="checkout-timer">
                <?php
                    // Ambil sisa waktu dari session
                    $timeLeft = session('checkout_time_left') ?? 900;
                    echo floor($timeLeft / 60) . ':' . str_pad($timeLeft % 60, 2, '0', STR_PAD_LEFT);
                ?>
            </span>
        </div>

        <ol class="items-center w-full space-y-4 sm:flex sm:space-x-8 sm:space-y-0 rtl:space-x-reverse mb-8">
            <li class="flex items-center text-blue-600 space-x-3 rtl:space-x-reverse">
                <span class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-full lg:h-12 lg:w-12 shrink-0">
                    <svg class="w-5 h-5 text-blue-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11h2v5m-2 0h4m-2.592-8.592a.93.93 0 0 1 .93.93v.018a.93.93 0 0 1-.93.93H9.93a.93.93 0 0 1-.93-.93v-.018a.93.93 0 0 1 .93-.93h.592Z"/><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 10v1.5a3.5 3.5 0 0 1-3.5 3.5H12a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h1.5a3.5 3.5 0 0 1 3.5 3.5Z"/></svg>
                </span>
                <span>
                    <h3 class="font-medium leading-tight">Data Diri</h3>
                    <p class="text-sm">Isi info personal</p>
                </span>
            </li>
            <li class="flex items-center text-gray-500 space-x-3 rtl:space-x-reverse">
                <span class="flex items-center justify-center w-10 h-10 bg-gray-100 rounded-full lg:h-12 lg:w-12 shrink-0">
                    <svg class="w-5 h-5 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M6 14h2m3 0h5M3 7v10a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1Z"/></svg>
                </span>
                <span>
                    <h3 class="font-medium leading-tight">Pembayaran</h3>
                    <p class="text-sm">Pilih metode bayar</p>
                </span>
            </li>
            <li class="flex items-center text-gray-500 space-x-3 rtl:space-x-reverse">
                <span class="flex items-center justify-center w-10 h-10 bg-gray-100 rounded-full lg:h-12 lg:w-12 shrink-0">
                    <svg class="w-5 h-5 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11.917 9.724 16.5 19 7.5"/></svg>
                </span>
                <span>
                    <h3 class="font-medium leading-tight">Konfirmasi</h3>
                    <p class="text-sm">Review pesanan</p>
                </span>
            </li>
        </ol>

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

            <div class="mt-8 text-right">
                <button type="submit" class="bg-blue-600 text-white font-bold text-lg py-3 px-10 rounded-lg hover:bg-blue-700 transition duration-300">
                    Lanjut ke Pembayaran
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
                    // Waktu habis, reload halaman (Filter akan menangani redirect)
                    window.location.reload();
                }

                let minutes = Math.floor(totalSeconds / 60);
                let seconds = totalSeconds % 60;
                
                // Format: 05:00
                timerElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            }, 1000);
        }
    });
</script>