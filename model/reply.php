<?php

class Reply 
{

    public function create_reply() {
	$pdo = access_mysql();
	$query = "
	    CREATE TABLE `:table_name` ( 
	    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	    FOREIGN KEY (`user_id`) REFERENCES `user`(`id`),
	    FOREIGN KEY (`user_name`) REFERENCES `user`(`name`),
	    `reply` TEXT,
	    `creation_time` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	    `modification_time` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	    `deleted` BOOLEAN,
	");

	$statement = $pdo -> prepare($query);
	$statemwnt -> bindParam(':table_name', $table_name, PDO::PARAM_STR);

	$name = $_POST('table_name');
	$statement -> execute();
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
