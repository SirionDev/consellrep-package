<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Configuració | Consell per la República
    |--------------------------------------------------------------------------
    |
    | This file is for storing configuration values.
    |
    */

    'validation' => [
        'url' => env('CXR_VALIDATION_URL', 'https://apis.consellrepublica.cat/idserv/validate'),
        'param' => env('CXR_VALIDATION_PARAM', 'idCiutada'),
    ],

];
