<?php

// app/Language/id/Validation.php

return [
    // Core Rules
    'required'      => 'Kolom {field} wajib diisi.',
    'matches'       => 'Kolom {field} tidak cocok dengan {param}.',
    'min_length'    => 'Kolom {field} minimal {param} karakter.',
    'max_length'    => 'Kolom {field} maksimal {param} karakter.',
    'valid_email'   => 'Format email tidak valid.',
    'is_unique'     => '{field} ini sudah terdaftar. Gunakan yang lain.',
    
    // Upload Rules (Bahasa Indonesia)
    'uploaded'      => 'Kamu belum memilih file untuk diupload.',
    'max_size'      => 'Ukuran file terlalu besar! Maksimal: {param}.',
    'is_image'      => 'File yang diupload bukan gambar yang valid.',
    'mime_in'       => 'Tipe file tidak diizinkan. Harap upload gambar (JPG/PNG/WEBP).',
    'ext_in'        => 'Ekstensi file tidak valid.',
];