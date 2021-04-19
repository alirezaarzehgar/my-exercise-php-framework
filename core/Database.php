<?php

namespace app\core;

class Database
{
    public \PDO $pdo;

    public function __construct(array $config)
    {
        $dsn = $config['dsn'];
        $user = $config['user'];
        $password = $config['password'];

        try {
            $this->pdo = new \PDO($dsn, $user, $password);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
        }
    }

    public function applyMigration()
    {
        $this->createMigrationTable();
        $appliedMigrations = $this->getAppliedMigrations();

        $files = scandir(Application::$ROOT_DIR . '/migrations');
        $toApplyMigrations = array_diff($files, $appliedMigrations);

        $newMigrations = [];

        foreach ($toApplyMigrations as $migration) {
            if ($migration === '.' || $migration === '..')
                continue;

            $className = pathinfo($migration, PATHINFO_FILENAME);
            $dynamicClassName = "\\app\migrations\\$className";
            $instance = new $dynamicClassName();

            $this->log("appying $migration");
            $instance->up();

            $newMigrations[] = $migration;
        }

        if (!empty($newMigrations))
            $this->saveMigration($newMigrations);
        else
            $this->log("All migrations are applied");
    }

    public function createMigrationTable()
    {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
                id INT NOT NULL AUTO_INCREMENT,
                migration VARCHAR(150) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id)
            );
        ");
    }

    public function getAppliedMigrations()
    {
        $statement = $this->pdo->prepare("SELECT migration FROM migrations");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function saveMigration(array $migrations)
    {
        $str = implode(",", array_map(fn ($m) => "('$m')", $migrations));

        $statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES
            $str
        ");

        $statement->execute();
    }

    public function log($message)
    {
        echo '[' . date('Y-m-d H:i:s') . "] - " . $message . PHP_EOL;
    }
}
