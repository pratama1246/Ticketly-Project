# 🎟️ Ticketly (PNC)

**Ticketly** is a **CodeIgniter 4 (PHP 8.1+)** event ticketing platform, designed for internal use at **Politeknik Negeri Cilacap**.

It supports 3 main roles / integration paths:

- **User (Web)**: browse and view available events, purchase tickets with quantity selection, view booking history and ticket status, and manage profile.
- **Admin (Web Dashboard)**: manage events (create, edit, delete), monitor ticket sales and quotas, view/manage transaction records, and monitor dashboard statistics.
- **Mobile API (Flutter Integration)**: custom JWT-based authentication, retrieve events/tickets, calculate cart checkout in real-time, and manage booking status.

> The application exposes a custom JWT RESTful API endpoint to receive requests from the mobile Flutter client.
> Built as a college project at Politeknik Negeri Cilacap, Informatics Engineering Department.

---

[![CodeIgniter](https://img.shields.io/badge/CodeIgniter-4-EE4326?style=for-the-badge&logo=codeigniter&logoColor=white)](https://codeigniter.com)
[![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-v3-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![Flowbite](https://img.shields.io/badge/Flowbite-v1.6-3F83F8?style=for-the-badge&logo=flowbite&logoColor=white)](https://flowbite.com)
[![Figma](https://img.shields.io/badge/Figma-F24E1E?style=for-the-badge&logo=figma&logoColor=white)](https://figma.com)

---

## Table of Contents

- [Key Features](#key-features)
- [Tech Stack](#tech-stack)
- [Requirements](#requirements)
- [Local Setup](#local-setup)
- [Environment Configuration (.env)](#environment-configuration-env)
- [Database Structure](#database-structure)
- [REST API Endpoints](#rest-api-endpoints)
- [Run the App](#run-the-app)
- [UI/UX Design](#uiux-design)
- [Team](#team)
- [License](#license)
- [Disclaimer](#disclaimer)

---

## Key Features

### Authentication & Profile

- Register kustom untuk user & admin
- Login & logout
- Edit profil user & update data kustom
- Autentikasi berbasis session untuk Web (Shield) & token JWT kustom untuk Mobile API

### User (Web)

- Halaman jelajah event konser/pertunjukan yang tersedia
- Pembelian tiket dengan seleksi jumlah tiket kustom
- Riwayat pemesanan & detail transaksi kustom
- Status pemesanan otomatis

### Admin (Web Dashboard)

- Dashboard statistik penjualan tiket
- Event management (CRUD)
- Monitor penjualan tiket & kuota secara real-time
- Kelola dan lihat data transaksi tiket masuk

### Mobile API (Flutter Integration)

- Registrasi & login (memperoleh JWT kustom)
- Home banner/landing event & featured events
- Informasi detail event, kategori tiket, & sisa kuota tiket
- Kalkulasi keranjang belanja (cart) real-time
- Transaksi pemesanan (start checkout, upload bukti bayar/confirm, cancel booking)

---

## Tech Stack

**Backend**

- PHP `^8.1`
- CodeIgniter Framework `^4.0`
- CodeIgniter Shield: `codeigniter4/shield`
- JWT Auth: `firebase/php-jwt`
- PDF Generator: `dompdf/dompdf`
- QR Code Generator: `simplesoftwareio/simple-qrcode`

**Frontend**

- HTML & Vanilla JavaScript
- Tailwind CSS & Flowbite
- Blade-style CodeIgniter views (layouts & sections)
- Figma (UI/UX design prototype)

---

## Requirements

- PHP **8.1+**
- Composer
- Web server (Apache/Nginx) atau built-in PHP development server
- MySQL

---

## Local Setup

```bash
# 1) Clone repository
git clone https://github.com/pratama1246/ticketly-project.git
cd ticketly-project

# 2) Install dependencies
composer install

# 3) Setup environment file
cp env .env

# 4) Configure your .env (DB, Base URL, JWT_SECRET_KEY, dll.)

# 5) Import database
# Buat database MySQL dengan nama 'ticketly' terlebih dahulu, kemudian jalankan:
mysql -u root -p ticketly < ticketly.sql
```

---

## Environment Configuration (.env)

### Application

```env
CI_ENVIRONMENT=development
app.baseURL='http://localhost:8080/'
```

### Database

```env
database.default.hostname=localhost
database.default.database=ticketly
database.default.username=root
database.default.password=
database.default.DBDriver=MySQLi
```

### Encryption & API Authentication

```env
JWT_SECRET_KEY=your_secret_key_here
```

### Email Settings

```env
email.fromName='Ticketly System'
email.fromEmail='noreply@ticketly.mytamakikii.web.id'
email.protocol='smtp'
email.SMTPHost='live.smtp.mailtrap.io'
email.SMTPUser='api'
email.SMTPPass='c23f6d13c308dccbd61a0d6fb1e5cd72'
email.SMTPPort=2525
email.SMTPCrypto='tls'
email.mailType='html'
```

---

## Database Structure

Struktur database lengkap beserta data seed awal tersedia di file `ticketly.sql`. Impor langsung ke server MySQL Anda untuk memperoleh database yang siap digunakan.

Proyek ini menggunakan tabel-tabel utama seperti:

- `users` & `auth_groups_users` (manajemen user & role admin/user)
- `events` (data event)
- `tickets` (tipe dan kuota tiket per event)
- `orders` (riwayat transaksi dan status pemesanan kustom)
- `order_items` (kategori tiket yang dipesan)

---

## REST API Endpoints

Seluruh API endpoint menggunakan prefiks `/api`. Endpoint yang dilindungi oleh filter `api_jwt` membutuhkan autentikasi berupa Bearer token JWT di header `Authorization: Bearer <token>`.

### Response Format

Respon yang dikembalikan memiliki struktur JSON konsisten:

```json
{
  "status": "success" | "error",
  "message": "Response message description.",
  "data": { ... } | [ ... ] | null
}
```

Untuk list data terpaginasi, respon menyertakan sidecar `meta`:

```json
{
  "status": "success",
  "message": "...",
  "data": [],
  "meta": {
    "total": 42,
    "per_page": 10,
    "current_page": 1,
    "last_page": 5
  }
}
```

### Endpoint List

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| **Auth** | | | |
| `POST` | `/api/auth/register` | Public | Registrasi akun baru |
| `POST` | `/api/auth/login` | Public | Login & dapatkan token JWT kustom |
| `POST` | `/api/auth/logout` | JWT | Logout & matikan sesi autentikasi |
| **Events & Tickets** | | | |
| `GET` | `/api/events` | Public | Dapatkan daftar event terpaginasi |
| `GET` | `/api/events/featured` | Public | Dapatkan daftar event pilihan (featured) |
| `GET` | `/api/events/landing` | Public | Dapatkan daftar event untuk halaman utama |
| `GET` | `/api/events/{slug}` | Public | Detail event berdasarkan slug |
| `GET` | `/api/events/{id}/tickets` | Public | Daftar kategori tiket & kuota per event |
| **Checkout** | | | |
| `GET` | `/api/checkout/payment-methods` | Public | Daftar metode pembayaran yang tersedia |
| `POST` | `/api/checkout/calculate` | Public | Kalkulasi keranjang, subtotal, admin fee, & total |
| `POST` | `/api/checkout/start` | JWT | Mulai checkout & mengunci sisa kuota tiket |
| `POST` | `/api/checkout/confirm` | JWT | Upload bukti bayar / konfirmasi transaksi |
| `POST` | `/api/checkout/cancel` | JWT | Membatalkan transaksi pemesanan |
| **Profile & Orders** | | | |
| `GET` | `/api/profile` | JWT | Dapatkan data profil user saat ini |
| `POST` | `/api/profile/update` | JWT | Perbarui profil user saat ini |
| `GET` | `/api/orders` | JWT | Riwayat pemesanan user |
| `GET` | `/api/orders/{id}` | JWT | Detail transaksi pemesanan spesifik |

---

## Run the App

Jalankan server lokal pengembangan CodeIgniter 4 menggunakan spark serve:

```bash
php spark serve
```

Aplikasi akan berjalan di `http://localhost:8080`

---

## 🎨 UI/UX Design

The interface was designed in Figma before development, following a design-first workflow. The prototype covers user flows for browsing events, ticket purchasing, and the admin dashboard.

---

## 👥 Team

- **Hana**
- **Tama**
- **Jihan**

Built as a college project at Politeknik Negeri Cilacap, Informatics Engineering Department.

**Class:** Teknik Informatika 2D  
**Course:** Pemrograman Web 2  
**Institution:** Politeknik Negeri Cilacap

---

## License

This project is licensed under the [MIT License](LICENSE).

---

## ⚠️ Disclaimer

All event logos, promoter names, and concert posters featured in the screenshots and seed database of this project belong to their respective copyright owners (official promoters/events). They are used purely for educational and academic demonstration purposes to simulate a realistic ticketing catalog.

---

[![GitHub](https://img.shields.io/badge/GitHub-pratama1246-black?logo=github)](https://github.com/pratama1246)
