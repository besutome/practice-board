<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/model/user.php';
require_once __DIR__ . '/model/reply.php';
require_once __DIR__ . '/model/thread.php';

Twig_Autoloader::register();
$app = new \Slim\Slim();

$app->get('/', function() use ($app) {
    $loader = new Twig_Loader_Filesystem(__DIR__ . '/templates');
    $twig = new Twig_Environment($loader);
    echo $twig->render('index.twig');
});


$app->get('/create-user', function() use ($app) {
    echo 111;
});

$app->get('/login', function() use ($app) {
});

$app->get('/logout', function() use ($app) {
});


$app->get('/create-reply', function() use ($app) {
    echo 111;
});

$app->get('/list-reply', function() use ($app) {
    echo 111;
});

$app->get('/manage-reply', function() use ($app) {
    echo 111;
});

$app->get('/delete-reply', function() use ($app) {
    echo 111;
});


$app->get('/create-thread', function() use ($app) {
    echo 111;
});

$app->get('/list-thread', function() use ($app) {
    echo 111;
});

$app->get('/manage-thread', function() use ($app) {
    echo 111;
});

$app->get('/delete-thread', function() use ($app) {
    echo 111;
});

$app->run();
