<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\EventModel;

/**
 * @property \CodeIgniter\HTTP\IncomingRequest $request
 */
class EventController extends BaseController
{
    /**
     * 1. READ: Menampilkan daftar semua event
     */
    public function index()
    {
        $eventModel = new EventModel();
        
        $data = [
            'title'  => 'Manajemen Event',
            'events' => $eventModel->findAll()
        ];

        echo view('admin/layout/header', $data);
        echo view('admin/events/index', $data);
        echo view('admin/layout/footer');
    }

    /**
     * 2. CREATE (Form): Menampilkan form tambah event
     */
    public function new()
    {
        $data = [
            'title' => 'Tambah Event Baru',
            'validation' => \Config\Services::validation() 
        ];

        echo view('admin/layout/header', $data);
        echo view('admin/events/new', $data);
        echo view('admin/layout/footer');
    }

    /**
     * 3. CREATE (Process): Menyimpan data event baru
     */
    public function create()
    {
        // Aturan Validasi
        $rules = [
            'name'        => 'required|string|max_length[255]',
            'event_date'  => 'required|valid_date',
            'venue'       => 'permit_empty|string|max_length[255]',
            'description' => 'permit_empty|string',
            'poster_image' => [
                'label' => 'Poster Image',
                'rules' => 'uploaded[poster_image]' // Wajib upload di awal
                    . '|is_image[poster_image]'
                    . '|mime_in[poster_image,image/jpg,image/jpeg,image/png,image/webp]'
                    . '|max_size[poster_image,2048]',
            ],
            'seatmap_image' => [
                'label' => 'Seatmap Image',
                'rules' => 'permit_empty|uploaded[seatmap_image]'
                    . '|is_image[seatmap_image]'
                    . '|mime_in[seatmap_image,image/jpg,image/jpeg,image/png,image/webp]'
                    . '|max_size[seatmap_image,2048]',
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Siapkan Data Teks
        $data = [
            'name'        => $this->request->getPost('name'),
            'event_date'  => $this->request->getPost('event_date'),
            'venue'       => $this->request->getPost('venue'),
            'description' => $this->request->getPost('description'),
        ];

        // Upload Poster (Ke folder 'banners' sesuai strukturmu)
        $posterFile = $this->request->getFile('poster_image');
        if ($posterFile->isValid() && ! $posterFile->hasMoved()) {
            $newName = $posterFile->getRandomName();
            $posterFile->move(FCPATH . 'uploads/banners', $newName);
            $data['poster_image'] = 'uploads/banners/' . $newName;
        }

        // Upload Seatmap
        $seatmapFile = $this->request->getFile('seatmap_image');
        if ($seatmapFile && $seatmapFile->isValid() && ! $seatmapFile->hasMoved()) {
            $newName = $seatmapFile->getRandomName();
            $seatmapFile->move(FCPATH . 'uploads/seatmaps', $newName);
            $data['seatmap_image'] = 'uploads/seatmaps/' . $newName;
        }

        // Simpan
        $eventModel = new EventModel();
        if ($eventModel->insert($data)) {
            return redirect()->to('/admin/events')->with('message', 'Event baru berhasil ditambahkan.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan event.');
        }
    }

    /**
     * 4. UPDATE (Form): Menampilkan form edit
     * (Otomatis dipanggil oleh rute /admin/events/edit/[id])
     */
    public function edit($id = null)
    {
        $eventModel = new EventModel();
        
        if ($id === null) {
            return redirect()->to('/admin/events')->with('error', 'ID tidak valid.');
        }

        $event = $eventModel->find($id);

        if (!$event) {
            return redirect()->to('/admin/events')->with('error', 'Event tidak ditemukan.');
        }

        $data = [
            'title'      => 'Edit Event: ' . $event['name'],
            'event'      => $event,
            'validation' => \Config\Services::validation()
        ];

        echo view('admin/layout/header', $data);
        echo view('admin/events/edit', $data); 
        echo view('admin/layout/footer');
    }

    /**
     * 5. UPDATE (Process): Menyimpan perubahan
     */
    public function update($id = null)
    {
        $eventModel = new EventModel();
        $existingEvent = $eventModel->find($id);

        if (!$existingEvent) {
            return redirect()->to('/admin/events')->with('error', 'Event tidak ditemukan.');
        }

        // Validasi (Gambar jadi opsional / permit_empty)
        $rules = [
            'name'        => 'required|string|max_length[255]',
            'event_date'  => 'required|valid_date',
            'venue'       => 'permit_empty|string|max_length[255]',
            'description' => 'permit_empty|string',
            'poster_image' => [
                'label' => 'Poster Image',
                'rules' => 'permit_empty|uploaded[poster_image]' 
                    . '|is_image[poster_image]'
                    . '|mime_in[poster_image,image/jpg,image/jpeg,image/png,image/webp]'
                    . '|max_size[poster_image,2048]',
            ],
            'seatmap_image' => [
                'label' => 'Seatmap Image',
                'rules' => 'permit_empty|uploaded[seatmap_image]' 
                    . '|is_image[seatmap_image]'
                    . '|mime_in[seatmap_image,image/jpg,image/jpeg,image/png,image/webp]'
                    . '|max_size[seatmap_image,2048]',
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Data Teks
        $data = [
            'name'        => $this->request->getPost('name'),
            'event_date'  => $this->request->getPost('event_date'),
            'venue'       => $this->request->getPost('venue'),
            'description' => $this->request->getPost('description'),
        ];

        // Cek Upload Poster Baru
        $posterFile = $this->request->getFile('poster_image');
        if ($posterFile && $posterFile->isValid() && ! $posterFile->hasMoved()) {
            // Hapus file lama
            if (!empty($existingEvent['poster_image']) && file_exists(FCPATH . $existingEvent['poster_image'])) {
                unlink(FCPATH . $existingEvent['poster_image']);
            }
            // Upload baru
            $newName = $posterFile->getRandomName();
            $posterFile->move(FCPATH . 'uploads/banners', $newName);
            $data['poster_image'] = 'uploads/banners/' . $newName;
        }

        // Cek Upload Seatmap Baru
        $seatmapFile = $this->request->getFile('seatmap_image');
        if ($seatmapFile && $seatmapFile->isValid() && ! $seatmapFile->hasMoved()) {
            // Hapus file lama
            if (!empty($existingEvent['seatmap_image']) && file_exists(FCPATH . $existingEvent['seatmap_image'])) {
                unlink(FCPATH . $existingEvent['seatmap_image']);
            }
            // Upload baru
            $newName = $seatmapFile->getRandomName();
            $seatmapFile->move(FCPATH . 'uploads/seatmaps', $newName);
            $data['seatmap_image'] = 'uploads/seatmaps/' . $newName;
        }

        // Update DB
        if ($eventModel->update($id, $data)) {
            return redirect()->to('/admin/events')->with('message', 'Event berhasil diperbarui.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui event.');
        }
    }

    /**
     * 6. DELETE: Menghapus event dan gambarnya
     */
    public function delete($id = null)
    {
        $eventModel = new EventModel();
        $event = $eventModel->find($id);

        if ($event) {
            // 1. Hapus File Poster (jika ada)
            if (!empty($event['poster_image']) && file_exists(FCPATH . $event['poster_image'])) {
                unlink(FCPATH . $event['poster_image']);
            }

            // 2. Hapus File Seatmap (jika ada)
            if (!empty($event['seatmap_image']) && file_exists(FCPATH . $event['seatmap_image'])) {
                unlink(FCPATH . $event['seatmap_image']);
            }

            // 3. Hapus Data dari Database
            $eventModel->delete($id);

            return redirect()->to('/admin/events')->with('message', 'Event berhasil dihapus.');
        }

        return redirect()->to('/admin/events')->with('error', 'Event tidak ditemukan.');
    }
    
    /**
     * Helper: Redirect /admin/events/1 ke edit
     */
    public function show($id = null)
    {
        return redirect()->to('/admin/events/edit/' . $id);
    }
}