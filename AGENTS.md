# AGENTS.md — Ticketly Backend

Ultimate guidelines for AI coding agents (Claude Code, Cursor, Antigravity, etc.).
Read this file before making any changes to this project.

---

## Project Overview

**Ticketly** is an event ticketing platform featuring a web interface and a mobile app (Flutter).

- Framework: CodeIgniter 4
- Auth: CodeIgniter Shield (session-based for web, custom JWT for API)
- Database: MySQL
- Web Frontend: Blade-style CI4 views + Tailwind CSS + Flowbite
- Mobile: Flutter — consumes the REST API from `app/Controllers/Api/`

This project is an **existing project being cleaned up**, not a new project.
The main task is to maintain consistency, not to rebuild.

---

## Directory Structure

```
app/
  Config/           — CI4 configuration (Routes, Filters, Auth, Autoload)
  Controllers/
    Admin/          — Web controllers for admin area
    Api/            — API controllers for Flutter
    Public/         — Web controllers for public pages
    User/           — Web controllers for user/checkout
  Filters/          — JWT filter, CORS filter
  Helpers/          — jwt_helper.php (createJWT, decodeJWT)
  Models/           — All CI4 models
  Database/
    Migrations/     — Schema migrations
    Seeds/          — Seeder data
```

---

## Backend Architecture Rules

### What Can Be Modified

- `app/Controllers/Api/` — API layer for Flutter
- `app/Filters/` — only if there is a real bug or clear inconsistency
- `app/Helpers/` — jwt_helper.php
- `app/Models/` — only add fields to `$allowedFields` if needed
- `app/Config/Routes.php` — add new routes to existing groups

### DO NOT MODIFY WITHOUT A STRONG REASON

- Web controllers (`Admin/`, `Public/`, `User/`) — already stable
- `app/Config/Auth.php` and `app/Config/AuthGroups.php`
- Existing database tables schema
- Already run migrations

### Core Principles

- **Existing project is the source of truth** — follow existing patterns, not external best practices.
- **Consistency over perfection** — consistency with old patterns is preferred over "correct" but different.
- **Minimal changes** — modify only what is necessary, avoid refactoring other components.
- **Do not introduce new abstractions** if the project does not use them consistently.

---

## Naming Conventions

### Controllers

```
app/Controllers/Api/EventController.php       → namespace App\Controllers\Api
app/Controllers/Admin/EventController.php     → namespace App\Controllers\Admin
app/Controllers/Public/EventController.php    → namespace App\Controllers\Public
```

Method names must follow CI4 resource naming conventions: `index`, `show`, `create`, `store`, `edit`, `update`, `delete`.

### Models

```php
protected $table         = 'ticket_types';   // snake_case, plural
protected $allowedFields = ['event_id', ...]; // snake_case
protected $returnType    = 'array';           // always array, not object
```

### API Routes

```
GET    /api/events                    → index (list)
GET    /api/events/{slug}             → show (detail by slug)
GET    /api/events/{id}/tickets       → resource nested
POST   /api/checkout/start            → action
GET    /api/checkout/payment-methods  → action with resource noun
```

Use kebab-case for URLs, snake_case for JSON keys.

### Order Status

Always **lowercase**. No capitalization.

```
pending | completed | cancelled | expired
```

Do NOT use `Pending`, `Completed`, `Cancelled`, `Expired`.

---

## API Consistency Rules

### Auth Flow

JWT is implemented custom, not using the built-in Shield JWT.

```php
// Generate token (in AuthController login)
$token = createJWT($userId, $email);   // from jwt_helper.php

// Validate token (in JwtFilter)
$decoded = decodeJWT($token);
$_SERVER['JWT_USER_ID'] = $decoded->userId;
$_SERVER['JWT_EMAIL']   = $decoded->email;

// Retrieve user ID in protected controllers
$userId = $_SERVER['JWT_USER_ID'] ?? null;
if (!$userId) { /* return 401 */ }
```

Do not replace this flow with Shield JWT or other libraries.

### Protected Routes

Routes requiring authentication must be wrapped with the `jwt` filter:

```php
$routes->group('', ['filter' => 'jwt'], function ($routes) {
    $routes->get('profile', 'ProfileController::index');
    // ...
});
```

Public routes do not need any filter.

---

## Response Structure

All API responses must follow this structure **without exception**:

