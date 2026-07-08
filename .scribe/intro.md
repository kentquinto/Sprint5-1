# Introduction

REST API for TCG Manager — a tournament management platform for Trading Card Game players and organizers. Supports event discovery, creation, and participation across 13 supported games including Pokémon, Yu-Gi-Oh!, Magic: The Gathering, and more.

<aside>
    <strong>Base URL</strong>: <code>http://localhost:8000</code>
</aside>

Welcome to the TCG Manager API. This API allows you to browse and manage trading card game tournaments.

<aside>Use the <b>Try It Out</b> button on any endpoint to test it directly from this page. For protected endpoints, click <b>Authorize</b> at the top and paste your Bearer token.</aside>

## Roles

There are two user roles in this API:

- **`player`** — can browse events, join/leave events, and manage their own profile
- **`organizer`** — everything a player can do, plus create, edit and delete their own events

Pass `"role": "organizer"` in the register request body to sign up as an organizer. Defaults to `player` if omitted.

## How to get a token

1. Register a new account via `POST /api/register`, or log in via `POST /api/login`.
2. Copy the `token` from the response.
3. Click **Authorize** and paste it in the field.

All protected endpoints will then use your token automatically.

