<?php
namespace Model;

use \Entity\User;

class UsersManagerPDO extends UserManager
{

    public function insertUser(User $user)
    {
        $requete = $this->dao->prepare('INSERT INTO T_MEM_memberc SET MMC_name = :name, MMC_email = :email ,MMC_password = :password, MMC_status = :status, MMC_dateadded = NOW(), MMC_datemodify = NOW()');
        $requete->bindValue(':name', $user->name());
        $requete->bindValue(':password', password_hash($user->password(), PASSWORD_DEFAULT));
        $requete->bindValue(':email', $user->email());
        $requete->bindValue(':status', $user->status());
        $requete->execute();
        $user->setId($this->dao->lastInsertId());

        return $user;
    }

    public function count()
    {
        return $this->dao->query('SELECT COUNT(*) FROM T_MEM_memberc')->fetchColumn();
    }

    public function deleteUserUsingId($id)
    {
        $this->dao->exec('DELETE FROM T_MEM_memberc WHERE MMC_id = ' . (int)$id);
    }

    public function getUserUsingId($id)
    {
        $requete = $this->dao->prepare('SELECT MMC_id, MMC_name, MMC_password, MMC_dateadded, MMC_email, MMC_status, MMC_datemodify FROM T_MEM_memberc WHERE MMC_id = :id');
        $requete->bindValue(':id', (int)$id, \PDO::PARAM_INT);
        $requete->execute();

        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\User');

        if ($user = $requete->fetch()) {

            return $user;
        }

        return null;
    }

    protected function updateUser(User $user)
    {
        $requete = $this->dao->prepare('UPDATE T_MEM_memberc SET MMC_name = :name, MMC_password = :pass, MMC_datemodify = NOW() WHERE MMC_id = :id');

        $requete->bindValue(':name', $user->getAttribute("name"));
        $requete->bindValue(':pass', $user->getAttribute("pass"));
        $requete->bindValue(':id', $user->getAttribute("pass"), \PDO::PARAM_INT);

        $requete->execute();
    }

    public function getUserUsingName($name)
    {

        $requete = $this->dao->prepare('SELECT MMC_id, MMC_name, MMC_password, MMC_dateadded, MMC_email, MMC_status, MMC_datemodify FROM T_MEM_memberc WHERE MMC_name = :name');

        $requete->bindValue(':name', $name);

        $requete->execute();

        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\User');

        if ($user = $requete->fetch()) {

            return $user;
        }

        return null;

    }

    public function existsMemberUsingName($userName)
    {

        $requete = $this->dao->prepare('SELECT * FROM T_MEM_memberc WHERE MMC_name = :name');
        $requete->bindValue(':name', $userName);
        $requete->execute();

        return $requete->fetch(\PDO::FETCH_ASSOC) != false;

    }
}