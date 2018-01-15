<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
            '<form method="POST" action="/home-response">' .
            '   <input type="text" name="nome" value="">' .
            '   <button type="submit">Enviar</button>' .
            '</form>';
});

// Request com GET
$app->get('/get-name', function(Request $request) {
//    $name = $request->get('nome','sem nome');
    $data = $request->query->all();
    print_r($data);
    die();
});

// Request com POST
$app->post('/home', function(Request $request) {
//    $name = $request->get('nome','sem nome');
    $data = $request->request->all();
//    print_r($data);
//    die();
    return "Post enviado com sucesso! <br> $name";
});

// Request com Response
$app->post('/home-response', function(Request $request) {
    $name = $request->get('nome', 'sem nome');
//    return "Post => Name: $name";
    return new Response("Post => Name: $name", 404);
});

// Parametros de rota
$app->get('/inicial', function() {
    return NULL .
            '<form method="POST" action="/inicial/parametro">' .
            '   <input type="text" name="nome" value="">' .
            '   <button type="submit">Enviar</button>' .
            '</form>';
});

$app->post('/inicial/{parametro1}', function(Request $request, $parametro1) {
    $name = $request->get('nome', 'sem nome');
    return new Response("Post <br/>name: $name<br/>Parametro1: $parametro1", 202);
});

$app->run();
