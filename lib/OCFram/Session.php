<?php
namespace OCFram;

use Entity\User;

session_start();

class Session
{

    const ADMIN_INT = 1;

    public function getAttribute($attr)
    {
        return isset($_SESSION[$attr]) ? $_SESSION[$attr] : null;
    }

    public function getFlash()
    {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);

        return $flash;
    }

    public function hasFlash()
    {
        return isset($_SESSION['flash']);
    }

    public static function isAuthenticated()
    {
        return isset($_SESSION['auth']) && $_SESSION['auth'] === true;
    }

    public function setAttribute($attr, $value)
    {
        $_SESSION[$attr] = $value;
    }

    public function setAuthenticated($authenticated = true, $user = null)
    {
        if (!is_bool($authenticated))
        {
            throw new \InvalidArgumentException('La valeur spécifiée à la méthode User::setAuthenticated() doit être un boolean');
        }
        if($user != null) {
            $_SESSION['auth'] = $authenticated;
            $_SESSION['authId'] = $user->id();
            $_SESSION['authName'] = $user->name();
            $_SESSION['authStatus'] = $user->status();
        }

    }

    public static function isAllowed($userName)
    {
        if(!isset($_SESSION['auth']))
            return false;

        if (self::isAdmin())
            return true;

        return $_SESSION['authName'] == $userName;
    }

    public static function isAdmin() {

        return $_SESSION['authStatus'] == User::USER_ADMIN;
    }

    public function setFlash($value)
    {
        $_SESSION['flash'] = $value;
    }
    
}