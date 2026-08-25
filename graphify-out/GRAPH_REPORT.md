# Graph Report - ticketly-project  (2026-08-25)

## Corpus Check
- 194 files · ~115,939 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 600 nodes · 880 edges · 103 communities (81 shown, 22 thin omitted)
- Extraction: 91% EXTRACTED · 9% INFERRED · 0% AMBIGUOUS · INFERRED: 79 edges (avg confidence: 0.85)
- Token cost: 1,200 input · 600 output

## Community Hubs (Navigation)
- Admin & Public Web Controllers
- Shield Web Authentication Config
- Flowbite UI Component Library
- Database Schema Migrations
- Composer Dependencies & Autoload
- JWT Auth & Security Guidelines
- Database Seeders & Initial Data
- Network & Security Configuration
- CLI Commands & Image Processing
- HTTP Middleware Filters
- Tailwind & NPM Tooling
- Test Suite & Health Checks
- Application Bootstrap & Preload
- Caching Configuration & Handlers
- Debug Toolbar Collectors
- Admin Ticket Tier Management
- Exception Handling & Logging
- API Checkout & Booking Pipeline
- Event Discovery & Detail API
- Validation Module
- View Template (debug.js)
- Kint Module
- Database Module
- Event Catalog Management
- Format Module
- Images Module
- Logger Module
- Mimes Module
- Authgroups Module
- Authtoken Module
- Autoload Module
- Cookie Module
- Foreigncharacters Module
- Modules Module
- Publisher Module
- Routing Module
- Services Module
- View Template (error_exception.php)
- Doctypes Module
- Optimize Module
- Routes Module
- Readme Module
- Profile_default Module
- Ticket Tier System
- Robots Module

## God Nodes (most connected - your core abstractions)
1. `BaseController` - 35 edges
2. `Auth` - 17 edges
3. `EventModel` - 16 edges
4. `CheckoutController` - 15 edges
5. `I()` - 15 edges
6. `U()` - 15 edges
7. `ct()` - 14 edges
8. `mt()` - 14 edges
9. `Session` - 13 edges
10. `z()` - 13 edges

## Surprising Connections (you probably didn't know these)
- `CodeIgniter Shield Web Auth Policy` --rationale_for--> `Auth`  [EXTRACTED]
  AGENTS.md → app/Config/Auth.php
- `Custom JWT Authentication Architecture` --rationale_for--> `createJWT()`  [EXTRACTED]
  AGENTS.md → app/Helpers/jwt_helper.php
- `Custom JWT Authentication Architecture` --rationale_for--> `decodeJWT()`  [EXTRACTED]
  AGENTS.md → app/Helpers/jwt_helper.php
- `ConfigReader` --inherits--> `App`  [EXTRACTED]
  tests/_support/Libraries/ConfigReader.php → app/Config/App.php
- `EventController` --inherits--> `BaseController`  [EXTRACTED]
  app/Controllers/Admin/EventController.php → app/Controllers/BaseController.php

## Import Cycles
- None detected.

## Hyperedges (group relationships)
- **JWT Mobile Authentication Pipeline** — app_helpers_jwt_helper, app_filters_jwtfilter, app_controllers_api_authcontroller, agents_auth_flow [EXTRACTED 1.00]
- **Ticketing & Checkout Processing Subsystem** — app_controllers_api_checkoutcontroller, app_controllers_user_checkoutcontroller, app_models_ordermodel, app_models_orderitemsmodel, app_models_tickettypemodel [EXTRACTED 1.00]
- **Event Catalog & Ticketing Management** — app_models_eventmodel, app_models_tickettypemodel, app_controllers_admin_eventcontroller, app_controllers_api_eventcontroller, app_controllers_public_eventcontroller [EXTRACTED 1.00]

## Communities (103 total, 22 thin omitted)

### Community 0 - "Admin & Public Web Controllers"
Cohesion: 0.06
Nodes (28): REST API Response Consistency Standard, Order Status Normalization Standard, DashboardController, DocsController, OrderController, ProfileController, TicketController, BaseController (+20 more)

