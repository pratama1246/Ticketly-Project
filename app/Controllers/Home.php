<?php

namespace App\Controllers;

use App\Models\EventModel;

class Home extends BaseController
{
    public function index()
    {
        $eventModel = new EventModel();

        // 1. Ambil Event untuk Carousel (Featured & Published)
        $featuredEvents = $eventModel
            ->where('is_featured', 1)
            ->where('status', 'published')
            ->orderBy("CASE WHEN sort_order = 0 THEN 9999 ELSE sort_order END", "ASC", false)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        // 2. Ambil Event per Kategori
        $concerts = $eventModel
            ->where('category', 'concert')
            ->where('status', 'published')
            ->orderBy('event_date', 'ASC')
            ->findAll(4);

        $festivals = $eventModel
            ->where('category', 'festival')
            ->where('status', 'published')
            ->orderBy('event_date', 'ASC')
            ->findAll(4);

        $otherEvents = $eventModel
            ->where('category', 'event')
            ->where('status', 'published')
            ->orderBy('event_date', 'ASC')
            ->findAll(4);

        $data = [
            'title'     => 'Home',
            'featured'  => $featuredEvents,
            'concerts'  => $concerts,
            'festivals' => $festivals,
            'events'    => $otherEvents,
        ];

        return view('layout/header', $data)
             . view('layout/main', $data)
             . view('layout/footer');
    }
}