```json
{
  "status": "success",
  "message": "Clear message description.",
  "data": { } | [ ] | null
}
```

### HTTP Status Code

| Condition | Code |
|---|---|
| GET/POST Success | 200 |
| CREATE Success | 201 |
| Validation Failure | 422 |
| Unauthorized (no/invalid token) | 401 |
| Not Found | 404 |
| Conflict (out of stock, invalid status) | 409 |
| Gone (order expired) | 410 |
| Server Error | 500 |

### Shape of `data` per Response Type

```json
// Single resource
"data": { "id": 1, "name": "..." }

// List / collection
"data": [ {...}, {...} ]

// Operation with no returned data (logout, cancel)
"data": null

// Validation error
"data": { "field_name": "error message" }
```

### Pagination

Only list/collection endpoints require pagination. Use **sidecar `meta`**:

```json
{
  "status": "success",
  "message": "...",
  "data": [ ],
  "meta": {
    "total": 42,
    "per_page": 10,
    "current_page": 1,
    "last_page": 5
  }
}
```

`data` remains a flat array. `meta` is only present in paginated endpoints.
Other endpoints do not need `meta`.

Default: `page=1`, `limit=10`, max limit=50.

---

## Workflow When Making Changes

### Before Writing Code

1. Read the file to be modified first.
2. Identify patterns already used in similar files.
3. Determine the scope of changes — document what will be modified and what won't.
4. If in doubt about the pattern, refer to a stable controller.

### While Writing Code

1. Follow the existing file pattern — do not introduce new patterns without reason.
2. Modify only what is in scope, do not perform extra refactoring.
3. Do not rename existing variables or methods without a clear reason.
4. Do not add `use` statements for unused classes.

### After Making Changes

1. Check if the response structure remains consistent.
2. Ensure status strings are entirely lowercase.
3. If database changes affect existing data, include a normalization query.

### Safe Order of Changes

```
Config → Models → Helpers → Filters → Controllers
```

Start from the lowest risk components. Controllers should be modified last.

---

## Dangerous Operations

Avoid these actions without explicit discussion:

```
DO NOT modify existing table structures
DO NOT rename database columns
DO NOT delete or move stable web controllers
DO NOT replace the JWT flow with another implementation
DO NOT add Service, Repository, or UseCase layers
DO NOT create a BaseApiController or additional abstractions unless all controllers adopt them
DO NOT modify $validFields in Auth.php
DO NOT run php spark migrate:fresh or truncate production data
DO NOT modify Shield auth flow for web
```

---

## Anti-Patterns to Avoid

```php
// DO NOT: overengineer response wrappers
class ApiResponse {
    public static function success($data) { ... }
}

// DO: return inline response directly like other controllers
return $this->response->setStatusCode(200)->setJSON([
    'status'  => 'success',
    'message' => '...',
    'data'    => $data
]);
```

```php
// DO NOT: create new interfaces or abstract classes
interface OrderRepositoryInterface { ... }

// DO: use the Model directly like other controllers
$orderModel = new OrderModel();
$order = $orderModel->find($id);
```

```php
// DO NOT: capitalize status strings
['status' => 'Pending']

// DO: always use lowercase
['status' => 'pending']
```

---

## Checklist Before Commit

- [ ] Response structure follows `{ status, message, data }`
- [ ] Order status is always lowercase (`pending`, `completed`, etc.)
- [ ] No duplicate `addGroup()` or repeated logic
- [ ] Pagination uses sidecar `meta`, not wrapping `data`
- [ ] No web controllers have been modified
- [ ] No new abstractions inconsistent with the project
- [ ] Include data normalization queries if database status values change

---

## Important File References

