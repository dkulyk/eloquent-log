<?php

namespace DKulyk\Eloquent\Properties\Values;

use DKulyk\Eloquent\Properties\Value;

class DateValue extends Value
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['value'];

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d';
}
