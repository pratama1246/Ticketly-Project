<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\EventModel;
use App\Models\TicketTypeModel; // Pastikan ini ada

class EventSeeder extends Seeder
{
    public function run()
    {
        $model = new EventModel();
        $ticketModel = new TicketTypeModel(); // Pastikan ini ada
        $path = APPPATH . 'Database/Seeds/data/';

        // 1. Matikan Foreign Key Checks
        $this->db->disableForeignKeyChecks();

        // 2. Kosongkan tabel (tiket dulu, baru event)
        $ticketModel->truncate();
        $model->truncate();

        // 3. Nyalakan lagi Foreign Key Checks
        $this->db->enableForeignKeyChecks();

        // 4. Pindai SEMUA FOLDER di dalam 'data/'
        $eventFolders = glob($path . '*', GLOB_ONLYDIR);

        // 5. Looping setiap FOLDER event
        foreach ($eventFolders as $folder) {
            
            $jsonFile = $folder . '/data.json';
            $htmlFile = $folder . '/description.html';

            if (!file_exists($jsonFile) || !file_exists($htmlFile)) {
                echo "Melewatkan " . basename($folder) . ": file tidak lengkap.\n";
                continue;
            }

            // 7. Baca isi file JSON dan HTML
            $jsonString = file_get_contents($jsonFile);
            $data = json_decode($jsonString, true);
            $description = file_get_contents($htmlFile);

            // 8. Siapkan data event
            $insertData = [
                'name'          => $data['name'],
                'event_date'    => $data['event_date'],
                'venue'         => $data['venue'],
                'poster_image'  => $data['poster_image'],
                'seatmap_image' => $data['seatmap_image'],
                'description'   => $description
            ];

            // 9. Masukkan EVENT ke database
            $model->insert($insertData);
            
            // ======================================================
            //    BAGIAN BARU UNTUK MEMASUKKAN TIKET
            // ======================================================
            
            // 10. Dapatkan ID dari event yang BARU SAJA dimasukkan
            $newEventId = $model->insertID();

            // 11. Cek apakah data tiket ada di file JSON
            if (isset($data['ticket_types']) && is_array($data['ticket_types'])) {
                
                // 12. Loop semua data tiket
                foreach ($data['ticket_types'] as $ticket) {
                    // 13. Siapkan data tiket
                    $ticketData = [
                        'event_id'       => $newEventId, // <-- Sambungkan ke ID event
                        'name'           => $ticket['name'],
                        'price'          => $ticket['price'],
                        'quantity_total' => $ticket['quantity_total']
                    ];
                    
                    // 14. Masukkan TIKET ke database
                    $ticketModel->insert($ticketData);
                }
            }
            // ======================================================
            
            echo "Memasukkan event: " . $data['name'] . " (ID: $newEventId) dan tiket-tiketnya.\n";
        }
    }
}