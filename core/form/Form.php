<?php

namespace app\core\form;

use app\core\Model;

class Form
{
    public static function begin($action, $method)
    {
        echo sprintf('<form action"%s" method="%s">', $action, $method);
        return new Form();
    }

    public function field(Model $model, $attribute, $type = 'text')
    {
        return new Field($model, $attribute, $type);
    }

    public static function end()
    {
        echo '</form>';
    }
}
