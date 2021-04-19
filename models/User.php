<?php

namespace app\models;

use app\core\DbModel;
use app\core\Model;

class User extends DbModel
{
    public const STATUS_DEACTIVE = 0;
    public const STATUS_ACTIVE = 1;
    public const STATUS_DELETED = 2;

    public string $field_name = '';
    public string $field_email = '';
    public int $field_status = self::STATUS_DEACTIVE;
    public string $field_password = '';

    public static function tableName(): string
    {
        return 'users';
    }

    public function labels(): array
    {
        return [
            'name' => 'Enter your name',
            'email' => 'Enter your email',
            'password' => 'Enter your password',
        ];
    }

    public function attributes(): array
    {
        $attributes = [];

        $properties = get_object_vars($this);
        foreach ($properties as $attr => $property)
            if (strpos($attr, 'field_') === 0)
                $attributes[] = substr($attr, strlen('field_'));

        return $attributes;
    }

    public function rules()
    {
        return [
            'name' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 4], [self::RULE_MAX, 'max' => 20]],
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL, [
                self::RULE_UNIQUE, 'class' => self::class
            ]],
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 7], [self::RULE_MAX, 'max' => 20]]
        ];
    }

    public function register()
    {
        $this->status = self::STATUS_ACTIVE;
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        return $this->save();
    }
}
