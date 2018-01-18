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

$app->get('/', function()use($app) {
    return $app->redirect('/home');
});

$app->get('/posts/create', function () use ($app) {
    return $app['view.renderer']->render('posts/create');
});

$app->post('/posts/create', function(Request $request) use($app) {
    /** @var \Doctrine\DBAL\Connection $db */
    $db = $app['db'];
    $data = $request->request->all();
    $db->insert('posts', array(
        'title' => $data['title'],
        'content' => $data['content']
    ));
    return $app->redirect('/posts/create');
});

$app->get('/posts', function() use($app) {
    /** @var \Doctrine\DBAL\Connection $db */
    $db = $app['db'];
    $sql = "SELECT * FROM posts;";
    $posts = $db->fetchAll($sql);
    return $app['view.renderer']->render('/posts/list', array("posts" => $posts));
});

$app->get('/posts/edit/{id}', function($id)use($app) {
    /** @var \Doctrine\DBAL\Connection $db */
    $db = $app['db'];
    $sql = "SELECT * FROM posts WHERE id = ?;";
    $post = $db->fetchAssoc($sql, array($id));
    return $app['view.renderer']->render('posts/edit', array('post' => $post));
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
