<?php

use \DKulyk\Eloquent\Properties\Factory;
use \DKulyk\Eloquent\Properties\Values;

return [
    'logging_table'    => 'eloquent_log',
    'properties_table' => 'properties',
    'values_table'     => 'property_values',
    'property_types'   => [
        Factory::TYPE_DATETIME => Values\DateTimeValue::class,
        Factory::TYPE_DATE     => Values\DateValue::class,
        Factory::TYPE_STRING   => Values\StringValue::class,
        Factory::TYPE_INTEGER  => Values\IntegerValue::class,
        Factory::TYPE_BOOLEAN  => Values\BooleanValue::class,
    ],
];
