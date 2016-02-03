<?php

namespace DKulyk\Eloquent;

use Throwable;

/**
 * Class Transaction
 *
 * @mixed Eloquent
 * @property bool $savingTransaction
 */
trait Transaction
{
    /**
     * Save the model to the database.
     *
     * @param  array $options
     *
     * @return bool
     * @throws Throwable
     */
    public function save(array $options = [])
    {
        if (!property_exists($this, 'savingTransaction') || $this->savingTransaction) {
            return $this->saveWithinTransaction($options);
        }
        return parent::save($options);
    }

    /**
     * Save the model to the database with in transaction.
     *
     * @param array $options
     *
     * @return bool
     * @throws Throwable
     */
    public function saveWithinTransaction(array $options = [])
    {
        $connection = $this->getConnection();
        $connection->beginTransaction();
        try {
            if ($saved = parent::save($options)) {
                $connection->commit();
            } else {
                $connection->rollBack();
            }
            return $saved;
        } catch (Throwable $e) {
            $connection->rollBack();
            throw  $e;
        }
    }
}