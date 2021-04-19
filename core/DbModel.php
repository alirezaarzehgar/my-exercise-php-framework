<?php

namespace app\core;

abstract class DbModel extends Model
{
    abstract public static function tableName(): string;

    abstract public function attributes(): array;

    public function save()
    {
        $tableName = $this->tableName();
        $attributes = $this->attributes();

        $params = array_map(fn ($attr) => ":$attr", $attributes);

        $SQL = "INSERT INTO $tableName (" . implode(",", $attributes) . ") VALUES (
            " . implode(",", $params) . "
        );";

        $statement = $this->prepare($SQL);

        foreach ($attributes as $attribute)
            $statement->bindValue(":$attribute", $this->{$attribute});

        $statement->execute();
        return true;
    }

    public static function prepare($sql)
    {
        return Application::$app->db->pdo->prepare($sql);
    }

    public static function findOne(array $where)
    {
        $tableName = static::tableName();
        $attributes = array_keys($where);

        $sql = implode("AND", array_map(fn ($attr) => "$attr = :$attr", $attributes));

        $statement = static::prepare("SELECT * FROM $tableName WHERE $sql");
        foreach ($where as $key => $item)
            $statement->bindValue(":$key", $item);

        $statement->execute();

        return $statement->fetchAll();
    }
}
