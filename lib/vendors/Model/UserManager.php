<?php
namespace Model;

use \OCFram\Manager;
use \Entity\User;

abstract class UserManager extends Manager
{

    abstract public function add($login, $password);

    abstract public function authenticate($login, $password);

    abstract public function count();

    abstract public function delete($id);

    abstract public function getUnique($id);

    abstract protected function modify(User $user);
}