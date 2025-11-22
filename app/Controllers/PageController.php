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
        $data = [
            'title' => 'Event Seru Lainnya',
            'desc'  => 'Workshop, Pameran, Theater dan acara menarik lainnya.',
            'events' => $this->eventModel->where('category', 'Event') // Atau 'Other' sesuaikan DB
                                         ->where('status', 'published')
                                         ->orderBy('event_date', 'ASC')
                                         ->findAll()
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