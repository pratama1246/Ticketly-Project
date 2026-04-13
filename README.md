# 🎟️ Ticketly

**Ticketly** is a web-based event ticketing platform built with CodeIgniter 4. The system enables users to browse events, purchase tickets, and manage bookings — while administrators can manage events, ticket quotas, and transaction data through a dedicated dashboard.

> Built and deployed as a college project at Politeknik Negeri Cilacap, Informatics Engineering Department.

---

## 🚀 Live Demo

📐 [Figma Design Prototype](https://www.figma.com) *(see `Tautan Figma Website.docx` in repo)*

---

## ✨ Features

### 👤 User
- Browse and view available events
- Purchase tickets with quantity selection
- View booking history and ticket status
- User authentication (register, login, logout)

### 🛠️ Admin
- Manage events (create, edit, delete)
- Monitor ticket sales and quotas
- View and manage transaction records
- Dashboard overview with key statistics

---

## 🧰 Tech Stack

| Layer | Technology |
|-------|-----------|
| Framework | CodeIgniter 4 |
| Language | PHP 8.1+ |
| Database | MySQL |
| Frontend | HTML, CSS, JavaScript |
| UI Design | Figma |
| Dependency Manager | Composer |

---

## 📁 Project Structure

```
ticketly-project/
├── app/
│   ├── Config/         # App configuration & routes
│   ├── Controllers/    # Request handlers
│   ├── Models/         # Database models
│   └── Views/          # HTML templates
├── public/             # Public assets (CSS, JS, images)
├── writable/           # Logs & cache
├── ticketly.sql        # Database schema & seed
├── composer.json       # PHP dependencies
└── package.json        # JS dependencies
```

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
   Edit `.env` and configure:
   ```
   app.baseURL = 'http://localhost:8080/'

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

## 👨‍💻 Author

| No | Name | NIM |
|----|------|-----|
| 1 | Dzakira Raihanna | 240202103 |
| 2 | Pratama Putra Purwanto | 240202115 |
| 3 | Shoffya Jihan Priyastri | 240302121 |

**Class:** Teknik Informatika 2D  
**Course:** Pemrograman Web 2
**Institution:** Politeknik Negeri Cilacap


[![GitHub](https://img.shields.io/badge/GitHub-pratama1246-black?logo=github)](https://github.com/pratama1246)
