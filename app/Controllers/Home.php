<?php

namespace App\Controllers;

use App\Models\EventModel; 

class Home extends BaseController
{
    public function index()
    {
        $eventModel = new EventModel(); 

        $data['events'] = $eventModel->findAll(); 

        // 4. Kirim $data ke dalam view
        echo view('layout/header');
        echo view('layout/main', $data); 
        echo view('layout/footer');
    }
}