### Community 1 - "Shield Web Authentication Config"
Cohesion: 0.06
Nodes (14): CodeIgniter Shield Web Auth Policy, Auth, Session, View, EventController, OrderController, PageController, CheckoutController (+6 more)

### Community 2 - "Flowbite UI Component Library"
Cohesion: 0.12
Nodes (52): A(), at(), B(), Bt(), C(), ct(), u(), d() (+44 more)

### Community 3 - "Database Schema Migrations"
Cohesion: 0.07
Nodes (10): CreateEventsTable, CreateTicketTypesTable, CreateOrdersTable, CreateOrderItemsTable, CreatePaymentMethodsTable, CreateSeatsTable, AddSeatIdToOrderItems, AddMissingColumnsToEvents (+2 more)

### Community 4 - "Composer Dependencies & Autoload"
Cohesion: 0.05
Nodes (36): autoload, autoload-dev, psr-4, exclude-from-classmap, psr-4, config, optimize-autoloader, preferred-install (+28 more)

### Community 5 - "JWT Auth & Security Guidelines"
Cohesion: 0.10
Nodes (16): Custom JWT Authentication Architecture, Ticketly Backend Agent Guidelines, AuthController, CorsFilter, JwtFilter, createJWT(), decodeJWT(), CodeIgniter\Controller (+8 more)

### Community 6 - "Database Seeders & Initial Data"
Cohesion: 0.09
Nodes (18): AdminUserSeeder, EventSeeder, FakeUserSeeder, PaymentMethodSeeder, CodeIgniter\Database\Seeder, CodeIgniter\Shield\Authentication\Actions\ActionInterface, CodeIgniter\Shield\Authentication\AuthenticatorInterface, CodeIgniter\Shield\Authentication\Authenticators\AccessTokens (+10 more)

### Community 7 - "Network & Security Configuration"
Cohesion: 0.12
Nodes (13): ContentSecurityPolicy, Cors, CURLRequest, Email, Encryption, Feature, Generators, Honeypot (+5 more)

### Community 9 - "CLI Commands & Image Processing"
Cohesion: 0.24
Nodes (4): CleanupImages, Serve, CodeIgniter\CLI\BaseCommand, CodeIgniter\CLI\CLI

### Community 10 - "HTTP Middleware Filters"
Cohesion: 0.17
Nodes (11): Filters, CodeIgniter\Config\Filters, CodeIgniter\Filters\Cors, CodeIgniter\Filters\CSRF, CodeIgniter\Filters\DebugToolbar, CodeIgniter\Filters\ForceHTTPS, CodeIgniter\Filters\Honeypot, CodeIgniter\Filters\InvalidChars (+3 more)

### Community 11 - "Tailwind & NPM Tooling"
Cohesion: 0.18
Nodes (10): flowbite, dependencies, flowbite, @tailwindcss/cli, @tailwindcss/typography, devDependencies, tailwindcss, tailwindcss (+2 more)

### Community 12 - "Test Suite & Health Checks"
Cohesion: 0.27
Nodes (4): App, CodeIgniter\Test\CIUnitTestCase, ConfigReader, HealthTest

### Community 13 - "Application Bootstrap & Preload"
Cohesion: 0.24
Nodes (3): Paths, CodeIgniter\Boot, preload

### Community 14 - "Caching Configuration & Handlers"
Cohesion: 0.22
Nodes (8): Cache, CodeIgniter\Cache\CacheInterface, CodeIgniter\Cache\Handlers\DummyHandler, CodeIgniter\Cache\Handlers\FileHandler, CodeIgniter\Cache\Handlers\MemcachedHandler, CodeIgniter\Cache\Handlers\PredisHandler, CodeIgniter\Cache\Handlers\RedisHandler, CodeIgniter\Cache\Handlers\WincacheHandler

