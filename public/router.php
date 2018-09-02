<?php declare(strict_types=1);

require_once './vendor/autoload.php';

//The data that will be submitted to us
$uuid = \Ramsey\Uuid\Uuid::uuid4();
$obj  = (object)[
    'data' => (object)[
        'type'       => 'chirp',
        'id'         => $uuid,
        'attributes' => (object)[
            'text' => 'Hi, this is a chirp'
        ]
    ]
];

//Convert it into an object