<?php

return [
    'app_url' => getenv('APP_URL') ?: 'https://rental.micool.id',
    'allowed_hosts' => ['localhost', 'rental.micool.id'],
    'host_paths' => [
        'localhost' => '/iventlo-new',
        'rental.micool.id' => ''
    ]
];
