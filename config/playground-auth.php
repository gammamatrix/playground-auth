<?php

return [
    'layout' => (string) env('PLAYGROUND_AUTH_LAYOUT', 'playground::layouts.site'),
    'view' => (string) env('PLAYGROUND_AUTH_VIEW', 'playground-auth::'),
    'redirect' => env('PLAYGROUND_AUTH_REDIRECT', null),
    'load' => [
        'commands' => (bool) env('PLAYGROUND_AUTH_LOAD_COMMANDS', true),
        'routes' => (bool) env('PLAYGROUND_AUTH_LOAD_ROUTES', true),
        'views' => (bool) env('PLAYGROUND_AUTH_LOAD_VIEWS', true),
    ],
    'sitemap' => [
        'enable' => (bool) env('PLAYGROUND_AUTH_SITEMAP_ENABLE', true),
        'guest' => (bool) env('PLAYGROUND_AUTH_SITEMAP_GUEST', true),
        'user' => (bool) env('PLAYGROUND_AUTH_SITEMAP_USER', true),
        'view' => (string) env('PLAYGROUND_AUTH_SITEMAP_VIEW', 'playground-auth::sitemap'),
    ],
];
