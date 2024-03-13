<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    // 401 - Unauthorized
    'unauthorized' => 'Unauthorized',
    // 403 - Forbidden
    'forbidden' => 'Forbidden',
    // 406 - Not Acceptable
    'unacceptable' => 'Not Acceptable',

    'model.locked' => 'The :model record is locked. You must unlock it to make changes.',

    'failed' => 'These credentials do not match our records.',
    'password' => 'The provided password is incorrect.',
    'permission' => 'You do not have permission.',
    'required' => 'Authentication is required. Please log in.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',

    'sanctum.disabled' => 'Sorry, Sanctum authenticaction is currently disabled.',

];
