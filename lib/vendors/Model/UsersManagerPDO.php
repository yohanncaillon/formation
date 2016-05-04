<?php
namespace Model;

use \Entity\User;

class UsersManagerPDO extends UserManager
{

    public function add($login, $password)
    {
        $requete = $this->dao->prepare('INSERT INTO T_MEM_memberc SET MMC_name = :name, MMC_password = :password, MMC_dateadded = NOW(), MMC_datemodify = NOW()');
        $requete->bindValue(':name', $login);
        $requete->bindValue(':password', password_hash($password, PASSWORD_DEFAULT));
        $result = $requete->execute();
        
        return $result;
    }

    public function count()
    {
        return $this->dao->query('SELECT COUNT(*) FROM T_MEM_memberc')->fetchColumn();
    }

    public function delete($id)
    {
        $this->dao->exec('DELETE FROM T_MEM_memberc WHERE MMC_id = ' . (int)$id);
    }

    public function getUnique($id)
    {
        $requete = $this->dao->prepare('SELECT MMC_id, MMC_name, MMC_password, MMC_dateadded, MMC_datemodify FROM T_MEM_memberc WHERE MMC_id = :id');
        $requete->bindValue(':id', (int)$id, \PDO::PARAM_INT);
        $requete->execute();

        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\OCFram\User');

        if ($user = $requete->fetch()) {
            $user->setDateAjout(new \DateTime($user->dateAjout()));
            $user->setDateModif(new \DateTime($user->dateModif()));

            return $user;
        }

        return null;
    }

    protected function modify(User $user)
    {
        $requete = $this->dao->prepare('UPDATE T_MEM_memberc SET MMC_name = :name, MMC_password = :pass, MMC_datemodify = NOW() WHERE MMC_id = :id');

        $requete->bindValue(':name', $user->getAttribute("name"));
        $requete->bindValue(':pass', $user->getAttribute("pass"));
        $requete->bindValue(':id', $user->getAttribute("pass"), \PDO::PARAM_INT);

        $requete->execute();
    }

    public function authenticate($login, $password)
    {

        $requete = $this->dao->prepare('SELECT MMC_id, MMC_name, MMC_password, MMC_dateadded, MMC_datemodify FROM T_MEM_memberc WHERE MMC_name = :name');

        $requete->bindValue(':name', $login);

        $requete->execute();

        if($result = $requete->fetch()) {

            $user = new User();
            $user->setId($result["MMC_name"]);
            $user->setName($result["MMC_name"]);
            $user->setPassword($result["MMC_password"]);
            return $user;
        }

        return null;

    }
}