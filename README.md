# 🎟️ Ticketly

**Ticketly** is a robust and modern event ticketing platform powered by **CodeIgniter 4 (PHP 8.1+)**. It provides a comprehensive solution for managing events, selling tickets, and processing bookings. The system features a web-based user portal, an administrative dashboard, and a custom JWT-protected RESTful API for mobile application integration.

This is the backend and web application repository. The companion mobile application repository can be found here:
* **Flutter Mobile App Repository:** [github.com/pratama1246/ticketly](https://github.com/pratama1246/ticketly)

It supports 3 main roles / integration paths:

- **User (Web)**: browse and view available events, purchase tickets with quantity selection, view booking history and ticket status, and manage profile.
- **Admin (Web Dashboard)**: manage events (create, edit, delete), monitor ticket sales and quotas, view/manage transaction records, and monitor dashboard statistics.
- **Mobile API (Flutter Integration)**: custom JWT-based authentication, retrieve events/tickets, calculate cart checkout in real-time, and manage booking status.

> The application exposes a custom JWT RESTful API endpoint to receive requests from the mobile Flutter client.

---

[![CodeIgniter](https://img.shields.io/badge/CodeIgniter-4-EE4326?style=for-the-badge&logo=codeigniter&logoColor=white)](https://codeigniter.com)
[![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-v3-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![Flowbite](https://img.shields.io/badge/Flowbite-v1.6-3F83F8?style=for-the-badge&logo=flowbite&logoColor=white)](https://flowbite.com)

---

## Table of Contents

- [Key Features](#key-features)
- [Tech Stack](#tech-stack)
- [Directory Structure](#directory-structure)
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
- Forgot password request (OTP via email) & secure password reset
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

- User authentication (Registration, login via custom JWT, OTP request, and password reset)
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

---

## Directory Structure

Here is the main directory structure of the **Ticketly** project:

```
ticketly-project/
├── app/                        # Main application directory
│   ├── Config/                 # Application configuration (Routes, Filters, Auth, etc.)
│   ├── Controllers/            # Controllers for business logic
│   │   ├── Admin/              # Controllers for admin area (Web)
│   │   ├── Api/                # REST API Controllers for Mobile (Flutter)
│   │   ├── Public/             # Controllers for public pages (Web)
│   │   └── User/               # Controllers for user & checkout (Web)
│   ├── Database/               # Database migrations and seeds
│   │   ├── Migrations/         # Database schema migration files
│   │   └── Seeds/              # Seeder files for initial/dummy data
│   ├── Filters/                # Request filters (JwtFilter, CorsFilter, etc.)
│   ├── Helpers/                # Custom helper files (jwt_helper.php for JWT operations)
│   ├── Models/                 # CodeIgniter 4 Models (Database interactions)
│   └── Views/                  # View templates (layouts & sections)
│       ├── admin/              # Admin dashboard views
│       ├── layout/             # Main layout templates (header, footer, sidebar)
│       ├── public/             # Public page views (landing page, event details)
│       └── user/               # User views (transactions, order history)
├── public/                     # Publicly accessible directory (via web browser)
│   ├── assets/                 # Static assets (images, logos, icons)
│   ├── js/                     # Client-side JavaScript files
│   ├── openapi.json            # OpenAPI specification for REST API documentation
│   ├── output.css              # Compiled Tailwind CSS file
│   └── uploads/                # Directory for uploaded files (payment proof, etc.)
├── tests/                      # Unit testing and integration testing
├── writable/                   # Writable folder for logs, cache, session data, etc.
├── .env                        # Environment configurations (DB, JWT secret, etc.)
├── README.md                   # Project documentation
└── spark                       # CodeIgniter 4 CLI tool script
```

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

# 5) Run database migrations
# Create a MySQL database named 'ticketly' first, configure your .env, then run:
php spark migrate

# 6) Run seeders to populate database with initial data & mock users (Optional)
php spark db:seed PaymentMethodSeeder
php spark db:seed EventSeeder
php spark db:seed AdminUserSeeder
php spark db:seed FakeUserSeeder
```

### 🔑 Default Testing Credentials

After running the seeders, you can use the following credentials for testing:

#### Administrator
- **Email:** `admin@ticketly.com`
- **Username:** `admin`
- **Password:** `admin123`

#### Mock Users
- `budi@example.com` / `password123` (username: `budi_santoso`)
- `ani@example.com` / `password123` (username: `ani_wijaya`)
- `dewi@example.com` / `password123` (username: `dewi_sari`)
- `rudi@example.com` / `password123` (username: `rudi_hermawan`)

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

The database schema is managed using CodeIgniter 4 **Migrations** (`app/Database/Migrations/`), and initial data is populated using **Seeds** (`app/Database/Seeds/`).

You can set up or reset the database structure by running:
```bash
php spark migrate
```

And populate the initial data using:
```bash
php spark db:seed PaymentMethodSeeder
php spark db:seed EventSeeder
php spark db:seed AdminUserSeeder
php spark db:seed FakeUserSeeder
```

This project uses the following main tables:

- `users` — Primary user accounts and profile data.
- `events` — Concerts and events details (name, slug, date, venue, poster, seatmap).
- `ticket_types` — Ticket tiers, pricing, and available quotas per event.
- `seats` — Seat mappings for event venues.
- `orders` — Transactions and booking status (`pending`, `completed`, `cancelled`, `expired`).
- `order_items` — Ordered ticket categories per transaction.
- `payment_methods` — Supported payment options (Virtual Account, E-Wallet, etc.).
- `password_resets` — Password reset verification OTP codes.

---

## REST API Endpoints

All API endpoints are prefixed with `/api`. Endpoints protected by the `api_jwt` filter require a valid JWT Bearer token in the `Authorization: Bearer <token>` header.

### 📖 Interactive API Docs (Scalar)

We provide interactive API documentation powered by **Scalar**. You can browse all endpoints, view detailed request/response schemas, generate integration code (e.g., for Flutter/Dart, JavaScript, curl), and test requests directly from your browser.

* **Documentation URL:** [http://localhost:8080/api/docs](http://localhost:8080/api/docs)
* **OpenAPI File Spec:** [`public/openapi.json`](public/openapi.json)

> **Note:** Make sure your local server is running (`php spark serve`) to access the URL above.

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
| `POST` | `/api/auth/forgot-password` | Public | Request password reset verification code (OTP) |
| `POST` | `/api/auth/verify-code` | Public | Validate OTP verification code |
| `POST` | `/api/auth/reset-password` | Public | Update user password with new credentials |
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

### 1. Run the CodeIgniter 4 local development server:
```bash
php spark serve
```
The application will run at `http://localhost:8080`

### 2. Compile Tailwind CSS (Watch Mode):
If you need to customize or compile the frontend styles, run the Tailwind CSS CLI:
```bash
npx @tailwindcss/cli -i ./public/input.css -o ./public/output.css --watch
```

---

## 🎨 UI/UX Design

The interface was designed in Figma before development, following a design-first workflow. The prototype covers user flows for browsing events, ticket purchasing, and the admin dashboard.

---

## Preview Screenshots



---

## 👥 Team

- **Hana**
- **Tama**
- **Jihan**

---

## License

This project is licensed under the [MIT License](LICENSE).

---

## ⚠️ Disclaimer

All event logos, promoter names, and concert posters featured in the screenshots and seed database of this project belong to their respective copyright owners (official promoters/events). They are used purely for educational and academic demonstration purposes to simulate a realistic ticketing catalog.

---

[![GitHub](https://img.shields.io/badge/GitHub-pratama1246-black?logo=github)](https://github.com/pratama1246)
