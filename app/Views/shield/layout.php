<?php
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ticketly | Masuk atau Daftar</title>
    <link href="<?= base_url('output.css') ?>" rel="stylesheet">
    <link rel="icon" href="<?= base_url('assets/favicon.png') ?>" type="image/png">
    <script src="<?= base_url('js/app.js') ?>" defer></script>
</head>

<body class="min-h-screen bg-yellow-bright-light text-gray-800">
  <div class="min-h-screen flex flex-col">

    <main class="flex-1 max-w-4xl mx-auto px-4 py-10 w-full">
        <?= $this->renderSection('content') ?>
    </main>

  </div>
</body>

</html>