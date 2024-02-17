<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Packages
    |--------------------------------------------------------------------------
    |
    | PLAYGROUND_AUTH_PACKAGES may be used to load abilities from other packages.
    |
    | PLAYGROUND_AUTH_REQUIRE_PACKAGE_ABILITIES is enabled by default. When
    |   using token abilities, this value ensures that `playground-auth` is
    |   included in `PLAYGROUND_AUTH_PACKAGES` if omitted in the .env.
    |
    | PLAYGROUND_AUTH_DEBUG Requires config(app.debug) to be true to display logs.
    */

    'packages' => is_string(env('PLAYGROUND_AUTH_PACKAGES', 'playground-auth')) ? array_map(
        'trim',
        explode(',', env('PLAYGROUND_AUTH_PACKAGES', 'playground-auth'))
    ) : [],

    'require' => [
        /**
         * @var bool package_abilities   By default, require
         */
        'package_abilities' => (bool) env('PLAYGROUND_AUTH_REQUIRE_PACKAGE_ABILITIES', true),
    ],

    /**
     * @var bool debug   Enable authentication debugging messages.
     */
    'debug' => (bool) env('PLAYGROUND_AUTH_DEBUG', false),
    // 'debug' => (bool) env('PLAYGROUND_AUTH_DEBUG', true),

    /*
    |--------------------------------------------------------------------------
    | Loading
    |--------------------------------------------------------------------------
    |
    | PLAYGROUND_AUTH_LOAD_COMMANDS enables Console\Commands\HashPassword
    |
    | PLAYGROUND_AUTH_LOAD_TRANSLATIONS loads translations in /lang
    |
    */

    'load' => [
        'commands' => (bool) env('PLAYGROUND_AUTH_LOAD_COMMANDS', true),
        'translations' => (bool) env('PLAYGROUND_AUTH_LOAD_TRANSLATIONS', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Redirects
    |--------------------------------------------------------------------------
    |
    | PLAYGROUND_AUTH_REDIRECT may be disabled to show an error page instead.
    */

    /**
     * @var ?string redirect   Specify for redirect()->guest($redirect)
     */
    'redirect' => env('PLAYGROUND_AUTH_REDIRECT', 'login'),
    // 'redirect' => null,

    /*
    |--------------------------------------------------------------------------
    | Policies
    |--------------------------------------------------------------------------
    |
    | ModelPolicy supports multiple security implementations.
    |
    | Options for Sanctum:
    | - PLAYGROUND_AUTH_USER_PRIVILEGES - allow saving privileges in the user model.
    | - PLAYGROUND_AUTH_VERIFY === privileges
    |
    */

    /**
     * @var string verify   user|privileges|roles
     */
    'verify' => env('PLAYGROUND_AUTH_VERIFY', 'privileges'),

    /**
     * @var bool sanctum   Enable Sanctum
     */
    'sanctum' => (bool) env('PLAYGROUND_AUTH_SANCTUM', true),

    /**
     * @var bool hasPrivilege   Enable if the user model has $user->hasPrivilege($privilege)
     */
    'hasPrivilege' => (bool) env('PLAYGROUND_AUTH_HAS_PRIVILEGE', false),

    /**
     * @var bool hasPrivilege   Enable if the user model has the attribute User::$privileges
     */
    'userPrivileges' => (bool) env('PLAYGROUND_AUTH_USER_PRIVILEGES', false),

    /**
     * @var bool hasRole   Enable if the user model has $user->hasRole($role)
     */
    'hasRole' => (bool) env('PLAYGROUND_AUTH_HAS_ROLE', false),
    // 'hasRole' => (bool) env('PLAYGROUND_AUTH_HAS_ROLE', true),

    /**
     * @var bool userRole   Enable if the user model has the attribute User::$role
     */
    'userRole' => (bool) env('PLAYGROUND_AUTH_USER_ROLE', false),
    // 'userRole' => (bool) env('PLAYGROUND_AUTH_USER_ROLE', true),

    /**
     * @var bool userRoles   Enable if the user model has the attribute User::$roles
     */
    'userRoles' => (bool) env('PLAYGROUND_AUTH_USER_ROLES', false),
    // 'userRoles' => (bool) env('PLAYGROUND_AUTH_USER_ROLES', true),

    /*
    |--------------------------------------------------------------------------
    | Token configuration
    |--------------------------------------------------------------------------
    |
    | Enabling Sanctum provides token and API key support.
    |
    */

    'token' => [

        /**
         * @var string abilities   merge|user
         */
        'abilities' => env('PLAYGROUND_AUTH_TOKEN_ABILITIES', 'merge'),

        /**
         * @var ?string expires   Set expires to null to allow tokens to live forever.
         */
        'expires' => env('PLAYGROUND_AUTH_TOKEN_EXPIRES', 'tomorrow midnight'),

        /**
         * @var string name   The token name.
         */
        'name' => env('PLAYGROUND_AUTH_TOKEN_NAME', 'app'),

        /**
         * @var bool listed   Use the listed admins and managers in this configuration.
         */
        'listed' => (bool) env('PLAYGROUND_AUTH_TOKEN_LISTED', false),

        /**
         * @var bool roles   Check the user role(s) for applying abilities.
         */
        'roles' => (bool) env('PLAYGROUND_AUTH_TOKEN_ROLES', false),
        // 'roles' => (bool) env('PLAYGROUND_AUTH_TOKEN_ROLES', true),

        /**
         * @var bool privileges  Allow the attribute User::$privileges to be used for authorization.
         */
        'privileges' => (bool) env('PLAYGROUND_AUTH_TOKEN_PRIVILEGES', false),
        // 'privileges' => (bool) env('PLAYGROUND_AUTH_TOKEN_PRIVILEGES', true),

        /**
         * @var bool sanctum   The token will use Sanctum.
         */
        'sanctum' => (bool) env('PLAYGROUND_AUTH_TOKEN_SANCTUM', true),
        // 'sanctum' => (bool) env('PLAYGROUND_AUTH_TOKEN_SANCTUM', false),

    ],

    /*
    |--------------------------------------------------------------------------
    | Listed admins and managers
    |--------------------------------------------------------------------------
    |
    | Allow specifying a set of admins and/or managers.
    |
    */

    /**
     * @var array<int, string> admins Provide an array of email addresses for admin privileges.
     */
    'admins' => [
        // 'cindy@example.com',
        // 'joe@example.com',
        // 'tim@example.com',
    ],

    /**
     * @var array<int, string> managers Provide an array of email addresses for manager privileges.
     */
    'managers' => [
        // 'sally@example.com',
    ],

    /*
    |--------------------------------------------------------------------------
    | Abilities
    |--------------------------------------------------------------------------
    |
    | Root: has all privileges, where applicable.
    |
    | Admins: have wildcard access, at top level of resources.
    |
    | Manager: Has wildcard access at the model level.
    |
    | User: Has specific privileges and no wildcards.
    |
    | Guest: Specify `deny` for no privileges.
    |
    */

    'abilities' => [
        'root' => [
            '*',
        ],
        'admin' => [
            'app:*',
            'playground:*',
            'playground-auth:*',
        ],
        'manager' => [
            'app:view',

            'playground:view',

            'playground-auth:logout',
            'playground-auth:reset-password',
        ],
        'user' => [
            'app:view',

            'playground:view',

            'playground-auth:logout',
            'playground-auth:reset-password',
        ],
        'guest' => [
            'deny',
        ],
        // 'guest' => [
        //     'app:view',

        //     'playground:view',

        //     'playground-auth:logout',
        //     'playground-auth:reset-password',
        // ],
    ],
];
