<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

/**
 * Custom Serve Command
 *
 * Override command `serve` bawaan CI4 agar otomatis menggunakan
 * public/router.php sebagai router PHP built-in server.
 *
 * router.php menambahkan CORS header ke semua response (termasuk
 * file statis di /uploads/) sehingga Flutter Web di Chrome bisa
 * load gambar tanpa diblokir CORS.
 *
 * Penggunaan tetap sama seperti biasa:
 *   php spark serve
 *   php spark serve --port=8080
 *   php spark serve --host=0.0.0.0
 */
class Serve extends BaseCommand
{
    protected $group       = 'CodeIgniter';
    protected $name        = 'serve';
    protected $description = 'Launches the CI4 development server with CORS support for Flutter Web.';
    protected $usage       = 'serve [--host] [--port] [--php]';
    protected $options     = [
        '--host'    => 'The HTTP host to serve the app on. Default: localhost',
        '--port'    => 'The HTTP port to serve the app on. Default: 8080',
        '--php'     => 'The PHP binary to use. Default: PHP_BINARY',
        '--docroot' => 'The document root. Default: public/',
    ];

    public function run(array $params)
    {
        $host    = CLI::getOption('host')    ?? 'localhost';
        $port    = (int) (CLI::getOption('port') ?? 8080);
        $php     = CLI::getOption('php')     ?? PHP_BINARY;
        $docroot = realpath(CLI::getOption('docroot') ?? FCPATH);
        $router  = realpath(FCPATH . 'router.php');

        if (! $router) {
            CLI::error('router.php tidak ditemukan di folder public/.');
            CLI::error('Pastikan file public/router.php sudah ada.');
            return EXIT_ERROR;
        }

        CLI::write('CodeIgniter development server (+ CORS support for Flutter Web)', 'green');
        CLI::write('Document root : ' . $docroot, 'yellow');
        CLI::write('Server        : http://' . $host . ':' . $port, 'yellow');
        CLI::write('Press Control-C to stop.', 'yellow');
        CLI::newLine();

        // Jalankan PHP built-in server dengan router.php kita
        // agar CORS header otomatis di-inject ke semua response
        passthru(
            escapeshellarg($php)
            . ' -S ' . escapeshellarg($host . ':' . $port)
            . ' -t ' . escapeshellarg($docroot)
            . ' '    . escapeshellarg($router)
        );
    }
}
