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
| `app/Config/Filters.php` | Filter aliases including `jwt` and `cors` |
| `app/Helpers/jwt_helper.php` | `createJWT()` and `decodeJWT()` functions |
| `app/Filters/JwtFilter.php` | Token validation, injection into `$_SERVER` |
| `app/Controllers/Api/AuthController.php` | Login, register, logout |
| `app/Controllers/Api/EventController.php` | List, detail, featured events |
| `app/Controllers/Api/CheckoutController.php` | Full API checkout flow |
| `app/Models/OrderModel.php` | Includes `autoExpireOrders()` |


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