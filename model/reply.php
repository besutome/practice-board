<?php

class Reply 
{
    public function __construct() {
	require_once __DIR__ . '/../vendor/autoload.php';
	Twig_Autoloader::register();

	$loader = new Twig_Loader_Filesystem(__DIR__ . '/../templates');
	$twig = new Twig_Environment($loader);
    }

    public function create_reply() {
	$app = \Slim\Slim::getInstance();
	session_start();
	$user_id = $_SESSION['user_id'];
	$board_id = $_POST['board_id'];
	$message = $_POST['message'];
// var_dump($_SESSION);exit;
	$pdo = $this -> access_mysql();
	$query = "INSERT INTO `replies` (board_id, user_id, message) VALUES (:board_id, :user_id, :message)";

	$statement = $pdo -> prepare($query);
	$statement -> bindvalue(':board_id', $board_id, pdo::PARAM_INT);
	$statement -> bindvalue(':user_id', $user_id, pdo::PARAM_INT);
	$statement -> bindvalue(':message', $message, pdo::PARAM_STR);
	$statement -> execute();

	return $app->redirect("/thread/show/$board_id");
    }

    public function list_reply() {
	session_start();
	$user_id = $_SESSION['user_id'];

	$pdo = $this -> access_mysql();
	$statement = $pdo -> prepare( "SELECT `reply_id`, `message`, `board_id` FROM `replies` WHERE `user_id` = :user_id AND `deleted` IS NOT TRUE" );
	$statement -> bindvalue(':user_id', $user_id, pdo::PARAM_STR);
	$statement -> execute();
	$messages = $statement -> fetchAll(PDO::FETCH_ASSOC);
	foreach( $messages as $key => $value ){
	    $board_id[] = $value['board_id'];
	}
	$board_id = array_unique($board_id);
	foreach( $board_id as $key => $value ){
	    $params[] = ":board_id{$key}";
	}
	$param = implode(", ", $params);

	$pdo = $this -> access_mysql();
	$statement = $pdo -> prepare( "SELECT `thread_id`, `thread_name` FROM `threads` WHERE `thread_id` IN ($param) AND `deleted` IS NOT TRUE" );
	foreach ( $board_id as $key => $value ) {
	    $statement->bindValue(":board_id{$key}", intval($value), PDO::PARAM_INT);
	}
	$statement -> execute();
	$threads = $statement -> fetchAll(PDO::FETCH_ASSOC);

	$loader = new Twig_Loader_Filesystem(__DIR__ . '/../templates');
	$twig = new Twig_Environment($loader);
	$template = $twig->loadTemplate('reply/list.twig');

	return $template->render([ 'messages' => $messages, 'threads' => $threads ]);
    }

    public function manage_reply() {
	$app = \Slim\Slim::getInstance();
	$reply_id = $_POST['reply_id'];
	$message = $_POST['message'];
	$pdo = $this -> access_mysql();

	if (isset($_POST['delete'])) {
	    $query = "UPDATE `replies` SET `deleted` = true WHERE `reply_id` = :reply_id AND `deleted` IS NOT TRUE";
	    $statement = $pdo -> prepare($query);
	} else {
	    $query = "UPDATE `replies` SET message = :message WHERE `reply_id` = :reply_id AND `deleted` IS NOT TRUE";
	    $statement = $pdo -> prepare($query);
	    $statement -> bindValue(':message', $message, PDO::PARAM_STR);
	}

	$statement -> bindValue(':reply_id', $reply_id, PDO::PARAM_INT);
	$statement -> execute();

	return $app->redirect("/reply/list");

    }

    public function delete_reply() {
	$pdo = access_mysql();
	$query = "UPDATE `replies` SET deleted = true WHERE `reply_id` = :reply_id AND `deleted` = false";

	$statement = $pdo -> prepare($query);
	$statement -> bindValue(':reply_id', $reply_id, PDO::PARAM_INT);
	
	// @TODO reply_idを取得するロジック
	// $reply_id = ;
	$statement -> execute();

	$template = $twig->loadTemplate('reply/delete.twig');

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