### Community 15 - "Debug Toolbar Collectors"
Cohesion: 0.22
Nodes (8): Toolbar, CodeIgniter\Debug\Toolbar\Collectors\Database, CodeIgniter\Debug\Toolbar\Collectors\Events, CodeIgniter\Debug\Toolbar\Collectors\Files, CodeIgniter\Debug\Toolbar\Collectors\Logs, CodeIgniter\Debug\Toolbar\Collectors\Routes, CodeIgniter\Debug\Toolbar\Collectors\Timers, CodeIgniter\Debug\Toolbar\Collectors\Views

### Community 17 - "Exception Handling & Logging"
Cohesion: 0.38
Nodes (5): Exceptions, CodeIgniter\Debug\ExceptionHandler, CodeIgniter\Debug\ExceptionHandlerInterface, Psr\Log\LogLevel, Throwable

### Community 20 - "Validation Module"
Cohesion: 0.33
Nodes (5): Validation, CodeIgniter\Validation\StrictRules\CreditCardRules, CodeIgniter\Validation\StrictRules\FileRules, CodeIgniter\Validation\StrictRules\FormatRules, CodeIgniter\Validation\StrictRules\Rules

### Community 21 - "View Template (debug.js)"
Cohesion: 0.53
Nodes (4): getFirstChildWithTagName(), getHash(), init(), showTab()

### Community 22 - "Kint Module"
Cohesion: 0.40
Nodes (4): Kint, Kint\Parser\ConstructablePluginInterface, Kint\Renderer\Rich\TabPluginInterface, Kint\Renderer\Rich\ValuePluginInterface

### Community 24 - "Event Catalog Management"
Cohesion: 0.50
Nodes (3): CodeIgniter\Events\Events, CodeIgniter\Exceptions\FrameworkException, CodeIgniter\HotReloader\HotReloader

### Community 25 - "Format Module"
Cohesion: 0.50
Nodes (3): Format, CodeIgniter\Format\JSONFormatter, CodeIgniter\Format\XMLFormatter

### Community 26 - "Images Module"
Cohesion: 0.50
Nodes (3): Images, CodeIgniter\Images\Handlers\GDHandler, CodeIgniter\Images\Handlers\ImageMagickHandler

### Community 27 - "Logger Module"
Cohesion: 0.50
Nodes (3): Logger, CodeIgniter\Log\Handlers\FileHandler, CodeIgniter\Log\Handlers\HandlerInterface

## Knowledge Gaps
- **45 isolated node(s):** `DocTypes`, `Kint`, `Optimize`, `name`, `description` (+40 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **22 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `BaseController` connect `Admin & Public Web Controllers` to `Shield Web Authentication Config`, `JWT Auth & Security Guidelines`, `Admin Ticket Tier Management`, `API Checkout & Booking Pipeline`, `Event Discovery & Detail API`?**
  _High betweenness centrality (0.101) - this node is a cross-community bridge._
- **Why does `Session` connect `Shield Web Authentication Config` to `Network & Security Configuration`?**
  _High betweenness centrality (0.096) - this node is a cross-community bridge._
- **Why does `CheckoutController` connect `Shield Web Authentication Config` to `Admin & Public Web Controllers`?**
  _High betweenness centrality (0.065) - this node is a cross-community bridge._
- **Are the 29 inferred relationships involving `View` (e.g. with `.index()` and `.edit()`) actually correct?**
  _`View` has 29 INFERRED edges - model-reasoned connections that need verification._
- **Are the 8 inferred relationships involving `Auth` (e.g. with `.loginRedirect()` and `.confirmPayment()`) actually correct?**
  _`Auth` has 8 INFERRED edges - model-reasoned connections that need verification._
- **What connects `DocTypes`, `Kint`, `Optimize` to the rest of the system?**
  _45 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Admin & Public Web Controllers` be split into smaller, more focused modules?**
  _Cohesion score 0.0625 - nodes in this community are weakly interconnected._