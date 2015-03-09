<?php

class Thread
{

    public function create_thread() {

	$pdo = access_mysql();
	$query = "INSERT INTO threads (thread_name, user_id) VALUES (:thread_name, :user_id)";

	$statement = $pdo -> prepare($query);
	$statemwnt -> bindParam(':thread_name', $thread_name, PDO::PARAM_STR);
	$statemwnt -> bindParam(':user_id', $user_id, PDO::PARAM_STR);

	$thread_name = $_POST('thread_name');
	// @TODO user_idを取得するロジック
	$user_id = $_POST('user_id');
	$statement -> execute();
    }

    public function list_thread() {
	
    }

    public function manage_thread() {

    }

    public function delete_thread() {


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
