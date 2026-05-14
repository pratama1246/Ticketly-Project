<?php

namespace App\Controllers\Public;

use App\Controllers\BaseController;

use App\Models\EventModel;

class Home extends BaseController
{
    public function index()
    {
        $eventModel = new EventModel();

        $featuredEvents = $eventModel
            ->where('is_featured', 1)
            ->where('status', 'published')
            ->orderBy("CASE WHEN sort_order = 0 THEN 9999 ELSE sort_order END", "ASC", false)
            ->orderBy('created_at', 'DESC')
            ->findAll();

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
            'title'     => 'Nikmati Semua Event',
            'featured'  => $featuredEvents,
            'concerts'  => $concerts,
            'festivals' => $festivals,
            'events'    => $otherEvents,
        ];

        return view('layout/main', $data);
    }
}