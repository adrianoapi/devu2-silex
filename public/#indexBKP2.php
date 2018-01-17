<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use SON\View\ViewRenderer;

require __DIR__ . '/../vendor/autoload.php';
$app = new Silex\Application();
$app['view.config'] = array(
    'path_templates' => __DIR__ . '/../templates'
);
$app['view.render'] = function() use($app) {
    $pathTemplates = $app['view.config']['path_templates'];
    return new ViewRenderer($pathTemplates);
};
$app['valor'] = "Teste";
$app['date_time'] = function() {
    return new \DateTime();
};
$app->get('/hello-world', function(Silex\Application $app) {
    echo $app['date_time']->format(\DateTime::W3C);
    echo "<br/>";
    sleep(10);
    echo $app['date_time']->format(\DateTime::W3C);
    return "Hello world " . $app['valor'];
});

$app->get('/home', function() {
    return $app['view.render']->render('home', array());
});

$app->post('/get-name/{parametro1}/{parametro2}', function(Request $request, Silex\Application $app, $parametro2, $parametro1) {
    echo $app['valor'] . "<br>";
    $name = $request->get('nome');
    ob_start();
    include __DIR__ . '/../templates/get-name.phtml';
    $saida = ob_get_clean();
    return $saida;
});

$app->run();
