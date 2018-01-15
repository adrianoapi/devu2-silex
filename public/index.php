<?php

require '../vendor/autoload.php';
$app = new Silex\Application();

/*
 * Rotas
 */
$app->get('/', function() {
    return '<form method="POST" action="/home">' .
            '   <input name="nome">' .
            '   <button type="submit">Enviar</button>' .
            '</form>';
});

$app->post('/home', function() {
    return 'Post enviado com sucesso!';
});

$app->get('/hello-world', function() {
    return 'Hello world';
});

$app->run();
