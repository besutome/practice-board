<?php 

class Thread
{

    public function create_thread() {

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
