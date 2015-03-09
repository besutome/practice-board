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
	    $stmt = $pdo -> prepare( "INSERT INTO user (name, pass) VALUES (:name, :pass)" );
	    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
	    $stmt->bindValue(':pass', $pass, PDO::PARAM_INT);

	    $name = $_POST('name');
	    $pass = $_POST('pass');
	    $stmt->execute();

	} catch(Exception $e) {
	    echo '不正なアクセス';
	}
    }

    public function login() {

	session_start();


	$pdo = accsess_mysql();

	$stmt = $pdo -> prepare( "INSERT INTO user (name, pass) VALUES (:name, :pass)" );
	$stmt->bindParam(':name', $name, PDO::PARAM_STR);
	$stmt->bindValue(':pass', $pass, PDO::PARAM_INT);

	$name = $_POST('name');
	$pass = $_POST('pass');
	$stmt->execute();
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
