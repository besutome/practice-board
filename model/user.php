<?php

class User
{
    public function __construct() {
	require_once __DIR__ . '/../vendor/autoload.php';
	Twig_Autoloader::register();

	// $loader = new Twig_Loader_Filesystem(__DIR__ . '/../templates');
	// $twig = new Twig_Environment($loader);

    }

    public function create() {
	$key = $_POST['key'];
	$user_name = $_POST['user_name'];
	$password = $_POST['password'];

	// @TODO 'unko'を暗号化処理
	if ($key !== 'unko') {
	    // throw new Exception('KEYの不一致');
	}

	try {

	    $pdo = $this -> access_mysql();
	    $query = "INSERT IGNORE INTO users(user_name, password) VALUES(:user_name, :password)";

	    // @TODO nameの重複チェック
	    $statement = $pdo -> prepare($query);
	    $statement -> bindValue(':user_name', $user_name, PDO::PARAM_STR);
	    $statement -> bindValue(':password', $password, PDO::PARAM_STR);

	    $statement -> execute();

	} catch(Exception $e) {
	    echo '不正なアクセス' . $e->getMessage();
	}

	$loader = new Twig_Loader_Filesystem(__DIR__ . '/../templates');
	$twig = new Twig_Environment($loader);
	$template = $twig->loadTemplate('user/create.twig');
	return $template->render([ 'user_name' => $user_name, 'password' => $password ]);
    }

    public function login() {

	$app = \Slim\Slim::getInstance();

	// @TODO パスワード暗号化
	$password = $_POST["password"];
	$user_name = $_POST['user_name'];

	session_start();
	$pdo = $this -> access_mysql();
	$status = 'none';

	if( isset($_SESSION['user_name']) ) {
	    $status = 'logged_in';
	} else if( isset($_POST['user_name']) && isset($_POST['password']) ) {
	    $query = "SELECT * FROM `users` WHERE `user_name` = :user_name AND `password` = :password AND `deleted` IS NOT TRUE";
	    $statement = $pdo -> prepare($query);
	    $statement -> bindValue(':user_name', $user_name, PDO::PARAM_STR);
	    $statement -> bindValue(':password', $password, PDO::PARAM_STR);

	    $statement -> execute();
	    $result = $statement->rowCount();

	    if( $result === 1 ) {
		$_SESSION["user_name"] = $_POST["user_name"];
		foreach ($statement as $row) {
		    $_SESSION['user_id'] = $row['user_id'];
		}

		$statement -> closeCursor();
		$status = 'success';

	    } else {

		$statement -> closeCursor();
		$status = "failed";

		$app->flash('error', 'User email is required');

		$app->redirect('/');
	    }
	}

	$loader = new Twig_Loader_Filesystem(__DIR__ . '/../templates');
	$twig = new Twig_Environment($loader);
	$template = $twig->loadTemplate('user/login.twig');

	return $template->render([ 'message' => $message, 'user_name' => $user_name, 'password' => $password ]);
    }

    public function logout() {

	if ( isset($_SESSION['user_id']) ) {
	    $message = "ログアウトしました。";
	} else {
	    $message = "セッションがタイムアウトしました。";
	}

	$_POST = array();
	session_start();
	$_SESSION = array();
	session_destroy();

	$app = \Slim\Slim::getInstance();
	$app->redirect('/');
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
	    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $e) {
	    echo 'データベース接続error' . $e->getMessage();
	}
	return $pdo;
    }

}
