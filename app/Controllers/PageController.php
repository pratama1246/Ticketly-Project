<?php

namespace App\Controllers;

use App\Models\EventModel;

class PageController extends BaseController
{
    protected $eventModel;

    public function __construct()
    {
        $this->eventModel = new EventModel();
    }

    public function tentang()
    {
        echo view('layout/header', ['title' => 'Tentang Kami']);
        echo view('page/tentang');
        echo view('layout/footer');
    }

    // --- FUNGSI GENERIK UNTUK HALAMAN LISTING ---

    public function concerts()
    {
        $data = [
            'title' => 'Jadwal Konser Musik',
            'desc'  => 'Temukan konser artis favoritmu dan amankan tiketnya sekarang.',
            'events' => $this->eventModel->where('category', 'Concert')
                                         ->where('status', 'published')
                                         ->orderBy('event_date', 'ASC')
                                         ->findAll()
        ];
        return $this->renderListing($data);
    }

    public function festivals()
    {
        $data = [
            'title' => 'Festival Pilihan',
            'desc'  => 'Rasakan keseruan festival musik, seni, dan budaya terbaik.',
            'events' => $this->eventModel->where('category', 'Festival')
                                         ->where('status', 'published')
                                         ->orderBy('event_date', 'ASC')
                                         ->findAll()
        ];
        return $this->renderListing($data);
    }

    public function events()
    {
        $keyword = $this->request->getGet('q'); // Tangkap kata kunci dari URL
        
        $query = $this->eventModel->where('status', 'published');

        if (!empty($keyword)) {
            // --- MODE PENCARIAN ---
            $query->groupStart()
                  ->like('name', $keyword)       // Cari di Nama
                  ->orLike('venue', $keyword)    // Atau di Lokasi
                  ->orLike('category', $keyword) // Atau di Kategori
                  ->groupEnd();
            
            $title = 'Hasil Pencarian: "' . esc($keyword) . '"';
            $desc  = 'Menampilkan event yang cocok dengan kata kunci tersebut.';
        } else {
            // --- MODE LIHAT SEMUA ---
            // Menampilkan semua event (bukan cuma kategori 'Event')
            $title = 'Jelajahi Semua Event';
            $desc  = 'Temukan berbagai pengalaman seru mulai dari konser hingga pameran.';
        }

        $data = [
            'title'  => $title,
            'desc'   => $desc,
            'events' => $query->orderBy('event_date', 'ASC')->findAll()
        ];

        return $this->renderListing($data);
    }

    // Helper private biar gak nulis view berulang-ulang
    private function renderListing($data)
    {
        echo view('layout/header', ['title' => $data['title']]);
        echo view('page/listing', $data); // Kita pakai 1 file view untuk ramai-ramai
        echo view('layout/footer');
    }
}