# 🎟️ Ticketly

**Ticketly** is an event ticketing platform built with CodeIgniter 4. It provides a web application (for administrators and users) and a RESTful API backend to support integration with the Ticketly Flutter mobile application.

> Built and deployed as a college project at Politeknik Negeri Cilacap, Informatics Engineering Department.

---

## ✨ Features

### 👤 User (Web)
- Browse and view available events
- Purchase tickets with quantity selection
- View booking history and ticket status
- User authentication (register, login, logout)

### 🛠️ Admin (Web Dashboard)
- Manage events (create, edit, delete)
- Monitor ticket sales and quotas
- View and manage transaction records
- Dashboard overview with key statistics

### 📱 Mobile API (Flutter Integration)
- JWT-based custom authentication (Login, Register, Logout)
- Retrieve featured events & landing page banners
- View event details, ticket categories, and available quotas
- Real-time cart calculation and checkout flow
- Booking management (Start transaction, confirm payment, cancel booking)
- User profile management & transaction history tracking

---

## 🧰 Tech Stack

| Layer | Technology |
|-------|-----------|
| Framework | CodeIgniter 4 |
| Language | PHP 8.1+ |
| Database | MySQL |
| Auth System | Shield (Web Session) & Custom JWT (Mobile API) |
| API Auth | Firebase PHP-JWT |
| Frontend | HTML, CSS, JavaScript, Tailwind CSS, Flowbite |
| UI Design | Figma |
| Dependency Manager | Composer |

---

## 📁 Project Structure

```
ticketly-project/
├── app/
│   ├── Config/         # App configuration & routes (including Shield & JWT filters)
│   ├── Controllers/    # Request handlers
│   │   ├── Admin/      # Web controller for admin dashboard
│   │   ├── Api/        # REST API controller for Flutter
│   │   ├── Public/     # Web controller for public pages
│   │   └── User/       # Web controller for user checkout
│   ├── Filters/        # Middlewares (e.g. JwtFilter for API security)
│   ├── Helpers/        # Custom helpers (e.g. jwt_helper.php)
│   ├── Models/         # Database models
│   └── Views/          # HTML templates (Blade-style + Flowbite)
├── public/             # Public assets (CSS, JS, images)
├── writable/           # Logs & cache
├── ticketly.sql        # Database schema & seed
├── composer.json       # PHP dependencies
└── package.json        # JS dependencies
```

---

## 🔌 REST API Endpoints

All API endpoints are prefixed with `/api`. Protected routes require a valid JWT token sent in the `Authorization: Bearer <token>` header.

### Response Format
All responses return a consistent JSON structure:
```json
{
  "status": "success" | "error",
  "message": "Response message description.",
  "data": { ... } | [ ... ] | null
}
```

For paginated lists, a sidecar `meta` object is included:
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

### Endpoints List

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| **Auth** | | | |
| `POST` | `/api/auth/register` | Public | Register a new user |
| `POST` | `/api/auth/login` | Public | Login & receive JWT token |
| `POST` | `/api/auth/logout` | JWT | Logout and invalidate session |
| **Events** | | | |
| `GET` | `/api/events` | Public | Get list of events (paginated) |
| `GET` | `/api/events/featured` | Public | Get featured events |
| `GET` | `/api/events/landing` | Public | Get landing page events |
| `GET` | `/api/events/{slug}` | Public | Get event detail by slug |
| `GET` | `/api/events/{id}/tickets` | Public | Get ticket types & quotas for an event |
| **Checkout** | | | |
| `GET` | `/api/checkout/payment-methods` | Public | Get list of available payment methods |
| `POST` | `/api/checkout/calculate` | Public | Calculate subtotal, fees, and grand total |
| `POST` | `/api/checkout/start` | JWT | Initialize checkout & lock ticket quota |
| `POST` | `/api/checkout/confirm` | JWT | Upload proof of payment / confirm transaction |
| `POST` | `/api/checkout/cancel` | JWT | Cancel a pending transaction |
| **Profile & Orders** | | | |
| `GET` | `/api/profile` | JWT | Get current user's profile info |
| `POST` | `/api/profile/update` | JWT | Update user's profile info |
| `GET` | `/api/orders` | JWT | Get user's order history |
| `GET` | `/api/orders/{id}` | JWT | Get detailed transaction information |

---

## ⚙️ Installation

### Prerequisites
- PHP >= 8.1
- Composer
- MySQL
- Web server (Apache/Nginx) or PHP built-in server

### Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/pratama1246/ticketly-project.git
   cd ticketly-project
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Setup environment**
   ```bash
   cp env .env
   ```
   Edit `.env` and configure your database and JWT secret key:
   ```env
   app.baseURL = 'http://localhost:8080/'
   JWT_SECRET_KEY = your_jwt_secret_key_here

   database.default.hostname = localhost
   database.default.database = ticketly
   database.default.username = root
   database.default.password = 
   database.default.DBDriver = MySQLi
   ```

4. **Import database**
   ```bash
   # Create database first, then import:
   mysql -u root -p ticketly < ticketly.sql
   ```

5. **Run the application**
   ```bash
   php spark serve
   ```
   Open browser at `http://localhost:8080`

---

## 🗄️ Database

The database schema is available in `ticketly.sql`. Import it directly to your MySQL server to get the full table structure along with sample data.

---

## 🎨 UI/UX Design

The interface was designed in Figma before development, following a design-first workflow. The prototype covers user flows for browsing events, ticket purchasing, and the admin dashboard.

---

## 📄 License

This project is licensed under the [MIT License](LICENSE).

---

## 👥 Team

| No | Name |
|----|------|
| 1 | Hana |
| 2 | Tama|
| 3 | Jihan |

**Class:** Teknik Informatika 2D  
**Course:** Pemrograman Web 2   
**Institution:** Politeknik Negeri Cilacap

---

## ⚠️ Disclaimer

All event logos, promoter names, and concert posters featured in the screenshots and seed database of this project belong to their respective copyright owners (official promoters/events). They are used purely for educational and academic demonstration purposes to simulate a realistic ticketing catalog.

---

[![GitHub](https://img.shields.io/badge/GitHub-pratama1246-black?logo=github)](https://github.com/pratama1246)
