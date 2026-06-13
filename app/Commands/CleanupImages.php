<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CleanupImages extends BaseCommand
{
    protected $group       = 'Ticketly';
    protected $name        = 'images:cleanup';
    protected $description = 'Compresses existing images in uploads/ to WebP, updates the database, and deletes orphaned files.';
    protected $usage       = 'images:cleanup';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        
        $bannersDir  = FCPATH . 'uploads/banners/';
        $profileDir  = FCPATH . 'uploads/profile/';
        $seatmapsDir = FCPATH . 'uploads/seatmaps/';

        // Ensure directories exist
        @mkdir($bannersDir, 0777, true);
        @mkdir($profileDir, 0777, true);
        @mkdir($seatmapsDir, 0777, true);

        CLI::write('--- Memulai Audit dan Pembersihan Gambar ---', 'green');

        // ----------------------------------------------------
        // Part 1: Process Event Images (Banners & Seatmaps)
        // ----------------------------------------------------
        CLI::write('Memproses gambar Event...');
        $events = $db->table('events')->select('id, name, poster_image, seatmap_image')->get()->getResultArray();
        
        $activeBanners = [];
        $activeSeatmaps = [];

        foreach ($events as $event) {
            CLI::write('Event: ' . $event['name']);
            
            // Poster Image
            if (!empty($event['poster_image'])) {
                $filename = basename($event['poster_image']);
                $fullPath = $bannersDir . $filename;
                
                if (file_exists($fullPath)) {
                    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                    if ($ext !== 'webp') {
                        $webpFilename = pathinfo($filename, PATHINFO_FILENAME) . '.webp';
                        $webpFullPath = $bannersDir . $webpFilename;
                        
                        CLI::write("  Mengompresi poster {$filename} ke WebP...", 'yellow');
                        if ($this->compressToWebP($fullPath, $webpFullPath, 80)) {
                            // Update database path
                            $db->table('events')->where('id', $event['id'])->update([
                                'poster_image' => 'uploads/banners/' . $webpFilename
                            ]);
                            unlink($fullPath);
                            CLI::write("  Sukses: Poster diperbarui ke {$webpFilename}", 'green');
                            $activeBanners[] = $webpFilename;
                        } else {
                            CLI::error("  Gagal mengompresi poster: {$filename}");
                            $activeBanners[] = $filename;
                        }
                    } else {
                        $activeBanners[] = $filename;
                    }
                }
            }

            // Seatmap Image
            if (!empty($event['seatmap_image'])) {
                $filename = basename($event['seatmap_image']);
                $fullPath = $seatmapsDir . $filename;
                
                if (file_exists($fullPath)) {
                    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                    if ($ext !== 'webp') {
                        $webpFilename = pathinfo($filename, PATHINFO_FILENAME) . '.webp';
                        $webpFullPath = $seatmapsDir . $webpFilename;
                        
                        CLI::write("  Mengompresi seatmap {$filename} ke WebP...", 'yellow');
                        if ($this->compressToWebP($fullPath, $webpFullPath, 85)) {
                            // Update database path
                            $db->table('events')->where('id', $event['id'])->update([
                                'seatmap_image' => 'uploads/seatmaps/' . $webpFilename
                            ]);
                            unlink($fullPath);
                            CLI::write("  Sukses: Seatmap diperbarui ke {$webpFilename}", 'green');
                            $activeSeatmaps[] = $webpFilename;
                        } else {
                            CLI::error("  Gagal mengompresi seatmap: {$filename}");
                            $activeSeatmaps[] = $filename;
                        }
                    } else {
                        $activeSeatmaps[] = $filename;
                    }
                }
            }
        }

        // ----------------------------------------------------
        // Part 2: Process User Profile Images
        // ----------------------------------------------------
        CLI::write('Memproses foto profil User...');
        $users = $db->table('users')->select('id, username, foto')->get()->getResultArray();
        
        $activeProfiles = [];

        foreach ($users as $user) {
            if (!empty($user['foto'])) {
                $filename = basename($user['foto']);
                $fullPath = $profileDir . $filename;
                
                if (file_exists($fullPath)) {
                    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                    if ($ext !== 'webp') {
                        $webpFilename = pathinfo($filename, PATHINFO_FILENAME) . '.webp';
                        $webpFullPath = $profileDir . $webpFilename;
                        
                        CLI::write("  Mengompresi foto profil {$filename} milik {$user['username']} ke WebP...", 'yellow');
                        if ($this->compressToWebP($fullPath, $webpFullPath, 80)) {
                            // Update database path
                            $db->table('users')->where('id', $user['id'])->update([
                                'foto' => $webpFilename
                            ]);
                            unlink($fullPath);
                            CLI::write("  Sukses: Foto profil diperbarui ke {$webpFilename}", 'green');
                            $activeProfiles[] = $webpFilename;
                        } else {
                            CLI::error("  Gagal mengompresi foto profil: {$filename}");
                            $activeProfiles[] = $filename;
                        }
                    } else {
                        $activeProfiles[] = $filename;
                    }
                } else {
                    CLI::write("  Peringatan: Berkas foto profil {$filename} milik {$user['username']} hilang dari server.", 'red');
                }
            }
        }

        // ----------------------------------------------------
        // Part 3: Clean up Orphaned Files
        // ----------------------------------------------------
        CLI::write('Membersihkan berkas tak terpakai (orphaned)...');
        
        // Clean Banners
        $this->cleanupFolder($bannersDir, $activeBanners, 'Banner');
        
        // Clean Seatmaps
        $this->cleanupFolder($seatmapsDir, $activeSeatmaps, 'Seatmap');
        
        // Clean Profiles
        $this->cleanupFolder($profileDir, $activeProfiles, 'Foto Profil');

        CLI::write('--- Audit dan Pembersihan Gambar Selesai ---', 'green');
    }

    private function cleanupFolder(string $dir, array $activeFiles, string $label)
    {
        if (!is_dir($dir)) return;

        $files = scandir($dir);
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') continue;
            
            if (!in_array($file, $activeFiles, true)) {
                $fullPath = $dir . $file;
                if (is_file($fullPath)) {
                    unlink($fullPath);
                    CLI::write("  Hapus berkas sampah [{$label}]: {$file}", 'red');
                }
            }
        }
    }

    private function compressToWebP(string $sourcePath, string $targetPath, int $quality = 80): bool
    {
        if (!file_exists($sourcePath)) {
            return false;
        }

        $info = getimagesize($sourcePath);
        if ($info === false) {
            return false;
        }

        $mime = $info['mime'];
        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                $image = imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $image = imagecreatefrompng($sourcePath);
                if ($image) {
                    imagepalettetotruecolor($image);
                    imagealphablending($image, true);
                    imagesavealpha($image, true);
                }
                break;
            case 'image/webp':
                return copy($sourcePath, $targetPath);
            default:
                return false;
        }

        if (!$image) {
            return false;
        }

        $result = imagewebp($image, $targetPath, $quality);
        imagedestroy($image);

        return $result;
    }
}
