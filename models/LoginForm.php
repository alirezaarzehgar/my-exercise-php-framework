<?php

namespace app\models;

use app\core\Application;
use app\core\Model;

class LoginForm extends Model
{
    public string $field_email = '';
    public string $field_password = '';

    public function rules(): array
    {
        return [
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL],
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 7]]
        ];
    }

    public function labels(): array
    {
        return [
            'email' => 'Email',
            'password' => 'Password',
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

    public function login()
    {
        $user = User::findOne(['email' => $this->email]);

        if (!$user) {
            $this->addError('email', 'user');
            return false;
        }

        if (!password_verify($this->password, $user[0]['password'])) {
            $this->addError('password', 'password');
            return false;
        }


        return true;
    }
}
