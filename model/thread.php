<?php

class Thread
{
    public function __construct() {
	require_once __DIR__ . '/../vendor/autoload.php';
	Twig_Autoloader::register();

	$loader = new Twig_Loader_Filesystem(__DIR__ . '/../templates');
	$twig = new Twig_Environment($loader);
    }

    public function create_thread() {
	$app = \Slim\Slim::getInstance();
	session_start();

	$thread_name = $_POST['thread_name'];
	$user_id = $_SESSION['user_id'];

	$pdo = $this -> access_mysql();
	$query = "INSERT INTO `threads` (thread_name, user_id) VALUES (:thread_name, :user_id)";

	$statement = $pdo -> prepare($query);
	$statement -> bindvalue(':thread_name', $thread_name, pdo::PARAM_STR);
	$statement -> bindvalue(':user_id', $user_id, pdo::PARAM_INT);
	$statement -> execute();

	return $app->redirect('/thread/list');
    }

    public function list_thread() {
	$threads = $this -> list_threads();

	$loader = new Twig_Loader_Filesystem(__DIR__ . '/../templates');
	$twig = new Twig_Environment($loader);
	$template = $twig->loadTemplate('thread/list.twig');

	return $template->render([ 'threads' => $threads ]);
    }

    public function manage_thread() {
	session_start();
	$user_id = $_SESSION['user_id'];
	$add_query = strval(" AND `user_id` = $user_id");
	$threads = $this -> list_threads($add_query);

	$loader = new Twig_Loader_Filesystem(__DIR__ . '/../templates');
	$twig = new Twig_Environment($loader);
	$template = $twig->loadTemplate('thread/manage.twig');

	return $template->render([ 'threads' => $threads ]);
    }

    public function edit_thread() {
	$app = \Slim\Slim::getInstance();

	try {
	    $pdo = $this -> access_mysql();
	    $query = "UPDATE `threads` SET `thread_name` = :thread_name WHERE `thread_id` = :thread_id AND `deleted` IS NOT TRUE";
	    $statement = $pdo -> prepare($query);
	    $statement -> bindvalue(':thread_name', $thread_name, pdo::PARAM_STR);
	    $statement -> bindvalue(':thread_id', $thread_id, pdo::PARAM_INT);
	    $thread_name = $_POST['thread_name'];
	    $thread_id = $_POST['thread_id'];
	    $statement -> execute();
	} catch (PDOException $e) {
	    echo 'データベースerror' . $e->getMessage();
	}

	return $app->redirect('/thread/manage');
    }

    public function delete_thread() {
	$pdo = access_mysql();
	$query = "UPDATE `threads` SET deleted = true WHERE `thread_id` = :thread_id AND `deleted` IS NOT TRUE";

	$statement = $pdo -> prepare($query);
	
	// @TODO thread_idを取得するロジック
	// $thread_id = 
	$statement -> execute();

	$template = $twig->loadTemplate('thread/delete.twig');
    }

    private function access_mysql() {
	try {
	    // @TODO user名、パスワードを暗号化処理
	    $db = 'mysql:dbname=board;host=localhost';
	    $user = 'root';
	    // 本来ならパスは環境変数にぶち込む
	    $password = 12266583;
	    $pdo = new PDO($db, $user, $password);
	    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $e) {
	    echo 'データベース接続error' . $e->getMessage();
	}
	return $pdo;
    }

    private function list_threads($add_query = '') {
	$pdo = $this -> access_mysql();
	$query = "SELECT `thread_name`, `thread_id` FROM `threads` WHERE `deleted` IS NOT TRUE";
	$query .= $add_query;
	$statement = $pdo -> query($query);

	$statement -> fetch(PDO::FETCH_ASSOC);
	$threads = [];
	foreach ($statement as $key => $value) {
	    $threads += [$value['thread_id'] => $value['thread_name']];
	}
	return $threads;
    }
}
