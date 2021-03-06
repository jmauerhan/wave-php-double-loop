<?php declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request AS SilexRequest;
use Symfony\Component\HttpFoundation\Response AS SilexResponse;

$app               = new Silex\Application();
$app['debug']      = true;
$app['debug.mode'] = 'dev';

$dbEngine = env('DB_ENGINE');
$dbHost   = env('DB_HOST');
$dbName   = env('DB_NAME');
$dbPort   = env('DB_PORT');
$dbUser   = env('DB_USER');
$dbPass   = env('DB_PASSWORD');

$dsn = "{$dbEngine}:dbname={$dbName};host={$dbHost};port={$dbPort}";
$pdo = new PDO($dsn, $dbUser, $dbPass);

$valitron  = new \Valitron\Validator();
$validator = new \Chirper\Http\Validation\ValitronValidator($valitron);

$transformer = new \Chirper\Chirp\JsonApiChirpTransformer($validator);

$app->post('chirp',
    function (SilexRequest $silexRequest) use ($app, $pdo, $transformer) {
        $driver = new \Chirper\Chirp\PdoPersistenceDriver($pdo);
        $action = new \Chirper\Chirp\CreateAction($transformer, $driver);

        $request = new \Chirper\Http\Request($silexRequest->getMethod(),
                                             $silexRequest->getUri(),
                                             $silexRequest->headers->all(),
                                             $silexRequest->getContent(),
                                             $silexRequest->getProtocolVersion());

        $response = $action->create($request);

        return new SilexResponse($response->getBody()->getContents(),
                                 $response->getStatusCode(),
                                 $response->getHeaders());
    });

$app->get('',
    function (SilexRequest $silexRequest) use ($app, $pdo, $transformer) {
        $driver   = new \Chirper\Chirp\PdoPersistenceDriver($pdo);
        $action   = new \Chirper\Chirp\GetTimelineAction($transformer, $driver);
        $response = $action->getAll();

        return new SilexResponse($response->getBody()->getContents(),
                                 $response->getStatusCode(),
                                 $response->getHeaders());
    }
);

//Fixes CORS / Preflight stuff
$app->options("{anything}",
    function () {
        return new \Symfony\Component\HttpFoundation\JsonResponse(null, 200);
    })->assert("anything", ".*");

$app->after(function (SilexRequest $request, SilexResponse $response) {
    $response->headers->set('Access-Control-Allow-Origin', '*');
    $response->headers->set('Access-Control-Allow-Methods', 'GET, POST');
    $response->headers->set('Access-Control-Allow-Headers', 'content-type');
});

$app->run();