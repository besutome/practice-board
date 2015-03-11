<?php

class Reply 
{

    public function create_reply() {
	$pdo = access_mysql();
	$query = "INSERT INTO `replies` (board_id, user_id, message) VALUES (:board_id, :user_id, :message)";

	$statement = $pdo -> prepare($query);
	$statement -> bindvalue(':board_id', $board_id, pdo::PARAM_INT);
	$statement -> bindvalue(':user_id', $user_id, pdo::PARAM_INT);
	$statement -> bindvalue(':message', $message, pdo::PARAM_STR);

	// @todo board_idを取得するロジック
	$board_id = ;
	// @todo user_idを取得するロジック
	$user_id = ;
	$message = $_POST('message');
	$statement -> execute();
    }

    public function list_reply() {
	$pdo = access_mysql();
	$statement = $pdo -> prepare( "SELECT `message` FROM `replies` WHERE `user_id` = :user_id" );
	$statement -> bindvalue(':user_id', $user_id, pdo::PARAM_STR);
	
	// @todo user_idを取得するロジック
	$user_id = ;
	$statement -> execute();

	$result = $statement -> fetch(PDO::FETCH_ASSOC);
	return $result;
    }

    public function manage_reply() {
	$pdo = access_mysql();
	$query = "UPDATE `replies` SET message = :message WHERE `reply_id` = :reply_id";

	$statement = $pdo -> prepare($query);
	$statement -> bindValue(':message', $message, PDO::PARAM_STR);
	$statement -> bindValue(':reply_id', $reply_id, PDO::PARAM_INT);

	$message = $_POST['message'];
	// @todo reply_idを取得するロジック
	$reply_id = ;
	$statement -> execute();

    }

    public function delete_reply() {
	$pdo = access_mysql();
	$query = "UPDATE `replies` SET deleted = true WHERE `reply_id` = :reply_id AND `deleted` = false";

	$statement = $pdo -> prepare($query);
	$statement -> bindValue(':reply_id', $reply_id, PDO::PARAM_INT);
	
	// @TODO reply_idを取得するロジック
	$reply_id = ;
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
