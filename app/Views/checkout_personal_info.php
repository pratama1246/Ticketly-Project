<main class="w-full grow mb-20">
    <div class="max-w-4xl mx-auto p-4">

        <!-- Form Data Diri -->
        <h2 class="text-2xl font-bold text-black mb-4">Informasi Personal</h2>
        
        <form action="/checkout/process_personal_info" method="POST">
            <?= csrf_field() ?>
                <div class="mb-4">
                    <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900">Nama Depan <span class="text-red-500">*</span></label>
                    <input type="text" id="first_name" name="first_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                </div>
                <div class="mb-4">
                    <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900">Nama Belakang</label>
                    <input type="text" id="last_name" name="last_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                </div>
                <div class="mb-4">
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email <span class="text-red-500">*</span></label>
                    <input type="email" id="email" name="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                </div>
                <div class="mb-4">
                    <label for="phone_number" class="block mb-2 text-sm font-medium text-gray-900">Nomor Telepon <span class="text-red-500">*</span></label>
                    <input type="tel" id="phone_number" name="phone_number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                </div>
                <div class="mb-4">
                    <label for="identity_number" class="block mb-2 text-sm font-medium text-gray-900">Nomor Identitas (KTP/SIM/NIK/Paspor, dll) <span class="text-red-500">*</span></label>
                    <input type="text" id="identity_number" name="identity_number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                </div>
                <div class="max-w-sm mb-4">
                    <label for="birth_date" class="block mb-2 text-sm font-medium text-gray-900">Tanggal Lahir <span class="text-red-500">*</span></label>
                    
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-body" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 10h16m-8-3V4M7 7V4m10 3V4M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Zm3-7h.H8V13Zm4 0h.01v.01H12V13Zm4 0h.01v.01H16V13Zm-8 4h.01v.01H8V17Zm4 0h.01v.01H12V17Zm4 0h.01v.01H16V17Z"/></svg>
                        </div>
                        <input id="birth_date" name="birth_date" 
                            type="text" 
                            class="block w-full ps-9 pe-3 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 px-3 shadow-sm placeholder:text-gray-400" 
                            placeholder="DD/MM/YYYY" required>
                    </div>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900 mb-2">
                        Saya setuju untuk menerima notifikasi terkait pemesanan tiket berikut melalui nomor WhatsApp saya.
                    </p>
                    <div class="flex items-center gap-6 mb-6">
                        <div class="flex items-center">
                            <input id="wa_yes" type="radio" value="yes" name="whatsapp_notif" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <label for="wa_yes" class="ms-2 text-sm font-medium text-gray-900">Iya</label>
                        </div>
                        <div class="flex items-center">
                            <input id="wa_no" type="radio" value="no" name="whatsapp_notif" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2" checked>
                            <label for="wa_no" class="ms-2 text-sm font-medium text-gray-900">Tidak</label>
                        </div>
                    </div>
                </div>

                <div class="flex items-start mb-6">
                    <div class="flex items-center h-5">
                        <input id="terms" name="terms" type="checkbox" value="1" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300" required>
                    </div>
                    <label for="terms" class="ms-2 text-sm font-medium text-gray-900">
                        Dengan mengklik “Lanjut”, kamu menyetujui <a href="#" class="text-blue-600 hover:underline font-bold">Syarat & Ketentuan</a> dan <a href="#" class="text-blue-600 hover:underline font-bold">Kebijakan Privasi</a> Ticketly.
                    </label>
                </div>

                <div class="flex items-start mb-4">
                    <div class="flex items-center h-5">
                        <input id="privacy_data" name="privacy_data" type="checkbox" value="1" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300" required>
                    </div>
                    <label for="privacy_data" class="ms-2 text-sm font-medium text-gray-900">
                        Dengan mengklik “Lanjut”, kamu menyetujui <a href="#" class="text-blue-600 hover:underline font-bold">Kebijakan Pemrosesan Data Pribadi</a> Ticketly.
                    </label>
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
</main>