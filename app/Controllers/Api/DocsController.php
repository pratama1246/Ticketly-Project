<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;

class DocsController extends BaseController
{
    /**
     * Menampilkan Halaman Dokumentasi API menggunakan Scalar
     * 
     * @return string|\CodeIgniter\HTTP\Response
     */
    public function index()
    {
        return view('api_docs');
    }
}