| File | Description |
|---|---|
| `app/Config/Routes.php` | All web and API routes |
| `app/Config/Filters.php` | Filter aliases including `jwt`, `cors`, and `api_rate_limit` |
| `app/Config/Midtrans.php` | Midtrans Core API Configuration (Sandbox / Production) |
| `app/Filters/ApiRateLimitFilter.php` | API Rate Limiter filter (429 JSON response) |
| `app/Helpers/jwt_helper.php` | `createJWT()` and `decodeJWT()` functions |
| `app/Filters/JwtFilter.php` | Token validation, injection into `$_SERVER` |
| `app/Libraries/MidtransService.php` | Midtrans Core API `/v2/charge` handler (10 payment methods) |
| `app/Controllers/Api/PaymentWebhookController.php` | Midtrans payment notification handler (SHA512 & idempotency) |
| `app/Controllers/Public/LegalController.php` | Web & API endpoints for Terms & Privacy Policy |
| `app/Controllers/Api/AuthController.php` | Login, register, logout, Bcrypt hashed single-use OTP |
| `app/Controllers/Api/ProfileController.php` | Profile get & update with auth_identities sync |
| `app/Controllers/Api/EventController.php` | List, detail, featured events |
| `app/Controllers/Api/CheckoutController.php` | Full API checkout flow (single-batch query & Midtrans connected) |
| `app/Models/OrderModel.php` | Includes `autoExpireOrders()` & payment columns |

## Features & Improvements Added (Phase 1, Phase 2, & Phase 3)

- **Database Performance & Indexes (Phase 1)**: Migration `AddPerformanceIndexesAndAlterResetCode` adding composite indexes on `orders(status, created_at)`, `orders(user_id)`, `order_items(order_id)`, `order_items(ticket_type_id, seat_id)`, `ticket_types(event_id)`, `events(slug UNIQUE)`, `events(status, category, event_date)`, `seats(event_id, ticket_type_id)`, and `password_resets.code` expanded to `VARCHAR(255)`.
- **API Rate Limiting (Phase 1)**: `api_rate_limit` filter applied on sensitive endpoints (`auth/login`, `auth/register`, `auth/forgot-password`, `auth/verify-code`, `auth/reset-password`, `checkout/start`).
- **Single-Use Hashed OTP (Phase 1)**: OTP codes stored using `password_hash($code, PASSWORD_BCRYPT)` and deleted immediately upon password reset.
- **Profile Email Sync (Phase 1)**: `ProfileController::update` validates uniqueness against Shield's `auth_identities` and syncs the secret column.
- **Single-Batch Ticket Query (Phase 1)**: `CheckoutController::start` retrieves all selected tickets in a single `whereIn` query and indexes in memory.
- **Legal Compliance (Phase 1)**: `/terms`, `/privacy`, `/api/terms`, and `/api/privacy` routes and views for T&C and Privacy Policy compliance.
- **Midtrans Core API Integration (Phase 2)**: Direct charge mapping for 10 payment methods (`bca`, `bni`, `bri`, `permata/allobank`, `mandiri_bill`, `gopay`, `shopeepay`, `ovo/dana/qris`, `akulaku`) without Snap popup.
- **Payment Webhook Handler (Phase 2)**: Route `POST /api/payment/webhook` with `SHA512` signature verification, idempotent status update, inventory restore on expiration/cancellation, and automated e-ticket dispatch on settlement.
- **Payment Details Schema (Phase 2)**: Migration `AddPaymentDetailsToOrders` adding `payment_va_number`, `payment_bill_key`, `payment_biller_code`, `payment_qr_url`, `payment_deeplink_url`, `payment_expired_at`, `midtrans_transaction_id`, `payment_status_raw`.
- **CLI Background Scheduler (Phase 3)**: Spark command `php spark orders:expire` (`app/Commands/ExpireOrders.php`) for cron job execution, de-coupling heavy expiration writes from public read endpoints (`/api/events`).
- **Atomic Stock Reservation (Phase 3)**: Conditional atomic queries (`UPDATE ticket_types SET quantity_sold = quantity_sold + ? WHERE (quantity_sold + ?) <= quantity_total`) eliminating over-selling race conditions during ticket wars.
- **Pessimistic Seat Locking (Phase 3)**: `SELECT ... FOR UPDATE` row-level locks on `assignSeats()` ensuring zero duplicate seat allocations during concurrent checkouts.
- **Anti-Scalper NIK Quota Limit (Phase 3)**: Strict enforcement of maximum 4 tickets per NIK/identity per event across pending and completed orders.
- **Idempotency Key (Phase 3)**: Header `X-Idempotency-Key` caching on `POST /api/checkout/start` preventing duplicate order creation from double-clicks or network retries.

## Existing UI/API Priority

If there is a conflict between:
- modern best practices
- AI preferences
- existing implementation

prioritize the existing implementation as long as it remains stable and consistent.

## Token & Context Efficiency

- do not request the entire repository if not needed
- prioritize representative files
- conduct audits before large code generations
- generate changes per file/module