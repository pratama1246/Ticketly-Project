# 🎟️ Ticketly (PNC)

**Ticketly** is a **CodeIgniter 4 (PHP 8.1+)** event ticketing platform built as a college project at **Politeknik Negeri Cilacap**.

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

- Custom registration for user & admin
- Login & logout
- Edit user profile & update custom data
- Session-based authentication for Web (Shield) & custom JWT token for Mobile API

### User (Web)

- Browse available concert/show events
- Ticket purchase with custom quantity selection
- Order history & custom transaction details
- Automatic order status updates

### Admin (Web Dashboard)

- Ticket sales statistics dashboard
- Event management (CRUD)
- Real-time ticket sales & quota monitoring
- Manage and view incoming ticket transaction data

### Mobile API (Flutter Integration)

- Registration & login (obtaining custom JWT)
- Home banner/landing events & featured events
- Detailed event information, ticket categories, & remaining ticket quota
- Real-time shopping cart (cart) calculation
- Booking transactions (start checkout, upload proof of payment/confirm, cancel booking)

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
- Web server (Apache/Nginx) or built-in PHP development server
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

# 4) Configure your .env (DB, Base URL, JWT_SECRET_KEY, etc.)

# 5) Import database
# Create a MySQL database named 'ticketly' first, then run:
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
email.fromEmail='noreply@yourdomain.com'
email.protocol='smtp'
email.SMTPHost='live.smtp.mailtrap.io'
email.SMTPUser='your_mailtrap_user'
email.SMTPPass='your_mailtrap_password'
email.SMTPPort=2525
email.SMTPCrypto='tls'
email.mailType='html'
```

---

## Database Structure

The complete database structure along with initial seed data is available in the `ticketly.sql` file. Import it directly to your MySQL server to get a database that is ready to use.

This project uses the following main tables:

- `users` & `auth_groups_users` (user management & admin/user role mapping)
- `events` (event data)
- `tickets` (ticket types and quotas per event)
- `orders` (transaction history and custom booking status)
- `order_items` (ordered ticket categories)

---

## REST API Endpoints

All API endpoints are prefixed with `/api`. Endpoints protected by the `api_jwt` filter require a valid JWT Bearer token in the `Authorization: Bearer <token>` header.

### Response Format

Returned responses have a consistent JSON structure:

```json
{
  "status": "success" | "error",
  "message": "Response message description.",
  "data": { ... } | [ ... ] | null
}
```

For paginated list data, the response includes a sidecar `meta` object:

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
| `POST` | `/api/auth/register` | Public | Register a new account |
| `POST` | `/api/auth/login` | Public | Login & receive custom JWT token |
| `POST` | `/api/auth/logout` | JWT | Logout & terminate authentication session |
| **Events & Tickets** | | | |
| `GET` | `/api/events` | Public | Get paginated list of events |
| `GET` | `/api/events/featured` | Public | Get featured events list |
| `GET` | `/api/events/landing` | Public | Get events list for the main/landing page |
| `GET` | `/api/events/{slug}` | Public | Event details by slug |
| `GET` | `/api/events/{id}/tickets` | Public | List of ticket categories & quotas per event |
| **Checkout** | | | |
| `GET` | `/api/checkout/payment-methods` | Public | List of available payment methods |
| `POST` | `/api/checkout/calculate` | Public | Calculate cart, subtotal, admin fee, & total |
| `POST` | `/api/checkout/start` | JWT | Start checkout & lock remaining ticket quota |
| `POST` | `/api/checkout/confirm` | JWT | Upload proof of payment / confirm transaction |
| `POST` | `/api/checkout/cancel` | JWT | Cancel booking transaction |
| **Profile & Orders** | | | |
| `GET` | `/api/profile` | JWT | Get current user's profile details |
| `POST` | `/api/profile/update` | JWT | Update current user's profile details |
| `GET` | `/api/orders` | JWT | User order history |
| `GET` | `/api/orders/{id}` | JWT | Specific order transaction details |

---

## Run the App

Run the CodeIgniter 4 local development server using spark serve:

```bash
php spark serve
```

The application will run at `http://localhost:8080`

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
