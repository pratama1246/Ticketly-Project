<?= $this->extend('layout/profile_layout') ?>
<?= $this->section('content') ?>

<div class="max-w-xl mx-auto mt-28 p-6 bg-white shadow rounded-xl">

    <div class="text-center">
        <img src="<?= base_url($user->foto ? 'uploads/profile/' . $user->foto : 'assets/profile_default.png') ?>"
             class="w-16 h-16 rounded-full mx-auto mb-4">

        <h2 class="text-2xl font-bold"><?= $user->username ?></h2>
        <p class="text-gray-700"><?= $user->email ?></p>

        <a href="/profile/edit" class="mt-4 inline-block bg-blue-600 px-5 py-2 rounded text-white">
            Edit Profil
        </a>
    </div>

</div>

<?= $this->endSection() ?>
