<?php

namespace DKulyk\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Validator;

/**
 * Class Validation.
 *
 * @mixed Eloquent
 */
trait Validation
{
    /**
     * @var \Illuminate\Validation\Validator
     */
    protected $validator;

    /**
     * @var bool
     */
    protected $autoValidation = true;

    /**
     * The "booting" method of the trait.
     */
    public static function bootValidation()
    {
        static::saving(
            function (Eloquent $model) {
                /* @var Eloquent|Validation $model */
                return $model->autoValidation ? $model->validate() : true;
            }
        );
    }

    /**
     * Validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [];
    }

    /**
     * @return \Illuminate\Validation\Validator
     */
    public function getValidator()
    {
        if ($this->validator === null) {
            $this->validator = Validator::make([], $this->rules());
        }

        return $this->validator;
    }

    /**
     * Validate model attributes.
     *
     * @return bool
     */
    public function validate()
    {
        $validator = $this->getValidator();
        $validator->setData($this->attributesToArray());

        return $validator->passes();
    }

    /**
     * Set auto validation flag.
     *
     * @param bool $value
     */
    public function setAutoValidation($value)
    {
        $this->autoValidation = $value;
    }

    /**
     * Disable auto validation on save.
     */
    public function disableAutoValidation()
    {
        $this->setAutoValidation(false);
    }
}
