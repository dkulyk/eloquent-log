<?php

class LoggingTest extends TestCase
{
    /** @test */
    public function t1_logging_test()
    {
        $contact = ContactLogging::create(['email' => 'test2@example.com']);
        $contact->email = 'test3@example.com';
        $contact->save();
        static::assertEquals(2, $contact->logs->count());
        static::assertEquals(\DKulyk\Eloquent\Logging\Model::CREATE, $contact->logs[0]->type);
        static::assertEquals(\DKulyk\Eloquent\Logging\Model::UPDATE, $contact->logs[1]->type);

        static::assertEquals('test3@example.com', $contact->email);
        $contact = $contact->logs[0]->restore(true)->fresh();
        static::assertEquals('test2@example.com', $contact->email);
    }
}
