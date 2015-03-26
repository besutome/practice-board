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
	session_start();
	$thread_name = $_POST['thread_name'];
	$user_id = $_SESSION['user_id'];

	$pdo = $this -> access_mysql();
	$query = "INSERT INTO `threads` (thread_name, user_id) VALUES (:thread_name, :user_id)";

	$statement = $pdo -> prepare($query);
	$statement -> bindvalue(':thread_name', $thread_name, pdo::PARAM_STR);
	$statement -> bindvalue(':user_id', $user_id, pdo::PARAM_INT);
	$statement -> execute();

	$loader = new Twig_Loader_Filesystem(__DIR__ . '/../templates');
	$twig = new Twig_Environment($loader);
	$template = $twig->loadTemplate('thread/create.twig');

	return $template->render([ 'thread_name' => $thread_name ]);
    }

    public function list_thread() {
	$pdo = $this -> access_mysql();
	$statement = $pdo -> query( "SELECT `thread_name` FROM `threads` WHERE `deleted` IS NOT TRUE" );

	$result = $statement -> fetch(PDO::FETCH_ASSOC);
	foreach ($statement as $key => $value) {
	    $thread_name[$key] =  $value['thread_name'];
	}

	$loader = new Twig_Loader_Filesystem(__DIR__ . '/../templates');
	$twig = new Twig_Environment($loader);
	$template = $twig->loadTemplate('thread/list.twig');
    var_dump($thread_name);

	return $template->render($thread_name);
    }

    public function manage_thread() {
	$pdo = access_mysql();
	$query = "UPDATE `threads` SET thread_name = :thread_name WHERE `thread_id` = :thread_id AND `deleted` IS NOT TRUE";

	$statement = $pdo -> prepare($query);
	$statement -> bindValue(':thread_id', $thread_id, PDO::PARAM_INT);

	// @TODO thread_idを取得するロジック
	// $thread_id = 
	$statement -> execute();

	$template = $twig->loadTemplate('thread/manage.twig');
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
}
