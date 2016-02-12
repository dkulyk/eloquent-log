<?php

namespace DKulyk\Eloquent\Properties;

use Illuminate\Support\Collection;

/**
 * Class MultipleCollection.
 */
class MultipleCollection extends Collection
{
    /**
     * @var Factory
     */
    protected $factory;

    /**
     * @var Property
     */
    protected $property;

    /**
     * Create a new values collection.
     *
     * @param mixed $items
     */
    public function __construct(Factory $factory, Property $property, $items = [])
    {
        $this->property = $property;
        $this->factory = $factory;
        parent::__construct($items);
    }

    /**
     * Add new value to property.
     *
     * @param mixed $value
     */
    public function add($value)
    {
        $this->factory->addValue($this->property, $value);
        $this->factory->updateValue($this->property->name);
    }

    public function setValues(Collection $values)
    {
        $this->items = $values->all();
    }
}
