<h1 align="center">🃏 TCG Manager — REST API</h1>

<p align="center">
  A RESTful API for organizing and discovering Trading Card Game tournaments.<br/>
  Built with Laravel 13, tested with Pest, and documented with Scribe.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-13-FF2D20?style=for-the-badge&logo=laravel&logoColor=white">
  <img src="https://img.shields.io/badge/PHP-8.3+-777BB4?style=for-the-badge&logo=php&logoColor=white">
  <img src="https://img.shields.io/badge/Passport-13-orange?style=for-the-badge">
  <img src="https://img.shields.io/badge/Tests-97%20passing-brightgreen?style=for-the-badge">
</p>

---

## What is this?

TCG Manager is a backend REST API that lets players register accounts, browse tournaments, and join events across 13 supported Trading Card Games. Organizers can create and manage their own events with full authorization control.

This is a **Sprint 5 capstone project** built at IT Academy Barcelona, developed using **TDD** (Test-Driven Development) — every endpoint has tests written before implementation.

---

## Features

- **Role-based access control** — two roles: `player` (join events) and `organizer` (create, edit, delete events)
- **Bearer token authentication** via Laravel Passport (register, login, logout)
- **Event management** — organizers can create, update and delete their own tournaments; all users can browse
- **Smart filtering** — filter events by game, status, price, date, location or search term
- **Participant system** — players join and leave events with full business rule enforcement
- **Personal dashboard** — your organized events and events you've joined
- **Leaderboards** — top players, top organizers and most popular games
- **Interactive API docs** — Try It Out, Postman collection and OpenAPI spec via Scribe
- **Pagination** — all list endpoints return paginated responses with metadata

---

## Tech Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 13 |
| Language | PHP 8.3+ |
| Authentication | Laravel Passport 13 (OAuth2 Bearer tokens) |
| Database | SQLite (local) · MySQL (production) |
| Testing | Pest 4.7 — 97 tests, 281 assertions |
| Documentation | Scribe 5.11 |
| Architecture | REST, MVC, Repository-light, TDD |

---

## Getting Started

### Prerequisites

- Git
- PHP 8.3+ with extensions `pdo_sqlite` and `openssl` enabled
- Composer
- SQLite (built into PHP) or MySQL

### 1 — Clone and install

```bash
git clone https://github.com/kentquinto/Sprint5-1.git
cd Sprint5-1
composer install
```

### 2 — Environment

```bash
cp .env.example .env
php artisan key:generate
```

Open `.env` and set:

```env
APP_URL=http://localhost:8000

# SQLite (default — no extra setup needed)
DB_CONNECTION=sqlite
```

> For **MySQL**, use:
> ```env
> DB_CONNECTION=mysql
> DB_HOST=127.0.0.1
> DB_PORT=3306
> DB_DATABASE=tcgmanager
> DB_USERNAME=root
> DB_PASSWORD=your_password
> ```

### 3 — Database

```bash
# Create the SQLite file (skip if using MySQL)
# Mac/Linux:
touch database/database.sqlite
# Windows:
# echo. > database/database.sqlite

# Run migrations — Passport OAuth tables are already included in this repo
php artisan migrate

# Generate Passport encryption keys (stored in storage)
php artisan passport:keys --force

# Create the personal access client Passport needs to issue Bearer tokens
php artisan passport:client --personal --name="TCGManager" --no-interaction

# Seed: 13 games + sample users, events and participants
php artisan db:seed
```

> **Important — do NOT run `passport:install`** on this project. It will re-publish the Passport migrations that are already committed to the repo, creating duplicates that break migrations and tests.
>
> Use `passport:keys` + `passport:client` instead (shown above) — these two commands do exactly what is needed without touching the migrations.

### 4 — Seed credentials

The seeder creates 10 test users, all with the same password:

| Email | Password | Role |
|---|---|---|
| `tester1@test.com` | `password` | `organizer` |
| `tester2@test.com` | `password` | `organizer` |
| `tester3@test.com` | `password` | `organizer` |
| `tester4@test.com` | `password` | `organizer` |
| `tester5@test.com` | `password` | `organizer` |
| `tester6@test.com` | `password` | `player` |
| `tester7@test.com` | `password` | `player` |
| `tester8@test.com` | `password` | `player` |
| `tester9@test.com` | `password` | `player` |
| `tester10@test.com` | `password` | `player` |

### 5 — Run

```bash
php artisan serve
```

| Resource | URL |
|---|---|
| API base | `http://localhost:8000/api` |
| Interactive docs | `http://localhost:8000/docs` |

---

## API Documentation

After running the server, open **`http://localhost:8000/docs`** for full interactive documentation including:

- **Try It Out** — send real requests directly from the browser
- **Example requests** in `curl` and JavaScript
- **Example responses** for every HTTP status code
- **Postman collection** — `public/docs/collection.json`
- **OpenAPI 3.0 spec** — `public/docs/openapi.yaml`

To regenerate docs after code changes:

```bash
php artisan scribe:generate
```

---

## Endpoints

### Authentication

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `POST` | `/api/register` | Public | Create account — returns Bearer token |
| `POST` | `/api/login` | Public | Log in — returns Bearer token |
| `POST` | `/api/logout` | 🔒 | Revoke current token |

### Profile

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET` | `/api/me` | 🔒 | Get your own profile (includes `role`) |
| `PUT` | `/api/me` | 🔒 | Update name, bio, country, favourite game |

### Events

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET` | `/api/events` | Public | Paginated list with 6 filter options |
| `GET` | `/api/events/{id}` | Public | Single event with full details |
| `POST` | `/api/events` | 🔒 | Create an event |
| `PUT` | `/api/events/{id}` | 🔒 | Update your event |
| `DELETE` | `/api/events/{id}` | 🔒 | Delete your event |

