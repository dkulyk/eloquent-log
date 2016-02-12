<?php

/**
 * Class Contact.
 *
 * @property string $email
 */
class Contact extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'contacts';

    protected $fillable = ['email'];
}
