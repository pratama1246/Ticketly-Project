<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\EventModel;

/**
 * @property \CodeIgniter\HTTP\IncomingRequest $request
 */
class EventController extends BaseController
{
    
    // Menampilkan daftar semua event
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

    // Menerima $slug (string) event
    public function detail($slug = null)
    {
        $eventModel = new EventModel();
        
        // Cari berdasarkan kolom 'slug'
        $event = $eventModel->where('slug', $slug)->first();

        if (!$event) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

    }

    // 2. Menampilkan form tambah event
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

    // 3. Memproses penyimpanan event baru
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
                'rules' => 'uploaded[poster_image]'
                    . '|is_image[poster_image]'
                    . '|mime_in[poster_image,image/jpg,image/jpeg,image/png,image/webp]'
                    . '|max_size[poster_image,2048]',
            ],
            'category' => 'required|in_list[concert,festival,event]',
            'status'   => 'required|in_list[draft,published]',
            'seatmap_image' => [
                'label' => 'Seatmap Image',
                'rules' => 'permit_empty|is_image[seatmap_image]'
                    . '|mime_in[seatmap_image,image/jpg,image/jpeg,image/png,image/webp]'
                    . '|max_size[seatmap_image,2048]',
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $eventModel = new EventModel();
        $slug = url_title($this->request->getPost('name'), '-', true);
        
        if ($eventModel->where('slug', $slug)->countAllResults() > 0) {
            $slug .= '-' . rand(1000, 9999);
        }

        $data = [
            'name'        => $this->request->getPost('name'),
            'slug'        => $slug,
            'event_date'  => $this->request->getPost('event_date'),
            'venue'       => $this->request->getPost('venue'),
            'description' => $this->request->getPost('description'),
            'seatmap_image' => null,
            'category'    => $this->request->getPost('category'),
            'status'      => $this->request->getPost('status'),
            'is_featured' => $this->request->getPost('is_featured') ? 1 : 0,
            'sort_order'  => $this->request->getPost('sort_order'),
        ];
        
        // Upload Poster
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

    // 4. Menampilkan form edit
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

    // 5. Menyimpan perubahan
    public function update($id = null)
    {
        $eventModel = new EventModel();
        $existingEvent = $eventModel->find($id);

        if (!$existingEvent) {
            return redirect()->to('/admin/events')->with('error', 'Event tidak ditemukan.');
        }

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
            'category' => 'required|in_list[concert,festival,event]',
            'status'   => 'required|in_list[draft,published]',
            'seatmap_image' => [
                'label' => 'Seatmap Image',
                'rules' => 'permit_empty|is_image[seatmap_image]'
                    . '|mime_in[seatmap_image,image/jpg,image/jpeg,image/png,image/webp]'
                    . '|max_size[seatmap_image,2048]',
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $slug = url_title($this->request->getPost('name'), '-', true);

        if ($eventModel->where('slug', $slug)->where('id !=', $id)->countAllResults() > 0) {
            $slug .= '-' . rand(1000, 9999);
        }

        $data = [
            'name'        => $this->request->getPost('name'),
            'slug'        => $slug,
            'event_date'  => $this->request->getPost('event_date'),
            'venue'       => $this->request->getPost('venue'),
            'description' => $this->request->getPost('description'),
            
            'category'    => $this->request->getPost('category'),
            'status'      => $this->request->getPost('status'),
            'is_featured' => $this->request->getPost('is_featured') ? 1 : 0,
            'sort_order'  => $this->request->getPost('sort_order'),
        ];

        // Cek Upload Poster Baru
        $posterFile = $this->request->getFile('poster_image');
        if ($posterFile && $posterFile->isValid() && ! $posterFile->hasMoved()) {
            if (!empty($existingEvent['poster_image']) && file_exists(FCPATH . $existingEvent['poster_image'])) {
                unlink(FCPATH . $existingEvent['poster_image']);
            }
            $newName = $posterFile->getRandomName();
            $posterFile->move(FCPATH . 'uploads/banners', $newName);
            $data['poster_image'] = 'uploads/banners/' . $newName;
        }

        // Cek Upload Seatmap Baru
        $seatmapFile = $this->request->getFile('seatmap_image');
        if ($seatmapFile && $seatmapFile->isValid() && ! $seatmapFile->hasMoved()) {
            if (!empty($existingEvent['seatmap_image']) && file_exists(FCPATH . $existingEvent['seatmap_image'])) {
                unlink(FCPATH . $existingEvent['seatmap_image']);
            }
            $newName = $seatmapFile->getRandomName();
            $seatmapFile->move(FCPATH . 'uploads/seatmaps', $newName);
            $data['seatmap_image'] = 'uploads/seatmaps/' . $newName;
        }

        // Update Database
        if ($eventModel->update($id, $data)) {
            return redirect()->to('/admin/events')->with('message', 'Event berhasil diperbarui.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui event.');
        }
    }

    // 6. Menghapus event dan gambarnya
    public function delete($id = null)
    {
        $eventModel = new EventModel();
        $event = $eventModel->find($id);

        if ($event) {
            if (!empty($event['poster_image']) && file_exists(FCPATH . $event['poster_image'])) {
                unlink(FCPATH . $event['poster_image']);
            }
            if (!empty($event['seatmap_image']) && file_exists(FCPATH . $event['seatmap_image'])) {
                unlink(FCPATH . $event['seatmap_image']);
            }

            $eventModel->delete($id);

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Event berhasil dihapus.'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Event tidak ditemukan.'
        ])->setStatusCode(404);
    }
    
    public function show($id = null)
    {
        return redirect()->to('/admin/events/edit/' . $id);
    }
}