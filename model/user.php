<?php

class User
{
    public function __construct() {
	require_once __DIR__ . '/../vendor/autoload.php';
	Twig_Autoloader::register();

	$loader = new Twig_Loader_Filesystem(__DIR__ . '/../templates');
	$twig = new Twig_Environment($loader);

    }

    public function create() {
	$key = $_POST['key'];

	// @TODO 'unko'を暗号化処理
	if ($key !== 'unko') {
	    // throw new Exception('KEYの不一致');
	}

	try {

	    $pdo = $this -> access_mysql();
	    $query = "INSERT INTO user (user_name, password) VALUES (:user_name, :password) WHERE `deleted` = false";

	    // @TODO nameの重複チェック
	    $statement = $pdo -> prepare($query);
	    $statement -> bindValue(':user_name', $user_name, PDO::PARAM_STR);
	    $statement -> bindValue(':password', $password, PDO::PARAM_STR);

	    $user_name = $_POST['user_name'];
	    $password = $_POST['password'];
	    $statement -> execute();

	} catch(Exception $e) {
	    echo '不正なアクセス';
	}

	$loader = new Twig_Loader_Filesystem(__DIR__ . '/../templates');
	$twig = new Twig_Environment($loader);

	$template = $twig->loadTemplate('user/create.twig');
	return $template->render([ 'user_name' => $user_name, 'password' => $password ]);
    }

    public function login() {

	session_start();
	$pdo = $this -> access_mysql();
	$status = 'none';

	if( isset($_SESSION['user_name']) ) {
	    $status = 'logged_in';
	} else if( isset($_POST['user_name']) && isset($_POST['password']) ) {
	    $query = "SELECT * FROM `users` WHERE `user_name` = :user_name AND `password` = :password WHERE `deleted` = false";
	    $statement = $pdo -> prepare($query);
	    $statement -> bindValue(':user_name', $user_name, PDO::PARAM_STR);
	    $statement -> bindValue(':password', $password, PDO::PARAM_STR);

	    // @TODO パスワード暗号化
	    $password = $_POST["password"];
	    $user_name = $_POST['user_name'];
	    $statement -> execute();

	    if( $statement -> fetchColumn() === 1 ) {
		$_SESSION["user_name"] = $_POST["user_name"];
		$status = 'login';
	    } else {
		$status = "failed";
	    }
	}

	$loader = new Twig_Loader_Filesystem(__DIR__ . '/../templates');
	$twig = new Twig_Environment($loader);

	$template = $twig->loadTemplate('user/login.twig');
	return $template->render([ 'message' => $message ]);
    }

    public function logout() {

	if ( isset($_SESSION['USERID']) ) {
	    $message = "ログアウトしました。";
	} else {
	    $message = "セッションがタイムアウトしました。";
	}

	session_start();
	$_SESSION = array();
	session_destroy();

	HTTP::redirect('/');
	return $message;
    }

    private function access_mysql() {
	try {
	    // @TODO user名、パスワードを暗号化処理
	    $db = 'mysql:dbname=board;host=localhost';
	    $user = 'root';
	    // 本来ならパスは環境変数にぶち込む
	    $password = 12266583;
	    $pdo = new PDO($db, $user, $password);
	} catch (PDOException $e) {
	    echo 'データベース接続error' . $e->getMessage();
	}
	return $pdo;
    }

}
