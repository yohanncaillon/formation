<?php
namespace OCFram;

use \Entity\User;

class PasswordConfirmValidator extends Validator
{
    protected $Field;

    public function __construct($error, Field $Champs)
    {
        parent::__construct($error);
        $this->Field = $Champs;
    }

    public function isValid($value)
    {

        return $value == $this->Field->value();
    }
}