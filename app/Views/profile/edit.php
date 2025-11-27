<?= $this->extend('layout/profile_layout') ?>
<?= $this->section('content') ?>

<div class="max-w-2xl mx-auto mt-28 p-6 bg-white shadow rounded-xl">

    <h2 class="text-xl font-bold mb-6">Edit Profil</h2>

    <form action="/profile/update" method="post" enctype="multipart/form-data" class="space-y-6">
        <?= csrf_field() ?>

        <!-- Foto Profil -->
        <div class="flex items-center gap-4">
            <img src="<?= base_url($user->foto ? 'uploads/profile/' . $user->foto : 'assets/profile_default.png') ?>"
                class="w-16 h-16 object-cover rounded-full border">

            <div class="flex-1">
                <label class="block mb-1 font-medium">Foto Profil</label>
                <input type="file" name="foto"
                    class="block w-full border border-gray-300 rounded-lg p-2">
            </div>
        </div>

        <!-- Username -->
        <div>
            <label class="block mb-1 font-medium">Username</label>
            <input type="text" name="username"
                value="<?= $user->username ?>"
                class="block w-full border border-gray-300 rounded-lg p-3 focus:ring focus:ring-blue-200">
        </div>

        <!-- Email -->
        <div>
            <label class="block mb-1 font-medium">Email</label>
            <input type="email" name="email"
                value="<?= $user->email ?>"
                class="block w-full border border-gray-300 rounded-lg p-3 focus:ring focus:ring-blue-200">
        </div>

        <!-- Tombol -->
        <div class="flex gap-3 pt-4">
            <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                Simpan
            </button>
            <a href="/profile"
                class="px-6 py-2 rounded-lg border border-gray-400 hover:bg-gray-50 transition">
                Batal
            </a>
        </div>

    </form>

</div>

<?= $this->endSection() ?>