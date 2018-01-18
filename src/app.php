<?php

use SON\View\ViewRenderer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application();
$app['debug'] = true;
$app['view.config'] = [
    'path_templates' => __DIR__ . '/../templates'
];

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'pdo_mysql',
        'host' => 'localhost',
        'dbname' => 'db_silex',
        'user' => 'root',
        'pass' => '',
    ),
));

$app['view.renderer'] = function() use($app) {
    $pathTemplates = $app['view.config']['path_templates'];
    return new ViewRenderer($pathTemplates);
};

$app['date_time'] = function() {
    return new \DateTime();
};

$app->get('/create-table', function (Silex\Application $app) {
    $file = fopen(__DIR__ . '/../data/schema.sql', 'r');
    while ($line = fread($file, 4096)) {
        $app['db']->executeQuery($line);
    }
    fclose($file);
    return "Tabelas criadas";
});

$app->get('/home', function() use($app) {
    dump($app);
    return $app['view.renderer']->render('home');
});

$app->post('/get-name/{parametro1}/{parametro2}', function(Request $request, Silex\Application $app, $parametro2, $parametro1) {
    $name = $request->get('nome');
    return $app['view.renderer']->render('get-name', array(
                'name' => $name,
                'parametro1' => $parametro1,
                'parametro2' => $parametro2
    ));
});

$app->run();
