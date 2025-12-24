<main class="min-h-screen bg-yellow-accent-light pt-32 pb-12 font-sans text-gray-900">
    
    <div class="max-w-lg mx-auto bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        
        <div class="h-32 bg-linear-to-r from-blue-500 to-blue-600 rounded-b-[50%]"></div>

        <div class="px-6 pb-8 text-center relative">
            <div class="relative -mt-16 mb-4 inline-block">
                <img src="<?= base_url($user->foto ? 'uploads/profile/' . $user->foto : 'assets/profile_default.png') ?>"
                     class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-md mx-auto bg-white" 
                     alt="Foto Profil">
            </div>

            <h2 class="text-2xl font-bold text-gray-900"><?= esc($user->username) ?></h2>
            <p class="text-gray-500 font-medium mb-6"><?= esc($user->email) ?></p>
            <div class="flex justify-center gap-4">
                <a href="/profile/edit" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-full font-medium transition-all shadow-md hover:shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    Edit Profil
                </a>
                <a href="/profile/history" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-full font-medium transition-all shadow-md hover:shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h11M9 21V3m0 0L5 7m4-4l4 4m6 13l-4-4m0 0l4-4m-4 4H6"></path></svg>
                    Riwayat Transaksi
                </a>
            </div>
        </div>
    </div>
</main>