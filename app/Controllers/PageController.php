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

    // Fungsi Statis Halaman Tentang Kami
    public function tentang()
    {
        echo view('layout/header', ['title' => 'Tentang Kami']);
        echo view('page/tentang');
        echo view('layout/footer');
    }

    // Fungsi Statis Halaman Konser
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

    // Fungsi Statis Halaman Festival
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

    // Fungsi Statis Halaman Event Lainnya dan Pencarian
    public function events()
    {
        $keyword = $this->request->getGet('q');
        
        $query = $this->eventModel->where('status', 'published');

        if (!empty($keyword)) {
            $query->groupStart()
                  ->like('name', $keyword)       
                  ->orLike('venue', $keyword)
                  ->orLike('category', $keyword) 
                  ->groupEnd();
            
            $title = 'Hasil Pencarian: "' . esc($keyword) . '"';
            $desc  = 'Menampilkan event yang cocok dengan kata kunci tersebut.';
        } else {
            $query->where('category', 'event');
            $title = 'Jelajahi Semua Event';
            $desc  = 'Temukan berbagai pengalaman seru mulai dari konser hingga pameran.';
        }

        $data = [
            'title'  => $title,
            'desc'   => $desc,
            'events' => $query->orderBy('event_date', 'ASC')->findAll(),
            'keyword' => $keyword
        ];

        return $this->renderListing($data);
    }

    private function renderListing($data)
    {
        echo view('layout/header', ['title' => $data['title']]);
        echo view('page/listing', $data);
        echo view('layout/footer');
    }
}