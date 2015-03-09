<?php

class User
{

    public function create_user() {
	$key = $_POST('key');

	// @TODO 'unko'を暗号化処理
	if ($key !== 'unko') {
	    throw new Exception('KEYの不一致');
	}

	try {

	    $pdo = accsess_mysql();

	    // @TODO nameの重複チェック
	    $statement = $pdo -> prepare( "INSERT INTO user (user_name, password) VALUES (:user_name, :password)" );
	    $statement -> bindParam(':user_name', $user_name, PDO::PARAM_STR);
	    $statement -> bindValue(':password', $password, PDO::PARAM_INT);

	    $user_name = $_POST('name');
	    $password = $_POST('pass');
	    $statement -> execute();

	} catch(Exception $e) {
	    echo '不正なアクセス';
	}
    }

    public function login() {

	session_start();

    }

    public function logout() {

	if (isset($_SESSION['USERID'])) {
	    $message = "ログアウトしました。";
	}
	else {
	    $message = "セッションがタイムアウトしました。";
	}

	session_start();
	$_SESSION = array();

	if (ini_get("session.use_cookies")) {
	    $params = session_get_cookie_params();
	    setcookie(session_name(), '', time() - 42000,
		$params["path"], $params["domain"],
		$params["secure"], $params["httponly"]
	    );
	}
	session_destroy();

	HTTP::redirect('/');
	return $message;
    }

    private function access_mysql() {
	try {
	    // @TODO user名、パスワードを暗号化処理
	    $db = 'mysql:dbname=board;host=localhost';
	    $user = 'root';
	    $password = 'password';
	    $pdo = new PDO($db, $user, $password);
	} catch (PDOException $e) {
	    echo 'データベース接続error' . $e->getMessage();
	}
	return $pdo;
    }

}
