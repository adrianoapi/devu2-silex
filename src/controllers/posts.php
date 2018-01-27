<?php

use Symfony\Component\HttpFoundation\Request;

$post = $app['controllers_factory'];
$post->get('/create', function () use ($app) {
    return $app['view.renderer']->render('posts/create');
});

$post->post('/create', function(Request $request) use($app) {
    /** @var \Doctrine\DBAL\Connection $db */
    $db = $app['db'];
    $data = $request->request->all();
    $db->insert('posts', array(
        'title' => $data['title'],
        'content' => $data['content']
    ));
    return $app->redirect('/admin/posts');
});

$post->get('/', function() use($app) {
    /** @var \Doctrine\DBAL\Connection $db */
    $db = $app['db'];
    $sql = "SELECT * FROM posts;";
    $posts = $db->fetchAll($sql);
    return $app['view.renderer']->render('/posts/list', array("posts" => $posts));
});

$post->get('/edit/{id}', function($id)use($app) {
    /** @var \Doctrine\DBAL\Connection $db */
    $db = $app['db'];
    $sql = "SELECT * FROM posts WHERE id = ?;";
    $post = $db->fetchAssoc($sql, array($id));
    if (!$post) {
        $app->abort(404, "Post não encontrado!");
    }
    return $app['view.renderer']->render('posts/edit', array('post' => $post));
});

$post->post('/edit/{id}', function(Request $request, $id) use($app) {
    /** @var \Doctrine\DBAL\Connection $db */
    $db = $app['db'];
    // Chea existência do post
    $sql = "SELECT * FROM posts WHERE id = ?;";
    $post = $db->fetchAssoc($sql, array($id));
    if (!$post) {
        $app->abort(404, "Post não encontrado!");
    }
    $data = $request->request->all();
    $db->update('posts', array(
        'title' => $data['title'],
        'content' => $data['content']
            ), array('id' => $id));
    return $app->redirect('/admin/posts');
});

$post->get('/delete/{id}', function($id) use($app) {
    /** @var \Doctrine\DBAL\Connection $db */
    $db = $app['db'];
    $sql = "SELECT * FROM posts WHERE id = ?;";
    $post = $db->fetchAssoc($sql, array($id));
    if (!$post) {
        $app->abort(404, "Post não encontrado!");
    }
    $db->delete('posts', array('id' => $id));
    return $app->redirect('/admin/posts');
});


$post->get('/home', function() use($app) {
    dump($app);
    return $app['view.renderer']->render('home');
});

$post->post('/get-name/{parametro1}/{parametro2}', function(Request $request, Silex\Application $app, $parametro2, $parametro1) {
    $name = $request->get('nome');
    return $app['view.renderer']->render('get-name', array(
                'name' => $name,
                'parametro1' => $parametro1,
                'parametro2' => $parametro2
    ));
});

return $post;