<?php

namespace app\core\form;

use app\core\Application;
use app\core\Model;

class Field
{
    public function __construct(
        public Model $model,
        public string $attribute,
        public string $type
    ) {
    }

    public function __toString()
    {
        return sprintf(
            '
            <div class="form-group">
                <label>%s</label>
                <input type="%s" name="%s" value="%s" class="form-control %s"></input>
                <div class="invalid-feedback">
                    %s
                </div>
            </div>        
        ',
            $this->model->labels()[$this->attribute] ?? $this->attribute,
            $this->type,
            $this->attribute,
            $this->model->{$this->attribute},
            $this->model->hasError($this->attribute) ? 'is-invalid' : '',
            $this->model->getFirstError($this->attribute)
        );
    }
}
