<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require __DIR__ . '/../vendor/autoload.php';
$app = new Silex\Application();

$app->get('/hello-world', function() {
    return 'Hello world';
});

$app->get('/home', function() {
    ob_start();
    include __DIR__ . '/../templates/home.phtml';
    $saida = ob_get_clean();
    return $saida;
});

$app->post('/get-name/{parametro1}/{parametro2}', function(Request $request, $parametro2, $parametro1) {
    $name = $request->get('nome');
    ob_start();
    include __DIR__ . '/../templates/get-name.phtml';
    $saida = ob_get_clean();
    return $saida;
});

$app->run();
