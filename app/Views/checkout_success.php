<main class="w-full pt-24 flex-grow"> <div class="max-w-4xl mx-auto p-4 text-center">
        
        <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-8 rounded-lg relative shadow-md">
            
            <span class="inline-block bg-green-500 rounded-full p-3 mb-4">
                <svg class="w-10 h-10 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11.917 9.724 16.5 19 7.5"/></svg>
            </span>

            <h1 class="text-3xl font-bold text-green-900 mb-4">Pemesanan Berhasil!</h1>
            <p class="text-lg text-green-800 mb-2">
                Terima kasih, <?= esc($order['first_name']) ?>. Pesanan Anda telah dikonfirmasi.
            </p>
            <p class="text-lg text-green-800 mb-6">
                Nomor Pesanan Anda: <strong class="font-bold">#<?= esc($order['id']) ?></strong>
            </p>
            
            <p class="text-md text-gray-700 mb-6">
                E-tiket Anda telah dikirimkan ke email: 
                <strong class="font-bold"><?= esc($email) ?></strong>.
                <br>
                Silakan cek kotak masuk (atau folder spam) Anda.
            </p>
            <a href="/" class="inline-block bg-blue-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-blue-700 transition duration-300">
                Kembali ke Halaman Utama
            </a>
        </div>

    </div>
</main>