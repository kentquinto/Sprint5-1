<h1 align="center">🃏 TCG Manager</h1>

<p align="center">
  A full-stack tournament management web app for Trading Card Game players and organizers.
  <br />
  Built with Laravel 11, Tailwind CSS, and MySQL.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 11">
  <img src="https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2">
  <img src="https://img.shields.io/badge/TailwindCSS-3-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/MySQL-8-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
</p>

---

## 📸 Screenshots

<table>
  <tr>
    <td align="center"><strong>🏠 Home</strong></td>
    <td align="center"><strong>📋 Events Index</strong></td>
  </tr>
  <tr>
    <td><img src="screenshots/Screenshot%202026-05-29%20at%2011.16.28.png" alt="Home Page"></td>
    <td><img src="screenshots/Screenshot%202026-05-29%20at%2011.17.15.png" alt="Events Index"></td>
  </tr>
  <tr>
    <td align="center"><strong>🎴 Event Detail</strong></td>
    <td align="center"><strong>📊 Dashboard</strong></td>
  </tr>
  <tr>
    <td><img src="screenshots/Screenshot%202026-05-29%20at%2011.17.48.png" alt="Event Detail"></td>
    <td><img src="screenshots/Screenshot%202026-05-29%20at%2011.18.14.png" alt="Dashboard"></td>
  </tr>
  <tr>
    <td align="center"><strong>👤 Public Profile</strong></td>
    <td align="center"><strong>⚙️ Profile Settings</strong></td>
  </tr>
  <tr>
    <td><img src="screenshots/Screenshot%202026-05-29%20at%2011.20.32.png" alt="Public Profile"></td>
    <td><img src="screenshots/Screenshot%202026-05-29%20at%2011.18.30.png" alt="Profile Settings"></td>
  </tr>
  <tr>
    <td align="center"><strong>➕ Create Event</strong></td>
    <td></td>
  </tr>
  <tr>
    <td><img src="screenshots/Screenshot%202026-05-29%20at%2011.18.47.png" alt="Create Event"></td>
    <td></td>
  </tr>
</table>

---

## 🧩 What is TCG Manager?

TCG Manager is a web application where players can **discover, create, and join Trading Card Game tournaments**. Whether you're a Yu-Gi-Oh! duelist, a Pokémon trainer, or a Magic: The Gathering planeswalker — there's a place for you here.

Organizers can create and manage events. Players can browse, filter, and join. Everyone gets a profile. No extra tools needed — just show up and play.

---

## ✨ Features

### For Players
- 🔍 **Browse Events** — Search by title, filter by game, date, price, or status
- 🎮 **Game-Themed Cards** — Each TCG has its own banner image and color scheme
- 📋 **Event Detail Pages** — Full info: location, date, entry fee, player count, participants list
- ✅ **Join & Leave Events** — One click to register or withdraw
- 👤 **Public Profiles** — View any player's bio, country, favorite game, created events, and events they've joined

### For Organizers
- ➕ **Create Events** — Set title, description, location, date/time, entry fee, max players, and game
- ✏️ **Edit & Delete** — Full control over events you created
- 📊 **Dashboard** — See all events you've created and all events you're participating in at a glance

### Platform
- 🔐 **Authentication** — Register, login, logout via Laravel Breeze
- 📧 **Email Verification** — Dashboard access requires a verified email
- 🛡️ **Policy-Based Authorization** — Only creators can edit or delete their own events
- 🚫 **Smart Guard Checks** — Can't join your own event, can't join a full event, can't join a finished or cancelled event
- 📄 **Pagination** — Events index paginates with query string preservation
- ⚡ **Flash Messages** — Instant success and error feedback after every action

---

## 🎴 Supported Games

| Game | Game | Game |
|---|---|---|
| Yu-Gi-Oh! | Pokémon | Magic: The Gathering |
| One Piece | Dragon Ball Super | Disney Lorcana |
| Star Wars: Unlimited | Final Fantasy TCG | Flesh and Blood |
| Digimon Card Game | League of Legends Riftbound | Gundam Card Game |
| Altered | | |

Each game has its own **banner image**, **badge color**, and **accent theme** applied across the event cards and detail pages.

---

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| Backend Framework | Laravel 11 |
| Authentication | Laravel Breeze |
| Database | MySQL |
| ORM | Eloquent |
| Frontend Styling | Tailwind CSS 3 |
| Asset Bundling | Vite |
| Templating | Blade |
| Authorization | Laravel Policies |

---

## ⚙️ Installation

### Requirements
- PHP 8.2+
- Composer
- Node.js & npm
- MySQL

### Steps

```bash
# 1. Clone the repository
git clone https://github.com/your-username/tcg-manager.git
cd tcg-manager

# 2. Install PHP dependencies
composer install

# 3. Install JS dependencies
npm install

# 4. Copy the environment file and configure it
cp .env.example .env

# 5. Generate the application key
php artisan key:generate
```

Edit `.env` and set your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tcg_tournaments
DB_USERNAME=root
DB_PASSWORD=your_password
```

```bash
# 6. Run migrations and seed the database
php artisan migrate:fresh --seed

# 7. Build frontend assets
npm run dev

# 8. Start the development server
php artisan serve
```

Visit `http://localhost:8000`

---

## 🌱 Seeded Test Data

Running `--seed` creates:

- **10 test users** (`tester1@test.com` through `tester10@test.com`) — password: `password`
- **13 games** across all major TCGs
- **13 events** covering all statuses: upcoming, ongoing, finished, cancelled
- **Participants** distributed across events

Log in with any tester account to explore the full app immediately.

---

## 🗂️ Project Structure

```
app/
├── Http/Controllers/
│   ├── EventController.php        # CRUD for events + filters
│   ├── ParticipantController.php  # Join / leave logic
│   ├── DashboardController.php    # User dashboard
│   └── ProfileController.php      # Profile view & edit
├── Models/
│   ├── Event.php                  # hasMany participants, belongsTo game/creator
│   ├── User.php                   # belongsToMany events, hasMany createdEvents
│   ├── Game.php                   # hasMany events
│   └── Participant.php            # Pivot model for event_id + user_id
└── Policies/
    └── EventPolicy.php            # update/delete restricted to creator

config/
└── game_colors.php                # Per-game color & banner image config

database/
├── migrations/                    # Schema for users, games, events, participants
└── seeders/                       # GameSeeder, UserSeeder, EventSeeder

resources/views/
├── layouts/                       # app.blade.php, navigation.blade.php
├── events/                        # index, show, create, edit, _status_badge
├── profile/                       # show, edit
└── dashboard.blade.php
```

---

## 🔑 Key Technical Highlights

- **Eager Loading** — All event queries use `with('game', 'creator', 'participants')` to eliminate N+1 queries
- **Policy Authorization** — `EventPolicy` enforces that only the event creator can edit or delete
- **Pivot Table** — The `participants` table handles the many-to-many relationship between users and events with a unique constraint to prevent duplicate joins
- **Query Builder `when()`** — Filters in `EventController::index()` are applied conditionally using `when()`, keeping the query chain clean
- **Carbon Casts** — `date_time` is cast to `datetime` on the Event model, eliminating `Carbon::parse()` calls in views
- **Game Config** — All game-specific colors and banner images live in `config/game_colors.php`, keeping presentation data out of views and controllers
- **Flash Messages** — Every write operation (create, update, delete, join, leave) returns a session flash message rendered in the layout

---

## 👤 Author

**Kent Quinto**
Full-Stack Web Development Student — IT Academy Barcelona
Built as a Sprint 4 capstone project.

---

## 📄 License

This project is open source and available under the [MIT License](LICENSE).
