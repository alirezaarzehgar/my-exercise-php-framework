<?php

namespace app\migrations;

use app\core\Application;
use app\core\DbModel;

class m0001_initial
{
    public function up()
    {
        $statement = Application::$app->db->pdo->prepare("CREATE TABLE IF NOT EXISTS users (
            id INT NOT NULL AUTO_INCREMENT,
            name VARCHAR(150) NOT NULL,
            email VARCHAR(150) NOT NULL,
            status TINYINT DEFAULT 0,
            password VARCHAR(100) NOT NULL,
            PRIMARY KEY (id)
        );");

        $statement->execute();
    }

    public function down()
    {
        echo "Down migration  {" . __CLASS__ . "}\n";
    }
}
