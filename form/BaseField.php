<?php

namespace thecodeholic\phpmvc\form;
use thecodeholic\phpmvc\Model;

abstract class BaseField
{

    public Model $model;
    public string $attribute;

    abstract public function renderInput(): string;

    public function __construct($model, string $attribute)
    {
        $this->model = $model;
        $this->attribute = $attribute;
    }

    public function __toString()
    {
        return sprintf('
            <div class="mb-3">
                <label>%s</label>
                %s
                <div class="invalid-feedback">
                    %s
                </div>
            </div>
        ',
            $this->model->getLabel($this->attribute) ?? $this->attribute,
            $this->renderInput(),
            $this->model->getFirstError($this->attribute)
        );
    }
}