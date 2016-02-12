<?php

class TransactionTest extends TestCase
{
    /** @test */
    public function rollback_test()
    {
        ContactTransaction::saved(
            function () {
                throw new Exception();
            }, 1
        );

        try {
            ContactTransaction::create(
                [
                    'email' => 'test@example.com',
                ]
            );
        } catch (Exception $e) {
        }

        static::assertNull(ContactTransaction::query()->first());
    }
}
