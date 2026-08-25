# 🐳 Panduan Containerization Ticketly (Docker)

Dokumen ini berisi panduan lengkap untuk menjalankan, mengelola, dan menguji backend **Ticketly** menggunakan Docker & Docker Compose.

---

## 📌 Arsitektur Kontainer

Sistem Ticketly dijalankan menggunakan 3 layanan kontainer dalam jaringan bridge `ticketly_net`:

1. **`app` (`ticketly_app`)**: PHP 8.2-FPM Alpine yang telah dilengkapi seluruh ekstensi yang dibutuhkan:
   - `mysqli`, `pdo_mysql`, `gd`, `imagick`, `intl`, `zip`, `mbstring`, `opcache`, `bcmath`, `fileinfo`, `exif`, `xml`, `openssl`, `sodium`, `sockets`.
   - Composer 2.8 terpasang secara built-in.
2. **`web` (`ticketly_web`)**: Nginx Alpine sebagai reverse proxy & web server statis:
   - Di-mapping ke **Port `8080` Host** (`8080:80`).
   - Meneruskan header otentikasi JWT (`Authorization: Bearer <token>`) dan proxy IP ke PHP-FPM.
3. **`db` (`ticketly_db`)**: MySQL 8.4 Server:
   - Di-mapping ke **Port `3307` Host** (`3307:3306`) agar tidak bentrok dengan database lokal MySQL host.
   - Volume persisten data tersimpan pada `mysql_data`.
   - Inisialisasi awal otomatis memuat dump schema & data dari `ticketly_backup.sql`.

---

## 🚀 Cara Menjalankan

### 1. Build & Jalankan Kontainer di Background
```bash
docker compose up -d --build
```

### 2. Cek Status Kontainer
```bash
docker compose ps
```
Pastikan ketiga service (`ticketly_app`, `ticketly_web`, `ticketly_db`) berstatus `Up` (dan `ticketly_db` berstatus `healthy`).

### 3. Install Dependensi Composer (Jika Dibutuhkan di Volume)
```bash
docker compose exec app composer install --optimize-autoloader
```

### 4. Menjalankan Migrasi & Database Seeder (Opsional jika ingin reset data)
```bash
# Menjalankan migrasi database
docker compose exec app php spark migrate

# Menjalankan seeder data awal
docker compose exec app php spark db:seed PaymentMethodSeeder
docker compose exec app php spark db:seed EventSeeder
docker compose exec app php spark db:seed AdminUserSeeder
docker compose exec app php spark db:seed FakeUserSeeder
```

### 5. Menghentikan Kontainer
```bash
# Menghentikan tanpa menghapus data
docker compose down

# Menghentikan dan menghapus volume database (Reset total)
docker compose down -v
```

---

## 📱 Koneksi dengan Flutter Client (`ticketly`)

Aplikasi Flutter diatur untuk terhubung langsung ke backend yang berjalan di container port `8080`:

| Platform Flutter | Base URL yang Digunakan | Keterangan |
|---|---|---|
| **Flutter Web** | `http://localhost:8080` | Akses langsung ke localhost |
| **Android Emulator** | `http://10.0.2.2:8080` | IP alias emulator Android ke host machine |
| **HP Fisik (WiFi)** | `http://<IP_LAPTOP_ANDA>:8080` | Contoh: `http://192.168.18.3:8080` |

---

## 🧪 Verifikasi Endpoint & Fitur

Uji coba endpoint utama menggunakan `curl` atau browser:

```bash
# 1. Scalar API Docs
curl -I http://localhost:8080/api/docs

# 2. Landing Events API
curl -s http://localhost:8080/api/events/landing | head -n 30

# 3. Featured Events API
curl -s http://localhost:8080/api/events/featured

# 4. Payment Methods API
curl -s http://localhost:8080/api/checkout/payment-methods
```

---

## 🛠️ Perintah Berguna Lainnya

```bash
# Masuk ke terminal container app (PHP)
docker compose exec app bash

# Melihat live logs Nginx & PHP-FPM
docker compose logs -f

# Kompresi gambar uploads ke format WebP
docker compose exec app php spark images:cleanup
```
