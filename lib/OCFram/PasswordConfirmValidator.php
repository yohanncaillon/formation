<?php
namespace OCFram;

use \Entity\User;

class PasswordConfirmValidator extends Validator
{
    protected $User;

    public function __construct($error, User $user)
    {
        parent::__construct($error);
        $this->User = $user;
    }

    public function isValid($value)
    {
        return $value == $this->User->password();
    }
}