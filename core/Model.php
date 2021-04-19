<?php

namespace app\core;

abstract class Model
{
    public const RULE_REQUIRED = 'required';
    public const RULE_EMAIL = 'email';
    public const RULE_MAX = 'max';
    public const RULE_MIN = 'min';
    public const RULE_UNIQUE = 'unique';
    public const RULE_MATCH = 'match';

    public array $errors = [];

    public function loadData(array $data)
    {
        foreach ($data as $attribute => $value)
            $this->{$attribute} = $value;
    }

    abstract public function rules();
    abstract public function labels(): array;

    public function validate()
    {
        foreach ($this->rules() as $attribute => $rules) {
            $value = $this->{$attribute};

            foreach ($rules as $rule) {
                $ruleName = $rule;
                if (!is_string($rule))
                    $ruleName = $rule[0];

                if ($ruleName === self::RULE_REQUIRED && !$value)
                    $this->addError($attribute, self::RULE_REQUIRED);

                if ($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL))
                    $this->addError($attribute, self::RULE_EMAIL);

                if ($ruleName === self::RULE_MIN && strlen($value) < $rule['min'])
                    $this->addError($attribute, self::RULE_MIN, $rule);

                if ($ruleName === self::RULE_MAX && strlen($value) > $rule['max'])
                    $this->addError($attribute, self::RULE_MAX, $rule);

                if ($ruleName === self::RULE_MATCH && $value !== $this->{$rule['match']})
                    $this->addError($attribute, self::RULE_MATCH, $rule);

                if ($ruleName === self::RULE_UNIQUE) {
                    $className = $rule['class'];
                    $uniqueAttr = $rule['attribute'] ?? $attribute;
                    $tableName = $className::tableName();

                    $statement = Application::$app->db->pdo->prepare("SELECT * FROM $tableName WHERE $uniqueAttr = :attr");
                    $statement->bindValue(":attr", $value);
                    $statement->execute();

                    $record = $statement->fetchObject();
                    if ($record)
                        $this->addError($attribute, self::RULE_UNIQUE, ['field' => $attribute]);
                }
            }
        }

        return empty($this->errors);
    }

    public function addError(string $attribute, string $rule, array $params = [])
    {
        $messages = $this->errorMessages();
        foreach ($params as $key => $value)
            $messages = str_replace("{{$key}}", $value, $messages);

        $this->errors[$attribute][] = $messages[$rule];
    }

    public function errorMessages()
    {
        return [
            'required' => 'This field is required',
            'email' => 'This field be valid email',
            'min' => 'Min lenght of this field must be {min}',
            'max' => 'Max lenght of this field must be {max}',
            'unique' => 'Record with this {field} already exists',
            'match' => 'This field must be same as {match}',
            'user' => 'User dose not exist',
            'password' => 'Incorrect password'
        ];
    }


    public function hasError($attribute)
    {
        return $this->errors[$attribute] ?? false;
    }

    public function getFirstError(string $attribute)
    {
        return $this->errors[$attribute][0] ?? false;
    }
}
