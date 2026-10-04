![TenantForge SaaS Foundation - MULTI-TENANT LARAVEL FOUNDATION](docs/images/portfolio-banner.svg)

# TenantForge SaaS Foundation

**A production-oriented multi-tenant SaaS foundation for Laravel with tenant isolation, subscription billing, administration and operational workflows.**

TenantForge is a Laravel and Filament application foundation for building organisation-based SaaS products on a shared database architecture. It brings together tenant lifecycle management, Stripe-backed subscription operations, feature-aware plans, administration, support workflows and containerised local infrastructure in one codebase.

> **Project provenance:** TenantForge is a derivative development based on the open-source `wallacemartinss/core_tenant` project. The upstream foundation and its original author, Wallace Martins, are credited below. This repository preserves that provenance while developing the system under a distinct technical identity.

## Product architecture and engineering highlights

A multi-tenant SaaS foundation for Laravel applications that need tenant onboarding, subscription billing, support, and administration.

- **Architecture:** A shared-database tenancy model layers organisation lifecycle, tenant-aware application access, Filament administration, Stripe subscription workflows, and container-oriented local infrastructure.
- **Distinctive engineering:** The core engineering challenge is maintaining tenant boundaries across identity, data access, plan entitlements, billing events, and support operations; upstream foundation attribution is retained.

## What the system demonstrates

The codebase provides working examples of several concerns that commonly have to be coordinated in a SaaS backend:

- organisation/company tenant management in a single database;
- separate administrative and tenant-facing Filament surfaces;
- Stripe customer, product, price, subscription, coupon and refund workflows;
- plan and feature modelling;
- tenant registration and account provisioning;
- support-ticket workflows with typed status, priority and category states;
- profile and theme customisation;
- queue/job monitoring;
- Docker-based application infrastructure;
- MySQL and PostgreSQL-oriented configuration;
- Laravel authentication and application services.

## Architecture

```text
                         TenantForge
                              |
              +---------------+---------------+
              |                               |
       Administration                    Tenant Surface
              |                               |
     +--------+---------+            +--------+---------+
     |        |         |            |        |         |
  Tenants   Plans    Billing      Profile  Support   Features
     |        |         |            |        |         |
     +--------+---------+------------+--------+---------+
                              |
                       Laravel Domain Layer
                              |
              +---------------+---------------+
              |                               |
        Stripe / Cashier                Shared SQL DB
              |                               |
        subscriptions,                 tenant-scoped
        prices, refunds                 application data
                              |
                    Docker / Queue Runtime
```

The project uses a shared-database tenancy model rather than one database per customer. Application code is therefore responsible for maintaining tenant-aware data boundaries.

## Core workflows

### Tenant onboarding

```text
Registration
    -> tenant/company creation
    -> account provisioning
    -> Stripe customer integration
    -> plan selection
    -> subscription lifecycle
    -> tenant application access
```

### Subscription operations

Administrators can model products, prices and features while the tenant-facing workflow connects account selection to Stripe subscription management. The repository also contains typed enums and application logic for subscription state, cancellation, promotion and refund handling.

### Support operations

Tenant support is represented as an application workflow rather than an external placeholder. Ticket priority, status and type are explicit domain states and are exposed through the Filament application surfaces.

## Technology

| Layer | Technology |
| --- | --- |
| Backend | PHP 8.2+, Laravel 11 |
| Admin/application UI | Filament 3 |
| Authentication | Laravel Fortify |
| Billing | Laravel Cashier + Stripe PHP SDK |
| Databases | MySQL / PostgreSQL |
| Containers | Docker / Docker Compose |
| Testing | PHPUnit |
| Code quality | Laravel Pint, CaptainHook |
| Frontend toolchain | Vite / Node.js |

## Code map and evidence

| Concern | Location | Evidence in this repository |
|---|---|---|
| Tenant and account models | `app/Models/Organization.php`, `app/Models/User.php` | organisation/user relationships and Filament tenancy integration |
| Administration and tenant panels | `app/Providers/Filament/`, `app/Filament/` | separate admin/application resources, pages and widgets |
| Billing workflows | `app/Services/Stripe/`, `app/Filament/Billing/`, `app/Http/Controllers/StripeWebhookController.php` | Cashier-backed checkout, subscription, refund and webhook handling |
| Persistence contract | `database/migrations/` | organization, billing, support, webhook and job-related tables |
| Automated checks | `tests/Unit/TenantForgeDomainEnumTest.php` and Laravel example tests | enum invariants plus application/unit smoke coverage; external Stripe flows are not integration-tested here |

## Repository structure

```text
app/                Laravel application and domain code
bootstrap/          Framework bootstrap
config/             Runtime and integration configuration
database/           Migrations, factories and seeders
resources/          Application views and frontend resources
routes/             HTTP/application routes
storage/            Runtime storage structure
tests/              Automated tests
Dockerfile*         Container definitions
docker-compose.yml  Local service orchestration
```

## Getting started

### Requirements

- PHP 8.2+
- Composer
- Node.js / npm
- Docker and Docker Compose for the containerised workflow
- a Stripe test account for billing functionality

### Install

```bash
git clone https://github.com/Masterleeaus/Tenant-Forge.git
cd Tenant-Forge
composer install
cp .env.example .env
php artisan key:generate
npm install
npm run build
```

Configure the database and Stripe **test** credentials in `.env`, then initialise the application:

```bash
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Alternatively, use the included Docker Compose environment:

```bash
docker compose up -d
```

The root route redirects to `/app`; the Stripe and Evolution webhook entry points are `/stripe/webhook` and `/evolution/webhook`.

For local Stripe webhook testing, configure the Stripe CLI to forward events to the application's `/stripe/webhook` endpoint and place the generated webhook secret in the environment configuration.

## Engineering notes

### Tenant boundaries

A shared database simplifies deployment and cross-tenant platform administration but makes tenant scoping a critical application invariant. Any extension to the system should preserve tenant ownership checks at query and action boundaries.

### Billing boundaries

Stripe remains the external payment authority. Local records should be treated as application projections of billing state rather than a substitute source of truth for payment events.

### Configuration and secrets

Do not commit real database credentials, Stripe secrets or webhook signing secrets. Use `.env.example` only as a configuration contract and keep runtime secrets outside source control.

## Limitations

TenantForge is an engineering foundation, not a finished vertical SaaS product. Production deployment requires environment-specific security review, backup/restore policy, monitoring, rate limiting, tenant-isolation testing and payment-flow validation.

The repository demonstrates application architecture and integration patterns; it does not claim PCI certification or guarantee that a deployment is secure solely because it uses this codebase.

## Status

**Active technical foundation.** The current implementation is suitable for experimentation and continued development of organisation-based SaaS applications.

## Provenance and attribution

This repository is derived from the MIT-licensed `wallacemartinss/core_tenant` project by **Wallace Martins**. Upstream architecture and code remain subject to their original license and attribution requirements. Subsequent development and the TenantForge project identity are maintained by **Jason Lee / @Masterleeaus**.

The provenance statement is intentionally retained so that downstream users can distinguish upstream work from subsequent development.

## Author / maintainer

**Jason Lee** - [@Masterleeaus](https://github.com/Masterleeaus)

## License

Retain the upstream MIT license and all legally required attribution when redistributing derivative source code.

