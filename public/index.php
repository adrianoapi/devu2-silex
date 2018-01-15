<?php

require '../vendor/autoload.php';
$app = new Silex\Application();
$app->get('/', function() {
    return 'home';
});
$app->get('/hello-world', function() {
    return 'Hello world';
});
$app->run();
