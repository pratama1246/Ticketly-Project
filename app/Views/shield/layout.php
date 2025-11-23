<?php
/** layout_tailwind.php
 * Converted layout template using Tailwind utilities.
 * Place this file in app/Views/Shield/layout_tailwind.php (or your preferred path)
 * and update app/Config/Auth.php to point 'layout' => '\App\Views\Shield\layout_tailwind'
 */
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= esc($title ?? 'App') ?></title>
  <!-- Replace the href below with your compiled Tailwind CSS file -->
  <link href="<?= base_url('output.css') ?>" rel="stylesheet">
</head>
<body class="min-h-screen bg-gray-50 text-gray-800">
  <div class="min-h-screen flex flex-col">

    <main class="flex-1 max-w-4xl mx-auto px-4 py-10 w-full">
      <?= $this->renderSection('content') ?>
    </main>
  </div>
</body>
</html>