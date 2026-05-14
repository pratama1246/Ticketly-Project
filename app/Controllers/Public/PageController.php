<?php

namespace App\Controllers\Public;

use App\Controllers\BaseController;

use App\Models\EventModel;

class PageController extends BaseController
{
    protected $eventModel;

    public function __construct()
    {
        $this->eventModel = new EventModel();
    }

    // Halaman Tentang Kami
    public function tentang()
    {
        return view('page/tentang', ['title' => 'Tentang Kami']);
    }

    // Halaman Konser
    public function concerts()
    {
        $data = [
            'title'  => 'Jadwal Konser Musik',
            'desc'   => 'Temukan konser artis favoritmu dan amankan tiketnya sekarang.',
            'events' => $this->eventModel
                            ->where('category', 'Concert')
                            ->where('status', 'published')
                            ->orderBy('event_date', 'ASC')
                            ->findAll()
        ];
        return view('page/listing', $data);
    }

    // Halaman Festival
    public function festivals()
    {
        $data = [
            'title'  => 'Festival Pilihan',
            'desc'   => 'Rasakan keseruan festival musik, seni, dan budaya terbaik.',
            'events' => $this->eventModel
                            ->where('category', 'Festival')
                            ->where('status', 'published')
                            ->orderBy('event_date', 'ASC')
                            ->findAll()
        ];
        return view('page/listing', $data);
    }

    // Halaman Event dan Pencarian
    public function events()
    {
        $keyword = $this->request->getGet('q');

        $safeKeyword = trim((string) $keyword);

        $query = $this->eventModel->where('status', 'published');

        if (!empty($safeKeyword)) {
            $query->groupStart()
                  ->like('name', $safeKeyword)
                  ->orLike('venue', $safeKeyword)
                  ->orLike('category', $safeKeyword)
                  ->groupEnd();

            $title = 'Hasil Pencarian: "' . esc($safeKeyword) . '"';
            $desc  = 'Menampilkan event yang cocok dengan kata kunci tersebut.';
        } else {
            $query->where('category', 'event');
            $title   = 'Jelajahi Semua Event';
            $desc    = 'Temukan berbagai pengalaman seru mulai dari konser hingga pameran.';
        }

        $data = [
            'title'   => $title,
            'desc'    => $desc,
            'events'  => $query->orderBy('event_date', 'ASC')->findAll(),
            'keyword' => $safeKeyword
        ];

        return view('page/listing', $data);
    }
}