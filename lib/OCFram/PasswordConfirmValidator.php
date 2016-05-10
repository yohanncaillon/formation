<?php
namespace OCFram;

use \Entity\User;

class PasswordConfirmValidator extends Validator
{
    protected $user;

    public function __construct($error, User $user)
    {
        parent::__construct($error);
        $this->user = $user;
    }

    public function isValid($value)
    {
        return $value == $this->user->password();
    }
}