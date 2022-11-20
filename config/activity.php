<?php

return [
    'activated'        => true, // active/inactive all logging
    'delete_limit'     => 7, // default 7 days

    'model' => [
        'user' => "App\User"
    ],

    'table' => 'logs',

    'log_events' => [
        'on_create'     => true,
        'on_edit'       => true,
        'on_delete'     => true,
    ]
];
