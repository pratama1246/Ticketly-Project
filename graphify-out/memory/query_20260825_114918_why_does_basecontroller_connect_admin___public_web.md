---
type: "query"
date: "2026-08-25T11:49:18.190496+00:00"
question: "Why does BaseController connect Admin & Public Web Controllers to Shield Web Authentication Config, JWT Auth & Security Guidelines, Admin Ticket Tier Management, API Checkout & Booking Pipeline, and Event Discovery & Detail API?"
contributor: "graphify"
outcome: "useful"
source_nodes: ["BaseController", "Auth", "CheckoutController"]
---

# Q: Why does BaseController connect Admin & Public Web Controllers to Shield Web Authentication Config, JWT Auth & Security Guidelines, Admin Ticket Tier Management, API Checkout & Booking Pipeline, and Event Discovery & Detail API?

## Answer

Expanded from original query via vocab: ['base', 'controller', 'admin', 'auth', 'jwt', 'checkout', 'event', 'ticket']. BaseController (app/Controllers/BaseController.php) serves as the universal parent class for all 16 controllers across 4 namespaces (Admin, Public, User, Api). It preloads core helpers ('jwt', 'api', 'auth', 'url', 'form', 'text', 'html') at L38, directly bridging Web Shield auth, custom Mobile JWT auth, ticket catalog, and checkout processing without separate BaseApiController abstractions.

## Outcome

- Signal: useful

## Source Nodes

- BaseController
- Auth
- CheckoutController