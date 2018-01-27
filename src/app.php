<?php

use SON\View\ViewRenderer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application();
$app['debug'] = false;
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

$app->get('/', function()use($app) {
    return $app->redirect('/home');
});

$site = include __DIR__ . '/controllers/site.php';
$app->mount('/', $site);
$app->mount('/admin', function($admin) use($app) {
    $post = include __DIR__ . '/controllers/posts.php';
    $admin->mount('/posts', $post);
});

$app->error(function(\Exception $e, Request $request, $code) use($app) {
    switch ($code) {
        case 404:
            return $app['view.renderer']->render('errors/404', array(
                        'message' => $e->getMessage()
            ));
    }
});

$app->run();
