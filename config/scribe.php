<?php

use Knuckles\Scribe\Config\AuthIn;
use Knuckles\Scribe\Config\Defaults;
use Knuckles\Scribe\Extracting\Strategies;

use function Knuckles\Scribe\Config\configureStrategy;

return [
    'title' => 'TCG Manager API',

    'description' => 'REST API for TCG Manager — a tournament management platform for Trading Card Game players and organizers. Supports event discovery, creation, and participation across 13 supported games including Pokémon, Yu-Gi-Oh!, Magic: The Gathering, and more.',

    'intro_text' => <<<'INTRO'
        Welcome to the TCG Manager API. This API allows you to browse and manage trading card game tournaments.

        <aside>Use the <b>Try It Out</b> button on any endpoint to test it directly from this page. For protected endpoints, click <b>Authorize</b> at the top and paste your Bearer token.</aside>

        ## How to get a token

        1. Register a new account via `POST /api/register`, or log in via `POST /api/login`.
        2. Copy the `token` from the response.
        3. Click **Authorize** and paste it in the field.

        All protected endpoints will then use your token automatically.
        INTRO,

    'base_url' => config('app.url'),

    'routes' => [
        [
            'match' => [
                'prefixes' => ['api/*'],
                'domains'  => ['*'],
            ],
            'include' => [],
            'exclude' => [],
        ],
    ],

    'type'  => 'static',
    'theme' => 'default',

    'static' => [
        'output_path' => 'public/docs',
    ],

    'laravel' => [
        'add_routes'       => true,
        'docs_url'         => '/docs',
        'assets_directory' => null,
        'middleware'       => [],
    ],

    'external' => [
        'html_attributes' => [],
    ],

    'try_it_out' => [
        'enabled'  => true,
        'base_url' => null,
        'use_csrf' => false,
        'csrf_url' => '/sanctum/csrf-cookie',
    ],

    'auth' => [
        'enabled'   => true,
        'default'   => false,
        'in'        => AuthIn::BEARER->value,
        'name'      => 'Authorization',
        'use_value' => env('SCRIBE_AUTH_KEY'),
        'placeholder' => '{YOUR_AUTH_TOKEN}',
        'extra_info' => 'Obtain a token by calling **POST /api/register** or **POST /api/login**. The token is returned in the `token` field of the response.',
    ],

    'example_languages' => [
        'bash',
        'javascript',
    ],

    'postman' => [
        'enabled'   => true,
        'overrides' => [],
    ],

    'openapi' => [
        'enabled'    => true,
        'version'    => '3.0.3',
        'overrides'  => [],
        'generators' => [],
    ],

    'groups' => [
        'default' => 'Endpoints',
        'order'   => [
            'Authentication',
            'Profile',
            'Events',
            'Participants',
            'Dashboard',
            'Players',
            'Games',
            'Statistics',
        ],
    ],

    'logo' => false,

    'last_updated' => 'Last updated: {date:F j, Y}',

    'examples' => [
        'faker_seed'    => 1234,
        'models_source' => ['factoryCreate', 'factoryMake', 'databaseFirst'],
    ],

    'strategies' => [
        'metadata' => [
            ...Defaults::METADATA_STRATEGIES,
        ],
        'headers' => [
            ...Defaults::HEADERS_STRATEGIES,
            Strategies\StaticData::withSettings(data: [
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json',
            ]),
        ],
        'urlParameters' => [
            ...Defaults::URL_PARAMETERS_STRATEGIES,
        ],
        'queryParameters' => [
            ...Defaults::QUERY_PARAMETERS_STRATEGIES,
        ],
        'bodyParameters' => [
            ...Defaults::BODY_PARAMETERS_STRATEGIES,
        ],
        'responses' => configureStrategy(
            Defaults::RESPONSES_STRATEGIES,
            Strategies\Responses\ResponseCalls::withSettings(
                only: ['GET *'],
                config: ['app.debug' => false],
            )
        ),
        'responseFields' => [
            ...Defaults::RESPONSE_FIELDS_STRATEGIES,
        ],
    ],

    'database_connections_to_transact' => [config('database.default')],

    'fractal' => [
        'serializer' => null,
    ],
];
