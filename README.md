<p align="center">
  <img src="public/assets/ticketly-logo.png" alt="Ticketly Logo" width="220" />
</p>

# Ticketly Backend & Web Platform

**High-concurrency event ticketing backend and administration platform with Midtrans Core API payment processing, JWT authentication for mobile clients, and asynchronous ticket dispatch.**

---

[![PHP Version](https://img.shields.io/badge/PHP-8.2_%7C_8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Framework](https://img.shields.io/badge/CodeIgniter-4.6-EE4326?style=for-the-badge&logo=codeigniter&logoColor=white)](https://codeigniter.com)
[![Auth](https://img.shields.io/badge/Shield-1.2-1f883d?style=for-the-badge)](https://codeigniter.com/shield)
[![Database](https://img.shields.io/badge/MySQL-8.4-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![Container](https://img.shields.io/badge/Docker-Ready-2496ED?style=for-the-badge&logo=docker&logoColor=white)](docker-compose.yml)
[![Payment](https://img.shields.io/badge/Midtrans-Core_API-002D62?style=for-the-badge)](https://midtrans.com)
[![Frontend](https://img.shields.io/badge/TailwindCSS_v4-Flowbite-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)

---

## Quickstart

Run the full stack locally in under 2 minutes using Docker:

```bash
# 1. Clone repository & enter directory
git clone https://github.com/pratama1246/ticketly-project.git
cd ticketly-project

# 2. Boot application, web server, and database
docker compose up -d --build

# 3. Access web platform & interactive API documentation
# Web: http://localhost:8080
# API Docs: http://localhost:8080/api/docs
```

Or run via PHP local CLI:

```bash
composer install && cp env .env && php spark migrate && php spark serve --port=8080
```

---

## Table of Contents

- [System Overview](#system-overview)
- [Practical Use Cases](#practical-use-cases)
- [Core Engineering Capabilities](#core-engineering-capabilities)
- [System Architecture](#system-architecture)
- [Directory Structure](#directory-structure)
- [Operational Runbook & Deployment](#operational-runbook--deployment)
- [Environment Configuration](#environment-configuration)
- [REST API Reference & Interactive Docs](#rest-api-reference--interactive-docs)
- [Local Development & Testing](#local-development--testing)
- [Default Testing Accounts](#default-testing-accounts)
- [License & Disclaimer](#license--disclaimer)

---

## System Overview

Ticketly Backend is a monolithic yet decoupled ticketing server built with **CodeIgniter 4 (PHP 8.2/8.1)** and **MySQL 8.4**. It powers both the server-rendered web administrative dashboard and the public client application, while exposing a stateless, custom JWT-protected RESTful API for the companion mobile application ([Ticketly Flutter Client](https://github.com/pratama1246/ticketly)).

The system handles the entire ticketing lifecycle: event cataloging, tiered ticket pricing, dynamic seating allocations, flash-sale inventory reservation, automated payment processing via Midtrans Core API (10 payment rails), webhook status reconciliation, and asynchronous background dispatch of encrypted QR code PDF e-tickets.

All sensitive endpoints are guarded with custom rate limiting, cryptographic Bcrypt OTP storage, token revocation blacklisting, UU PDP personal data masking, and enterprise security headers.

---

## Practical Use Cases

| Scenario | Problem Solved | Realized Benefit |
|---|---|---|
| **High-Traffic Ticket Wars** | Race conditions during concurrent ticket checkout leading to overselling beyond venue capacity. | Conditional SQL atomic queries (`UPDATE ... WHERE quantity_sold + ? <= quantity_total`) and row-level pessimistic locks (`SELECT ... FOR UPDATE`) guarantee zero over-selling. |
| **Direct Core API Payments** | Clunky redirect popups (Snap) breaking mobile app checkout flows. | Native Midtrans Core API `/v2/charge` handles direct Virtual Account numbers, QRIS string payloads, and e-wallet deep-links without webview redirection. |
| **Gate Check-in Operations** | Network latency and fraudulent duplicate barcode scans at physical entrance gates. | Atomic check-in endpoint with state-locked queries returns `200 OK` on first validation and `409 Conflict` on subsequent scans. |
| **Regulatory Compliance (UU PDP)** | PII leakage in API transaction responses and administrative logs. | Automatic PII masking filters (`mask_email`, `mask_phone`, `mask_nik`) mask identity data before returning JSON payloads to client devices. |
| **Unattended Order Cleanup** | Abandoned checkout sessions locking inventory indefinitely. | Background CLI cron command (`php spark orders:expire`) runs independently of web traffic to release expired quotas back to general stock. |

---

## Core Engineering Capabilities

### 1. Concurrency Control & Inventory Reservation
- **Atomic Stock Reservation**: Eliminates overselling via conditional SQL statements executing direct decrement checks:
  `UPDATE ticket_types SET quantity_sold = quantity_sold + ? WHERE (quantity_sold + ?) <= quantity_total`.
- **Pessimistic Seat Allocation**: Enforces `SELECT ... FOR UPDATE` row-level locks in `OrderModel::assignSeats()` to prevent race conditions during concurrent seat selections.
- **Anti-Scalper NIK Quota**: Enforces a strict limit of 4 tickets per Indonesian National ID (NIK) across all pending and completed orders per event.
- **Idempotency Key Guard**: Processes `X-Idempotency-Key` headers on `POST /api/checkout/start` to prevent duplicate billing from network retries or double clicks.

### 2. Midtrans Core API Payment Engine
- Direct charge integration across 10 distinct payment rails:
  - **Virtual Accounts**: BCA, BNI, BRI, Permata (AlloBank).
  - **Bill Payment**: Mandiri E-Commerce / Bill Key & Biller Code.
  - **E-Wallets & Instant Pay**: GoPay, ShopeePay, OVO, DANA, QRIS.
  - **Cardless Credit**: Akulaku PayLater.
- **Webhook Reconciliation**: Secure webhook controller (`POST /api/payment/webhook`) verifying `SHA-512` payload signatures (`order_id + status_code + gross_amount + ServerKey`), with automatic inventory rollback on expired/cancelled orders.

### 3. Background Asynchronous Processing
- **Decoupled Queue Worker**: Spark command `php spark queue:work` monitors pending delivery jobs to compile PDF tickets with QR codes via Dompdf and dispatch them through SMTP without blocking the HTTP request thread.
- **Automated Expiry Scheduler**: Standalone CLI runner `php spark orders:expire` handles unblocking held inventory without degrading read latency on public catalog endpoints.

### 4. Gate Scanner & Venue Validation
- Dedicated Gate Check-in API (`/api/scanner/validate`, `/api/scanner/checkin`, `/api/scanner/stats/:eventId`).
- Verifies attendee identity against ticket item codes and marks tickets as used in a single atomic database update to prevent double-entry fraud.

### 5. Security & Privacy Hardening
- **Stateless Custom JWT**: Token creation and validation via `firebase/php-jwt` with centralized `JwtFilter` injecting authenticated credentials into `$_SERVER['JWT_USER_ID']`.
- **Token Revocation (Blacklist)**: Instant token invalidation upon `POST /api/auth/logout` via cache blacklist checks.
- **Rate Limiting**: Sliding-window rate limiter filter (`api_rate_limit:10,60`) applied to authentication and checkout endpoints.
- **Single-Use Hashed OTP**: OTP codes stored using Bcrypt (`PASSWORD_BCRYPT`) and deleted immediately after password reset.
- **Enterprise Headers**: Global `SecurityHeadersFilter` injecting `X-Frame-Options: DENY`, `X-Content-Type-Options: nosniff`, `Referrer-Policy: strict-origin-when-cross-origin`, and `Permissions-Policy`.

---

## System Architecture

```mermaid
flowchart TD
    subgraph Clients["Client Layer"]
        WEB["Desktop / Mobile Browser<br/>(Tailwind CSS + Flowbite)"]
        APP["Ticketly Mobile Client<br/>(Flutter / Dart)"]
        GATE["Gate Check-in Scanners<br/>(Scanner API)"]
    end

    subgraph Gateway["Network & Reverse Proxy"]
        NGINX["Nginx Web Server<br/>(Port 8080 / Alpine)"]
    end

    subgraph Backend["Application Layer (CodeIgniter 4)"]
        FILTERS["Middleware Filters<br/>(JwtFilter | CorsFilter | ApiRateLimit | SecurityHeaders)"]
        
        subgraph Controllers["Controller Modules"]
            API_CTRL["Api/ Controllers<br/>(Auth, Events, Checkout, Orders, Scanner, Vouchers)"]
            ADMIN_CTRL["Admin/ Controllers<br/>(Events, Tickets, Orders, Dashboard)"]
            USER_CTRL["User/ & Public/ Controllers<br/>(Web Checkout, Catalog, Legal)"]
        end

        subgraph Services["Services & Helpers"]
            MIDTRANS["MidtransService<br/>(Core API /v2/charge)"]
            DELIVERY["TicketDeliveryService<br/>(Dompdf + SMTP Mailer)"]
            JWT_HELP["jwt_helper.php<br/>(Create, Decode & Blacklist)"]
            MASKING["masking_helper.php<br/>(UU PDP PII Masking)"]
        end
    end

    subgraph CLI["Background CLI Daemons"]
        WORKER["php spark queue:work<br/>(Async PDF & Email)"]
        EXPIRE["php spark orders:expire<br/>(Inventory Auto-release)"]
    end

    subgraph Storage["Data Persistence"]
        DB[("MySQL 8.4 Server<br/>(Port 3307 / utf8mb4)")]
        FILES["Local Disk Storage<br/>(writable/uploads & PDFs)"]
    end

    subgraph External["External Cloud Services"]
        MIDTRANS_API["Midtrans Gateway<br/>(Core API & Webhooks)"]
        SMTP["SMTP Mail Provider<br/>(Mailtrap / Custom TLS)"]
    end

    %% Routing Flow
    WEB -->|HTTP Requests| NGINX
    APP -->|REST API Requests / Bearer JWT| NGINX
    GATE -->|Scanner API Requests / Bearer JWT| NGINX

    NGINX -->|FastCGI (Port 9000)| FILTERS
    FILTERS --> Controllers

    API_CTRL --> MIDTRANS
    API_CTRL --> JWT_HELP
    API_CTRL --> MASKING
    MIDTRANS -->|HTTPS /v2/charge| MIDTRANS_API
    MIDTRANS_API -->|Webhook POST /api/payment/webhook| NGINX

    Controllers -->|Queries & Transactions| DB
    WORKER -->|Fetch Pending Delivery| DB
    WORKER -->|Generate PDF & Save| FILES
    WORKER -->|Send E-Ticket Email| SMTP
    EXPIRE -->|Lock & Release Expired Quota| DB
```

---

## Directory Structure

```text
ticketly-project/
├── app/
│   ├── Commands/               # CLI Spark daemons (ExpireOrders, TicketQueueWorker, CleanupImages)
│   ├── Config/                 # CI4 configuration (Routes, Filters, Midtrans, Auth, Database)
│   ├── Controllers/
│   │   ├── Admin/              # Admin dashboard web controllers (Event, Ticket, Order)
│   │   ├── Api/                # REST API controllers consumed by Flutter & Gate scanners
│   │   ├── Public/             # Public web catalog controllers (Home, Event, Legal)
│   │   └── User/               # Authenticated web user & web checkout controllers
│   ├── Database/
│   │   ├── Migrations/         # Schema migrations including performance indexes & payment columns
│   │   └── Seeds/              # Database seeders (PaymentMethod, Event, AdminUser, FakeUser)
│   ├── Filters/                # Global and route filters (JwtFilter, ApiRateLimit, SecurityHeaders)
│   ├── Helpers/                # Helpers (jwt_helper.php, masking_helper.php)
│   ├── Libraries/              # Integrations (MidtransService.php, TicketDeliveryService.php)
│   ├── Models/                 # Database models (Event, TicketType, Order, OrderItem, Seat, Voucher)
│   └── Views/                  # Blade-style CI4 templates (admin, public, user, emails, pdf)
├── docker/                     # Docker container configuration
│   ├── nginx/                  # Nginx reverse proxy configuration (default.conf)
│   └── php/                    # PHP 8.2-FPM Alpine Dockerfile & php.ini / opcache.ini
├── public/                     # Public root
│   ├── assets/                 # Brand assets, logos, and payment method icons
│   ├── openapi.json            # OpenAPI 3.0 specification for Scalar API docs
│   └── uploads/                # Dynamic uploads (posters, seatmaps, user avatars)
├── tests/                      # Automated unit and feature test suites
├── writable/                   # Cache, logs, temporary PDFs, and session data
├── docker-compose.yml          # Container stack definition (app, web, db)
├── DOCKER.md                   # Containerization operational guide
├── composer.json               # Backend dependencies (CI4, Shield, PHP-JWT, Dompdf, Guzzle)
└── spark                       # CodeIgniter 4 CLI entry point
```

---

## Operational Runbook & Deployment

### Option A: Docker Compose Deployment (Recommended)

The application includes a container setup with healthchecks and persistent volumes:

```bash
# 1. Build and boot all containers in background
docker compose up -d --build

# 2. Check service status
docker compose ps
# Expected output: ticketly_app (Up), ticketly_web (Up: 8080), ticketly_db (Up: healthy, 3307)

# 3. Initialize or run migrations inside the container
docker compose exec app php spark migrate

# 4. Populate baseline seed data
docker compose exec app php spark db:seed PaymentMethodSeeder
docker compose exec app php spark db:seed EventSeeder
docker compose exec app php spark db:seed AdminUserSeeder
docker compose exec app php spark db:seed FakeUserSeeder

# 5. Tail container logs
docker compose logs -f app
```

### Option B: Bare Metal / Traditional VPS Deployment

```bash
# 1. Install production dependencies
composer install --no-dev --optimize-autoloader

# 2. Setup environment configuration
cp env .env
# Edit .env with your production database credentials and keys

# 3. Run database migrations
php spark migrate

# 4. Configure web server (Nginx/Apache) root to the /public directory
```

### Cron Jobs & Background Workers

Configure the following systemd services or crontab entries on your production host:

```bash
# Order Expiration Cron: Run every minute to release expired reservations
* * * * * cd /path/to/ticketly-project && php spark orders:expire >> /dev/null 2>&1

# Queue Worker: Run continuously via Supervisor or systemd daemon
php spark queue:work
```

---

## Environment Configuration

Configure the following keys in your `.env` file:

| Variable | Default Value | Description |
|---|---|---|
| `CI_ENVIRONMENT` | `development` | Environment mode (`development` or `production`). |
| `app.baseURL` | `http://localhost:8080/` | Canonical base URL of the web server. |
| `database.default.hostname` | `localhost` / `db` | Database host (`localhost` for host, `db` for Docker). |
| `database.default.database` | `ticketly` | MySQL database name. |
| `database.default.username` | `root` / `ticketly_user` | MySQL database user. |
| `database.default.password` | `""` / `ticketly_password` | MySQL database password. |
| `database.default.port` | `3306` / `3307` | Database port. |
| `JWT_SECRET_KEY` | *(Set secure 64-char key)* | Secret key used for signing and verifying JWT tokens. |
| `MIDTRANS_SERVER_KEY` | `SB-Mid-server-...` | Midtrans Server Key for payment charges and signature check. |
| `MIDTRANS_CLIENT_KEY` | `SB-Mid-client-...` | Midtrans Client Key for client-side rendering. |
| `MIDTRANS_IS_PRODUCTION` | `false` | Midtrans environment (`true` for production, `false` for sandbox). |
| `email.SMTPHost` | `live.smtp.mailtrap.io` | Outgoing SMTP server address. |
| `email.SMTPPort` | `2525` / `587` | Outgoing SMTP port. |
| `email.SMTPUser` | *(Your SMTP user)* | SMTP authentication username. |
| `email.SMTPPass` | *(Your SMTP pass)* | SMTP authentication password. |

---

## REST API Reference & Interactive Docs

All API endpoints are prefixed with `/api`. Protected routes require the header `Authorization: Bearer <token>`.

### Interactive API Explorer (Scalar)
When the application is running, browse schemas, generate client code snippets (Dart, cURL, JavaScript), and test endpoints interactively:
- **Interactive Documentation**: [http://localhost:8080/api/docs](http://localhost:8080/api/docs)
- **OpenAPI 3.0 Specification**: [`public/openapi.json`](public/openapi.json)

### Standard Response Envelope

All API responses strictly adhere to this uniform contract:

```json
{
  "status": "success",
  "message": "Transaction initiated successfully.",
  "data": { }
}
```

Paginated collections include a sidecar `meta` block:

```json
{
  "status": "success",
  "message": "Events retrieved.",
  "data": [ ],
  "meta": {
    "total": 42,
    "per_page": 10,
    "current_page": 1,
    "last_page": 5
  }
}
```

### Endpoint Summary

| Method | Endpoint | Filter | Description |
|---|---|---|---|
| `GET` | `/api/docs` | Public | Interactive Scalar API documentation |
| `GET` | `/api/terms` | Public | Terms and conditions (UU PDP compliance) |
| `GET` | `/api/privacy` | Public | Privacy policy documentation |
| `POST` | `/api/auth/register` | Rate Limit (5/min) | Customer account registration |
| `POST` | `/api/auth/login` | Rate Limit (10/min) | Authentication & JWT token issuance |
| `POST` | `/api/auth/logout` | JWT Filter | Session termination & JWT token revocation |
| `POST` | `/api/auth/forgot-password`| Rate Limit (5/min) | Request Bcrypt hashed OTP code via email |
| `POST` | `/api/auth/verify-code` | Rate Limit (10/min)| Verify 6-digit OTP code |
| `POST` | `/api/auth/reset-password` | Rate Limit (5/min) | Set new password with verified OTP |
| `GET` | `/api/events/featured` | Public | List high-priority headline events |
| `GET` | `/api/events/landing` | Public | Aggregated landing page catalog payload |
| `GET` | `/api/events` | Public | Paginated list of events with category filter |
| `GET` | `/api/events/{slug}` | Public | Event detail by slug |
| `GET` | `/api/events/{id}/tickets` | Public | Available ticket categories & real-time quota |
| `GET` | `/api/vouchers/active` | Public | List valid promo discount vouchers |
| `POST` | `/api/vouchers/apply` | Rate Limit (20/min)| Calculate discount applicability |
| `GET` | `/api/checkout/payment-methods` | Public | List supported Midtrans payment rails |
| `POST` | `/api/checkout/calculate` | Public | Calculate subtotal, fees, vouchers, and total |
| `POST` | `/api/checkout/start` | JWT + Rate Limit | Reserve ticket stock & initiate Midtrans charge |
| `POST` | `/api/checkout/cancel` | JWT Filter | Cancel pending checkout and release held quota |
| `POST` | `/api/payment/webhook` | SHA-512 Check | Midtrans payment notification handler |
| `GET` | `/api/profile` | JWT Filter | Retrieve authenticated user profile |
| `POST` | `/api/profile/update` | JWT Filter | Update profile credentials and avatar |
| `GET` | `/api/orders` | JWT Filter | Paginated list of user order history |
| `GET` | `/api/orders/{id}` | JWT Filter | Order detail with tickets and payment info |
| `POST` | `/api/scanner/validate` | JWT Filter | Gate verification of ticket item code |
| `POST` | `/api/scanner/checkin` | JWT Filter | Atomic gate check-in with anti-double scan |
| `GET` | `/api/scanner/stats/{id}` | JWT Filter | Live gate check-in statistics per event |

---

## Local Development & Testing

### Running the Full Development Stack (Concurrently)

To run the local server, compile CSS, and tunnel webhooks concurrently:

```bash
# Watch Tailwind styles, start PHP server, and expose port via ngrok:
npm run dev
```

### Running Automated Test Suite

```bash
# Execute PHPUnit tests
composer test
```

---

## Default Testing Accounts

Run database seeders (`php spark db:seed AdminUserSeeder` and `FakeUserSeeder`) to populate these test accounts:

| Role | Email | Username | Password |
|---|---|---|---|
| **Administrator** | `admin@ticketly.com` | `admin` | `admin123` |
| **Customer 1** | `budi@example.com` | `budi_santoso` | `password123` |
| **Customer 2** | `ani@example.com` | `ani_wijaya` | `password123` |
| **Customer 3** | `dewi@example.com` | `dewi_sari` | `password123` |
| **Customer 4** | `rudi@example.com` | `rudi_hermawan` | `password123` |

---

## License & Disclaimer

- **License**: Licensed under the [MIT License](LICENSE).
- **Academic Disclaimer**: This platform was developed as an academic project at **Politeknik Negeri Cilacap**, Informatics Engineering Department. All event posters, trademarks, and artist names used in seed data belong to their respective copyright owners and are utilized strictly for educational demonstration.

---

<p align="center">
  <sub>Maintained by <strong>pratama1246</strong> · Ticketly Backend Platform</sub>
</p>
