<?php

class ManageUser
{

    public function create_user() {
	$key = $_POST('key');

	// @TODO 'unko'を暗号化処理
	if ($key !== 'unko') {
	    throw new Exception('KEYの不一致');
	}

	try {

	   try {
		// @TODO user名、パスワードを暗号化処理
		$pdo = new PDO('mysql:host=localhost;dbname=board;charset=utf8','user','password', array(PDO::ATTR_EMULATE_PREPARES => false));
	   } catch (PDOException $e) {
		echo 'データベース接続error' . $e->getMessage();
	   }

	   $stmt = $pdo -> prepare("INSERT INTO user (name, pass) VALUES (:name, :pass)");
	   $stmt->bindParam(':name', $name, PDO::PARAM_STR);
	   $stmt->bindValue(':pass', $pass, PDO::PARAM_INT);

	   $name = $_POST('name');
	   $pass = $_POST('pass');
	   $stmt->execute();

	} catch(Exception $e) {
	   echo '不正なアクセス';
	}
    }

}
