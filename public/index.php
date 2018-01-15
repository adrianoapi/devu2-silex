<?php

use Symfony\Component\HttpFoundation\Request;

require __DIR__ . '/../vendor/autoload.php';
$app = new Silex\Application();


/*
 * Rotas
 */

$app->get('/hello-world', function() {
    return 'Hello world';
});

$app->get('/home', function() {
    return NULL .
            '<form method="get" action="/get-name">' .
            '   <input type="text" name="nome" value="">' .
            '   <button type="submit">Enviar</button>' .
            '</form>';
});

// Request com GET
$app->get('/get-name', function(Request $request) {
    $name = $request->get('nome','sem nome');
    return "Post enviado com sucesso! <br> $name";
});

// Request com POST
$app->post('/home', function(Request $request) {
    $name = $request->get('nome','sem nome');
    return "Post enviado com sucesso! <br> $name";
});


$app->run();
