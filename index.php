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
	// $app -> render('create_user.twig', ['user_name' => $user_name, 'password' => $password]);
	$loader = new Twig_Loader_Filesystem(__DIR__ . '/templates');
	$twig = new Twig_Environment($loader);
	echo $twig -> render('create_user.twig');
    });

    $app->get('/login', function() use ($app) {
	$user = new User();
    });

    $app->get('/logout', function() use ($app) {
	$user = new User();
    });
}

$app->group('/reply', function () use ($app) {

    $app->get('/create', function() use ($app) {
	$reply = new Reply();
    });

    $app->get('/list', function() use ($app) {
	$reply = new Reply();
    });

    $app->get('/manage', function() use ($app) {
	$reply = new Reply();
    });

    $app->get('/delete', function() use ($app) {
	$reply = new Reply();
    });
}

$app->group('/thread', function () use ($app) {

    $app->get('/create', function() use ($app) {
	$thread = new Thread();
    });

    $app->get('/list', function() use ($app) {
	$thread = new Thread();
    });

    $app->get('/manage', function() use ($app) {
	$thread = new Thread();
    });

    $app->get('/delete', function() use ($app) {
	$thread = new Thread();
    });
}





$app->run();
