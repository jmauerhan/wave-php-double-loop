<?php declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request AS SilexRequest;
use Symfony\Component\HttpFoundation\Response AS SilexResponse;

$app               = new Silex\Application();
$app['debug']      = true;
$app['debug.mode'] = 'dev';

$app->post('chirp',
    function (SilexRequest $silexRequest) use ($app) {
        return new SilexResponse("", SilexResponse::HTTP_CREATED);
    });

$app->run();