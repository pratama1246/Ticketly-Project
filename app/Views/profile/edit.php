<main class="min-h-screen bg-gray-50 pt-32 pb-12 font-sans text-gray-900">

    <div class="max-w-xl mx-auto bg-white rounded-2xl shadow-lg border border-gray-100 p-8">

        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-gray-800">Edit Profil</h2>
            <a href="/profile" class="text-sm text-gray-500 hover:text-gray-700">&larr; Kembali</a>
        </div>

        <form action="/profile/update" method="post" enctype="multipart/form-data" class="space-y-6">
            <?= csrf_field() ?>

            <div class="flex items-center gap-5 p-4 bg-gray-50 rounded-xl border border-gray-100">
                <img src="<?= base_url($user->foto ? 'uploads/profile/' . $user->foto : 'assets/profile_default.png') ?>"
                     class="w-16 h-16 object-cover rounded-full border-2 border-white shadow-sm">
                
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ganti Foto</label>
                    <input type="file" name="foto" class="block w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-semibold
                        file:bg-blue-50 file:text-blue-700
                        hover:file:bg-blue-100 cursor-pointer">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <input type="text" name="username" value="<?= esc($user->username) ?>" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
                <input type="email" name="email" value="<?= esc($user->email) ?>" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="/profile" class="px-5 py-2.5 rounded-lg text-gray-600 hover:bg-gray-100 font-medium transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium shadow-md transition-all">
                    Simpan Perubahan
                </button>
            </div>

        </form>

    </div>

</main>