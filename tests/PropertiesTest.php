<?php

use DKulyk\Eloquent\Properties\Factory;
use DKulyk\Eloquent\Properties\Values;

class PropertiesTest extends TestCase
{
    /**
     * @var Factory
     */
    protected $factory;

    public function setUp()
    {
        parent::setUp();
        Factory::registerType(Factory::TYPE_INTEGER, Values\IntegerValue::class);
        $this->factory = new Factory(new ContactProperties());
    }

    /** @test */
    public function create_property_test()
    {
        static::assertTrue(Factory::addProperty(ContactProperties::class, 'single', Factory::TYPE_INTEGER)->exists);
        static::assertTrue(Factory::addProperty(ContactProperties::class, 'multiple', Factory::TYPE_INTEGER, true)->exists);
    }

    /** @test */
    public function it_should_make_a_casted_value()
    {
        $property = Factory::addProperty(ContactProperties::class, 'single', Factory::TYPE_INTEGER);
        $value = $this->factory->addValue($property, 'aa');

        static::assertInstanceOf(Values\IntegerValue::class, $value);
        static::assertEquals(0, $value->value);
    }
}