### Participants

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET` | `/api/events/{id}/participants` | 🔒 | List all participants of an event |
| `POST` | `/api/events/{id}/participants` | 🔒 | Join an event |
| `DELETE` | `/api/events/{id}/participants` | 🔒 | Leave an event |

### Dashboard

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET` | `/api/me/organized-events` | 🔒 | Events you have created |
| `GET` | `/api/me/joined-events` | 🔒 | Events you have joined |

### Players

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET` | `/api/players/{id}` | 🔒 | Player profile |

### Games & Statistics

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET` | `/api/games` | Public | All 13 supported TCGs |
| `GET` | `/api/stats/players` | Public | Top 10 players by events joined |
| `GET` | `/api/stats/games` | Public | Games ranked by event count |
| `GET` | `/api/stats/organizers` | Public | Top 10 organizers by events created |

---

## Authentication Flow

All 🔒 endpoints require a Bearer token in the `Authorization` header.

**Step 1 — Get a token:**

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email": "test@example.com", "password": "yourpassword"}'
```

```json
{
  "message": "Logged in successfully",
  "token": "eyJ0eXAiOiJKV1QiLCJhbGci..."
}
```

**Step 2 — Use it:**

```bash
curl http://localhost:8000/api/me \
  -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGci..."
```

**Step 3 — Log out (revokes the token permanently):**

```bash
curl -X POST http://localhost:8000/api/logout \
  -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGci..."
```

---

## Event Filters

`GET /api/events` accepts the following query parameters (all optional, combinable):

| Parameter | Type | Example | Description |
|---|---|---|---|
| `game` | integer | `1` | Filter by game ID (see `/api/games`) |
| `status` | string | `upcoming` | `upcoming` · `ongoing` · `finished` · `cancelled` |
| `price` | string | `free` | `free` or `paid` |
| `date` | string | `2026-12-01` | Events on a specific day (YYYY-MM-DD) |
| `search` | string | `Pokémon` | Partial title match |
| `location` | string | `Barcelona` | Partial location match |
| `page` | integer | `2` | Page number (20 per page) |

**Example:**
```
GET /api/events?game=1&status=upcoming&price=free
```

---

## Roles

There are two user roles, set at registration and stored on the user account:

| Role | Permissions |
|---|---|
| `player` | Browse events, join/leave events, update own profile |
| `organizer` | Everything a player can do + create, edit and delete their own events |

Register as an organizer by passing `"role": "organizer"` in the register request body. Defaults to `player` if omitted.

A player attempting to create, update, or delete an event receives a `403 Forbidden`.

---

## Business Rules

These are enforced server-side — they cannot be bypassed:

- You **cannot join your own event**
- You **cannot join the same event twice**
- You **cannot join a full event** (enforces `max_players`)
- You **cannot join a finished or cancelled event**
- Only the **event creator** can update or delete it — anyone else gets a `403 Forbidden` (enforced via `EventPolicy`)

---

## HTTP Status Codes

| Code | Meaning | When |
|---|---|---|
| `200` | OK | Request succeeded |
| `201` | Created | Resource was created |
| `204` | No Content | Deleted successfully (no body) |
| `401` | Unauthenticated | Missing or invalid Bearer token |
| `403` | Forbidden | Authenticated but not authorized |
| `404` | Not Found | Resource does not exist |
| `422` | Unprocessable | Validation failed — `errors` object included |

---

## Running Tests

```bash
php artisan test
```

```
Tests:    97 passed
Assertions: 281
Duration:  ~1.5s
```

The test suite covers every endpoint including authentication, validation, authorization, business rule enforcement and edge cases. Written with **Pest 4.7** following TDD — tests were written before implementation.

---

## Project Structure

```
app/
├── Http/
│   ├── Controllers/Api/
│   │   ├── AuthController.php        # register, login, me, update, logout
│   │   ├── EventController.php       # index, show, store, update, destroy
│   │   ├── ParticipantController.php # index, store (join), destroy (leave)
│   │   ├── DashboardController.php   # organizedEvents, joinedEvents
│   │   ├── PlayerController.php      # show (public profile)
│   │   ├── GameController.php        # index
│   │   └── StatisticsController.php  # players, games, organizers
│   └── Resources/
│       ├── EventResource.php         # controls event JSON shape
│       ├── UserResource.php          # private profile shape
│       ├── PublicProfileResource.php # public profile shape
│       └── ParticipantResource.php   # participant shape
├── Models/
│   ├── Event.php     # belongsTo Game, User · belongsToMany participants
│   ├── User.php      # hasMany createdEvents · belongsToMany events
│   └── Game.php      # hasMany events
└── Policies/
    └── EventPolicy.php  # update/delete: only the creator

database/
├── migrations/   # users, games, events, participants (pivot)
└── seeders/      # 13 games + sample data

routes/
└── api.php       # 20 routes — public + auth:api protected group

tests/Feature/Api/
└── ...           # 97 Pest tests
```

---

## Supported TCGs

Yu-Gi-Oh! · Pokémon · Magic: The Gathering · One Piece · League of Legends Riftbound · Disney Lorcana · Dragon Ball Super Card Game · Star Wars: Unlimited · Final Fantasy TCG · Flesh and Blood · Digimon Card Game · Gundam Card Game · Altered

---

## CORS

CORS is enabled for all `api/*` routes. By default, **all origins are allowed** (`*`) — suitable for local development.

To restrict access to a specific frontend origin, edit `config/cors.php`:

```php
'allowed_origins' => ['http://localhost:5173'],
```

---

## Author

**Kent Quinto**
Full-Stack Web Development Student — IT Academy Barcelona
Sprint 5 capstone project.

---

## License

Open source under the [MIT License](LICENSE).
