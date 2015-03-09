<?php

class Reply 
{

    public function create_reply() {

    }

    public function list_reply() {


    }

    public function manage_reply() {



    }

    public function delete_reply() {



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
