<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Profil User' ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <?= csrf_meta() ?>
</head>
<body class="bg-gray-100">

    <?= $this->include('layout/header') ?>

    <main class="min-h-screen pt-20 pb-8">
        <?= $this->renderSection('content') ?>
    </main>

    <?= $this->include('layout/footer') ?>

</body>
</html>
