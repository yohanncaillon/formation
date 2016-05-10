<?php
namespace Model;

use \OCFram\Manager;
use \Entity\User;

abstract class UserManager extends Manager
{

    abstract public function insertUser(User $user);

    abstract public function authenticate($login, $password);

    abstract public function count();

    abstract public function deleteUserUsingId($id);

    abstract public function getUserUsingId($id);

    abstract protected function updateUser(User $user);

    abstract public function existsMemberUsingName($userName);
}