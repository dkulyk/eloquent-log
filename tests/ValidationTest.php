<?php


class ValidationTest extends TestCase
{
    /** @test */
    public function validation_failed()
    {
        $contact = new ContactValidation();
        static::assertFalse($contact->validate());
        static::assertFalse($contact->save());
    }

    /** @test */
    public function validation_passed()
    {
        $contact = new ContactValidation();
        $contact->email = 'test@example.com';
        static::assertTrue($contact->validate());
        static::assertTrue($contact->save());
    }

    /** @test */
    public function disabled_auto_validation()
    {
        $contact = new ContactValidation();
        $contact->disableAutoValidation();
        $contact->email = ''; //not email
        static::assertFalse($contact->validate());
        static::assertTrue($contact->save());
    }
}
