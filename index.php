<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/model/user.php';
require_once __DIR__ . '/model/reply.php';
require_once __DIR__ . '/model/thread.php';

Twig_Autoloader::register();
$app = new \Slim\Slim();

// Slimの不具合で動かないぽい（/vendor/slim/slim/Slim/Helper/Set.php）
// use Slim\Slim;
// use Slim\Extras\Views\Twig as Twig;
// $app = new Slim( ['view' => new Twig, 'templates.path' => __DIR__ . '/templates'] );


$app->get('/', function() use ($app) {
    $loader = new Twig_Loader_Filesystem(__DIR__ . '/templates');
    $twig = new Twig_Environment($loader);
    echo $twig->render('index.twig');
});


$app->group('/user', function () use ($app) {

    $app->post('/create', function() use ($app) {
	$user = new User();
	echo $user -> create();
    });

    $app->post('/login', function() use ($app) {
	$user = new User();
	echo $user -> login();
    });

    $app->get('/logout', function() use ($app) {
	$user = new User();
	echo $user -> logout();
    });
});

$app->group('/reply', function () use ($app) {

    $app->post('/create', function() use ($app) {
	$reply = new Reply();
	echo $reply -> create_reply();
    });

    $app->get('/list', function() use ($app) {
	$reply = new Reply();
	echo $reply -> list_reply();
    });

    $app->post('/manage', function() use ($app) {
	$reply = new Reply();
	echo $reply -> manage_reply();
    });

    $app->get('/delete', function() use ($app) {
	$reply = new Reply();
	echo $reply -> delete_reply();
    });
});

$app->group('/thread', function () use ($app) {

    $app->post('/create', function() use ($app) {
	$thread = new Thread();
	echo $thread -> create_thread();
    });

    $app->get('/list', function() use ($app) {
	$thread = new Thread();
	echo $thread -> list_thread();
    });

    $app->get('/manage', function() use ($app) {
	$thread = new Thread();
	echo $thread -> manage_thread();
    });

    $app->get('/delete', function() use ($app) {
	$thread = new Thread();
	echo $thread -> delete_thread();
    });
});

$app->run();
