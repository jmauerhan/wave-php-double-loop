<?php

require_once '../vendor/autoload.php';

$faker = Faker\Factory::create();
$obj   = (object)[
    'data' => (object)[
        'type'       => 'chirp',
        'id'         => $faker->uuid,
        'attributes' => (object)[
            'text'   => $faker->text(100),
            'author' => $faker->userName
        ]
    ]
];

$id        = $obj->data->id;
$text      = $obj->data->attributes->text;
$author    = $obj->data->attributes->author;
$createdAt = (new DateTime())->format('Y-m-d H:i:s+e');

//Save it
$dsn    = 'pgsql:dbname=chirper;host=db';
$dbUser = 'postgres';
$dbPass = 'postgres';
$pdo    = new PDO($dsn, $dbUser, $dbPass);

//$sql          = "INSERT INTO chirp(id, chirp_text, author, created_at)
//VALUES(:id, :chirp_text, :author, :created_at)";
//$values       = ['id' => $id, 'chirp_text' => $text, 'author' => $author, 'created_at' => $createdAt];
//$preparedStmt = $pdo->prepare($sql);
//$result       = $preparedStmt->execute($values);

//Get the chirps out to see
$sql    = "SELECT * FROM chirp ORDER BY created_at DESC";
$result = $pdo->query($sql);
var_dump($result->fetchAll(PDO::FETCH_ASSOC));