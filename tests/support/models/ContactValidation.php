<?php

class ContactValidation extends Contact
{
    use \DKulyk\Eloquent\Validation;

    protected function rules()
    {
        return [
            'email' => 'required|email',
        ];
    }
}
