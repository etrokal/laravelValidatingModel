<?php

namespace Etrokal\ValidatingModel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

trait Validatable
{
    protected $storeRules = [];
    protected $updateRules = [];

    protected $attributeLabels = [];

    protected $errors = [];

    protected $validator;

    public static function bootValidatable()
    {
        self::creating(function ($model) {
            if (!$model->isStoreValid()) {
                return false;
            }
        });

        self::updating(function ($model) {
            if (!$model->isUpdateValid()) {
                return false;
            }
        });
    }

    protected function isValid($rules)
    {
        $this->validator = $this->generateValidator($rules);
        if ($this->validator->fails()) {
            $this->errors = $this->validator->errors->all();
            return false;
        } else {
            $this->errors = [];
            return true;
        }
    }

    protected function generateValidator($rules)
    {
        return Validator::make($this->attributes, $rules);
    }


    public function isUpdateValid()
    {
        return $this->isValid($this->updateRules);
    }

    public function isStoreValid()
    {
        return $this->isValid($this->storeRules);
    }


    public function getValidator()
    {
        return $this->validator;
    }
}
