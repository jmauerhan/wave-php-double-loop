<?php declare(strict_types=1);

require_once './vendor/autoload.php';

$faker = Faker\Factory::create();
$obj   = (object)[
    'data' => (object)[
        'type'       => 'chirp',
        'id'         => $faker->uuid,
        'attributes' => (object)[
            'text' => $faker->realText(100)
        ]
    ]
];

$id   = $obj->data->id;
$text = $obj->data->attributes->text;

//Save it
$dsn    = 'pgsql:dbname=chirper;host=db';
$dbUser = 'postgres';
$dbPass = 'postgres';
$pdo    = new PDO($dsn, $dbUser, $dbPass);

$sql          = "INSERT INTO chirp(id, chirp_text) VALUES(:id, :chirp_text)";
$preparedStmt = $pdo->prepare($sql);
$preparedStmt->execute(['id' => $id, 'chirp_text' => $text]);

//Get the chirps out to see
$sql    = "SELECT * FROM chirp";
$result = $pdo->query($sql);
var_dump($result->fetchAll());