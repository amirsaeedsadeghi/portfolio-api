# Portfolio API & SPA

**A full-stack portfolio application built with Laravel (backend) and React (frontend) to showcase my technical skills as a Full-Stack Developer.**

The live website is available at: **[https://amirsaaedkomjani.ir](https://amirsaaedkomjani.ir)**

This project is not just a personal portfolio; it is also a demonstration of **Clean Architecture**, **modern MVC**, and **production-grade design patterns**. It highlights my ability to structure scalable APIs, implement advanced OOP concepts, and build a fully custom UI from scratch.

---

## Highlights

-   **Backend (Laravel 10)**

    -   Modern **MVC + Clean Architecture** separation
    -   Service layer and Repository pattern with **Interfaces + Factories (DIP)**
    -   **JWT Authentication** (stateless, secure, scalable)
    -   Centralized **Exception Handling** with a uniform error schema
    -   **Form Requests** for validation + authorization (+ Scribe hints via `bodyParameters()`)
    -   **Policies** enforced in the **Service layer** (domain-driven authorization)
    -   **Filters** (dynamic query filtering & sorting) and **Traits** for reusable behaviors
    -   Rich **API Documentation** generated with **Scribe 5.x** (HTML + Postman + OpenAPI)

-   **Frontend (React 19 + Tailwind 4)**

    -   Built entirely from scratch, **custom responsive UI**
    -   **Compound Component Pattern** for reusable UI building blocks
    -   **React Router v6.30** for structured navigation
    -   **Tailwind CSS v4** for modern utility-first styling

-   **General**
    -   Extensive **OOP & Design Patterns**
    -   **Unit & Feature Tests** with PHPUnit (to be pushed before public release)
    -   Fully documented classes and API endpoints
    -   Roadmap: migration to a **custom mini-framework** (in-house ORM, advanced OOP, disciplined Git flow)

> Note: In the initial README version, some sections were intentionally concise. This revision adds architectural details (Policies in Services, Filters, Traits, PHP 8.1 Enums, and custom Artisan generators) for completeness.

---

## Architecture Overview

This project implements a **modern layered architecture** on top of Laravel’s MVC, following **Clean Architecture** principles. Authorization **Policies** are applied **within the Service layer** to keep controllers thin and to enforce business rules close to the domain logic.

```plaintext
           +----------------------------+
           |      Presentation Layer    |
           |   (Routes, Controllers)    |
           +----------------------------+
                        |
                        v
           +----------------------------+
           |      Application Layer     |
           |  Services, Filters,        |
           |         (Policies)         |
           +----------------------------+
                        |
                        v
           +----------------------------+
           |        Domain Layer        |
           | Repositories (Interfaces), |
           |   Repository Factories     |
           +----------------------------+
                        |
                        v
           +----------------------------+
           |      Infrastructure        |
           |   Eloquent Models, DB      |
           +----------------------------+
```

### Layers & Responsibilities

-   **Controllers (Presentation)**  
    Accept HTTP requests, delegate to Services, and return standardized `ApiResponse`. Controllers remain thin, orchestration-only.

-   **Services (Application)**  
    Encapsulate business flows/use-cases. Apply **Policies** here (authorize operations against the current user/role). Coordinate Repositories, Filters, and Resources.

-   **Repositories (Domain)**  
    Abstract data access. Declared as **Interfaces** and resolved via **Factories** (bound in `RepositoriesServiceProvider`) to decouple the domain from the infrastructure and make testing straightforward.

-   **Filters (Application)**  
    Dynamic query filtering/sorting/pagination as composable objects (see detailed section below). Provide whitelisted operators and fields to prevent over-filtering or injection.

-   **Form Requests (Application)**  
    Per-endpoint validation + authorization. Provide documentation hints to Scribe via `bodyParameters()`.

-   **Resources (Transformers)**  
    Shape outgoing JSON (eg. **camelCase** for client).

-   **Exception Handling**  
    Map framework/domain exceptions (`ModelNotFoundException`, `ValidationException`, `MethodNotAllowedHttpException`, …) to a **uniform error schema** with `error.code`, `message`, and `meta.request_id`.

---

## Tech Stack

-   **Backend:** Laravel 10, PHP 8.2, MySQL
-   **Auth:** JWT (`tymon/jwt-auth`)
-   **Docs:** Scribe 5.x (HTML, Postman, OpenAPI)
-   **Frontend:** React 19, React Router 6.30, Tailwind CSS 4
-   **Testing:** PHPUnit (unit + feature tests)

---

## Installation

### Prerequisites

-   PHP 8.2+, Composer
-   MySQL
-   Node.js (optional, for frontend build)

### Setup

```bash
git clone https://github.com/amirsaeedsadeghi/portfolio-api.git
cd portfolio-api
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate
php artisan jwt:secret
```

Update `.env`:

```env
APP_URL=http://localhost
DB_DATABASE=portfolio
DB_USERNAME=root
DB_PASSWORD=secret

JWT_SECRET=your_secret_key
SCRIBE_AUTH_KEY="Bearer eyJhbGciOi..."   # Dev-only, for Scribe Try It Out
```

Run:

```bash
php artisan serve
```

---

## API Documentation (Scribe)

-   Endpoints: `/api/v1/*`
-   Docs (type=laravel):
    -   HTML: `GET /docs`
    -   Postman Collection: `GET /docs.postman`
    -   OpenAPI: `GET /docs.openapi`
-   Error responses follow a uniform schema (see “Error Schema”).

Scribe hints via `bodyParameters()` are added on key **Form Requests** (eg. Login/Project/ProjectImage), so payloads and examples are explicit in the generated docs.

---

## Authentication

**JWT Bearer Authentication**

```http
Authorization: Bearer <your_token>
```

Public endpoints: `register`, `login`, `health`  
All other endpoints require authentication (**Policies** are enforced within Services).  
Endpoints that are intentionally public are marked with `@unauthenticated` in their DocBlocks.

---

## API Response Codes

| HTTP Status | Code               | Description                                           |
| ----------- | ------------------ | ----------------------------------------------------- |
| 200         | OK                 | Successful request with data returned.                |
| 201         | CREATED            | Resource successfully created.                        |
| 204         | NO_CONTENT         | Successful request, no content returned.              |
| 400         | BAD_REQUEST        | Malformed request.                                    |
| 401         | UNAUTHENTICATED    | Authentication required or token invalid.             |
| 403         | FORBIDDEN          | User is authenticated but not authorized.             |
| 404         | NOT_FOUND          | Resource not found.                                   |
| 405         | METHOD_NOT_ALLOWED | HTTP method not allowed for this route.               |
| 422         | VALIDATION_ERROR   | Input validation failed (with field-specific errors). |
| 429         | RATE_LIMITED       | Too many requests in a short period.                  |
| 500         | INTERNAL_ERROR     | Unexpected server error.                              |

---

## Error Schema

All errors follow a **standardized JSON format**:

```json
{
    "status": false,
    "message": "Validation failed.",
    "error": {
        "code": "VALIDATION_ERROR",
        "message": "The given data was invalid.",
        "details": {
            "email": ["The email field is required."]
        }
    },
    "meta": {
        "request_id": "uuid"
    }
}
```

-   `status`: Always `false` for errors.
-   `message`: Human-readable message.
-   `error.code`: Machine-readable error code.
-   `error.message`: Detailed error description.
-   `error.details`: Extra info (e.g., validation errors).
-   `meta.request_id`: Unique ID for request tracing in logs.

---

## Repositories & Services (Interfaces + Implementations)

This codebase adopts **DIP**: domain dependencies are expressed as **interfaces**, wired to concrete implementations via factories and a service provider.

-   **Repository contract** (example):

    ```php
    interface ProjectRepositoryInterface
    {
        public function find(int $id): ?Project;
        public function paginate(int $perPage = 15): LengthAwarePaginator;
        public function create(array $data): Project;
        public function update(Project $project, array $data): Project;
        public function delete(Project $project): void;
    }
    ```

-   **Repository implementation** wires to Eloquent and is returned by a **Factory**:

    ```php
    final class ProjectRepositoryFactory
    {
        public function make(): ProjectRepositoryInterface
        {
            return new EloquentProjectRepository(); // decoupled behind the interface
        }
    }
    ```

-   **Service** uses the repository contract and enforces **Policies**:

    ```php
    final class ProjectService
    {
        public function __construct(private ProjectRepositoryInterface $repo) {}

        public function create(User $actor, array $data): Project
        {
            Gate::authorize('create', Project::class); // Policy in Service
            return $this->repo->create($data);
        }
    }
    ```

> **Important:** For each domain, **define both** the repository **interface** and the **concrete implementation** with the methods you require, then wire them in a **Factory** and bind in the `RepositoriesServiceProvider`.

---

## Query Filters (accurate to implementation)

The filtering layer is implemented via an abstract `QueryFilter` and a `QueryFilterInterface`, enabling **dynamic method mapping**, **whitelisted LIKE/exact filters**, and **safe sorting**. It accepts either an explicit input array or defaults to `request()->all()`.

### Core behavior

-   `apply(Builder $builder)`: sets the Eloquent builder, iterates over all input keys, and invokes a matching method if it exists (eg. calling `sort()` or `filter()`).
-   `filter(array $filter)`: supports **nested filter[] syntax**; for each `filter[key]=value`, it attempts to call a method named `key`, otherwise falls back to whitelists:
    -   if `key` is in `$likeFilters`, runs a `LIKE` query (supports `*` → `%` wildcard transformation).
    -   if `key` is in `$exactFilters`, runs an exact `where` match.
-   `__call($method, $arguments)`: final fallback—if an unknown filter key is present, but is whitelisted in `likeFilters`/`exactFilters`, it applies the proper behavior; otherwise, it **ignores unknown filters** (safe default).
-   `sort(string $value)`: comma-separated list of sortable fields; prefix with `-` for `DESC`. Sorting is strictly **whitelisted via `$sortable`**, which may contain plain column names or mappings like `['created_at' => 'users.created_at']`.
-   Date helpers:
    -   `createdAt(string $value)` and `updatedAt(string $value)` accept either a single date (`YYYY-MM-DD` → `>=`) or a comma-separated range (`start,end` → `whereBetween`).

### Configuration in concrete filters

Concrete filters (eg. `UserFilter`) extend `QueryFilter` and set their whitelists:

```php
final class UserFilter extends QueryFilter
{
    protected array $sortable    = ['name', 'email', 'createdAt' => 'created_at'];
    protected array $likeFilters = ['name', 'email'];
    protected array $exactFilters = ['role'];

    // Optional explicit methods override
    protected function role(string $value): Builder
    {
        return $this->builder->where('role', $value);
    }
}
```

### Usage examples

```http
GET /api/v1/users?filter[name]=*ali*
GET /api/v1/users?filter[role]=guest
GET /api/v1/users?filter[createdAt]=2024-01-01,2024-01-31
GET /api/v1/users?sort=-createdAt,name
GET /api/v1/users?page=2
```

> Notes:
>
> -   `*` wildcards are supported in LIKE queries (converted to `%`).
> -   Unknown or non-whitelisted filter keys are ignored by default (defensive posture).
> -   `$sortable` allows aliasing to fully-qualified DB columns.

---

## Form Requests (Validation + Authorization + Docs)

Each write endpoint (and many reads) are backed by **Form Requests** to keep validation and authorization consistent:

-   **Validation** rules and **authorization** logic per endpoint
-   **Scribe integration** using `bodyParameters()` to describe payload fields and examples
-   Example:

    ```php
    final class StoreProjectRequest extends FormRequest
    {
        public function authorize(): bool
        {
            return auth()->check();
        }

        public function rules(): array
        {
            return [
                'title' => ['required', 'string', 'min:3'],
                'summary' => ['nullable', 'string'],
                'url' => ['nullable', 'url'],
                'is_active' => ['boolean'],
            ];
        }

        public function bodyParameters(): array
        {
            return [
                'title' => ['description' => 'Project title', 'example' => 'Portfolio API'],
                'summary' => ['description' => 'Short description', 'example' => 'Backend of personal portfolio'],
                'url' => ['description' => 'Public URL', 'example' => 'https://example.com'],
                'is_active' => ['description' => 'Active flag', 'example' => true],
            ];
        }
    }
    ```

---

## Roles via PHP 8.1 Enums

Roles are modeled using **native PHP Enums** (type-safe and self-documenting):

```php
enum Role: string
{
    case Admin = 'admin';
    case User  = 'user';
}
```

These enums are consumed by **Policies** and **Services** to authorize operations in a clear, explicit manner.

---

## Traits: Normalization & Case (snake_case ⇄ camelCase)

To keep client payloads ergonomic, responses are normalized (eg. **camelCase**). Internal model attributes may be snake_case (Eloquent’s default).

-   Traits convert internal arrays to camelCase for outbound responses (and handle normalization).
-   **Note for Scribe:** code-gen may display both snake_case and camelCase in examples if a transformer isn’t applied at doc time. The runtime API returns **camelCase** consistently.

> This is intentional: the API prioritizes client ergonomics while preserving Laravel conventions internally.

---

## Type-safety via PHPDoc “Generics”

PHP lacks native generics; this codebase uses **PHPDoc templates** to aid static analysis (Psalm/PHPStan) and IDE autocompletion in factories and base repositories:

```php
/**
 * @template TRepo of ProjectRepositoryInterface
 */
final class ProjectRepositoryFactory
{
    /**
     * @return TRepo
     */
    public function make(): ProjectRepositoryInterface
    {
        // ...
    }
}
```

You may also see `@implements`, `@extends`, and `@template-covariant` annotations to express intent precisely.

---

## Custom Artisan Generators

The project provides **custom Artisan commands** to scaffold common domain artifacts, speeding up development and enforcing conventions:

```bash
php artisan make:repository {name}   # Generates Interface + Implementation + Factory stub
php artisan make:service {name}      # Generates Service class with typical methods
php artisan make:filter {name}       # Generates Filter with dynamic mapping + whitelists (like/exact) + sort
```

Each generator produces **boilerplate with PHPDoc** and TODOs for domain-specific methods. Repositories should declare the required contract **first**, then the concrete implementation must fulfill it.

---

## Testing

-   Tests are written using **PHPUnit**.
-   Run:
    ```bash
    php artisan test
    ```
-   Includes **unit tests** (services, repositories) and **feature tests** (auth, validation, error handling).

---

## Roadmap

-   [ ] Complete all `bodyParameters()` for Scribe requests
-   [ ] Add additional error examples (`@response 401/404/422`)
-   [ ] Set up CI/CD with GitHub Actions
-   [ ] Release custom mini-framework version (in-house ORM, advanced OOP, Git discipline)
-   [ ] Add performance benchmarks

---

## Git History Note

> **About the commit history**  
> This repository was initialized **after the MVP version of the project was completed**.  
> All features and architecture were developed before initializing Git, and thus the commit history starts from a single, well-documented initial commit.
>
> Going forward, all development will follow [Conventional Commits](https://www.conventionalcommits.org/) and standard Git branching practices (`feat/`, `fix/`, `refactor/`, etc.) to ensure a clean, traceable history.
>
> This decision was made intentionally to prioritize feature completeness and architectural clarity before version control tracking.

## License

MIT License
