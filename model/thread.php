<?php

class Thread
{

    public function create_thread() {
	$pdo = access_mysql();
	$query = "INSERT INTO `threads` (thread_name, user_id) VALUES (:thread_name, :user_id)";

	$statement = $pdo -> prepare($query);
	$statement -> bindvalue(':thread_name', $thread_name, pdo::PARAM_STR);
	$statement -> bindvalue(':user_id', $user_id, pdo::PARAM_INT);

	$thread_name = $_POST('thread_name');
	// @todo user_idを取得するロジック
	$user_id = ;
	$statement -> execute();
    }

    public function list_thread() {
	$pdo = access_mysql();
	$statement = $pdo -> query( "SELECT `thread_name` FROM `threads` WHERE `deleted` = false" );

	$result = $statement -> fetch(PDO::FETCH_ASSOC);
	return $result;
    }

    public function manage_thread() {
	$pdo = access_mysql();
	$query = "UPDATE `threads` SET thread_name = :thread_name WHERE `thread_id` = :thread_id AND `deleted` = false";

	$statement = $pdo -> prepare($query);
	$statement -> bindValue(':thread_id', $thread_id, PDO::PARAM_INT);

	// @TODO thread_idを取得するロジック
	$thread_id = 
	$statement -> execute();
    }

    public function delete_thread() {
	$pdo = access_mysql();
	$query = "UPDATE `threads` SET deleted = true WHERE `thread_id` = :thread_id AND `deleted` = false";

	$statement = $pdo -> prepare($query);
	
	// @TODO thread_idを取得するロジック
	$thread_id = 
	$statement -> execute();
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
