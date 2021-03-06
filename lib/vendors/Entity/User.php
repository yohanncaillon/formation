<?php
namespace Entity;

use \OCFram\Entity;

class User extends Entity
{
    protected $MMC_id;
    protected $MMC_name;
    protected $MMC_password;
    protected $MMC_password_confirm;
    protected $MMC_status;
    protected $MMC_dateAdded;
    protected $MMC_datemodify;
    protected $MMC_email;

    const USER_ADMIN = 1;
    const USER_WRITER = 2;

    /**
     * @return mixed
     */
    public function password()
    {
        return $this->MMC_password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->MMC_password = $password;
    }

    /**
     * @return mixed
     */
    public function password_confirm()
    {
        return $this->MMC_password_confirm;
    }

    /**
     * @param mixed $MMC_password_confirm
     */
    public function setPassword_confirm($MMC_password_confirm)
    {
        $this->MMC_password_confirm = $MMC_password_confirm;
    }

    /**
     * @return mixed
     */
    public function name()
    {
        return $this->MMC_name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->MMC_name = $name;
    }

    /**
     * @return mixed
     */
    public function status()
    {
        return $this->MMC_status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->MMC_status = $status;
    }

    /**
     * @return mixed
     */
    public function id()
    {
        return $this->MMC_id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->MMC_id = $id;
    }

    /**
     * @return mixed
     */
    public function email()
    {
        return $this->MMC_email;
    }

    /**
     * @param mixed $MMC_email
     */
    public function setEmail($MMC_email)
    {
        $this->MMC_email = $MMC_email;
    }

    public static function getStatusName($id)
    {
        switch ($id) {

            case User::USER_ADMIN:
                return "administrateur";

            case User::USER_WRITER:
                return "Rédacteur";

            default:
                return "Status inconnu";
        }
    }


}