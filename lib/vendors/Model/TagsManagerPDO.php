<?php
namespace Model;

use Entity\News;
use \Entity\Tag;

class TagsManagerPDO extends TagsManager {


    public function insertTag(Tag $Tag)
    {

        $requete = $this->dao->prepare('INSERT INTO t_tag_tagsc SET TTC_name = :name');
        $requete->bindValue(':name', $Tag->name());
        $requete->execute();
        $Tag->setId($this->dao->lastInsertId());

        return $Tag;

    }

    public function getTagUsingName($name)
    {
        $requete = $this->dao->prepare('SELECT TTC_id, TTC_name FROM t_tag_tagsc WHERE TTC_name = :name');
        $requete->bindValue(':name', $name);
        $requete->execute();

        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Tag');

        if ($Tag = $requete->fetch()) {

            return $Tag;
        }

        return null;
    }

    public function existsTagUsingName($name)
    {
        $requete = $this->dao->prepare('SELECT * FROM T_tag_tagsc WHERE TTC_name = :name');
        $requete->bindValue(':name', $name);
        $requete->execute();

        return $requete->fetch(\PDO::FETCH_ASSOC) != false;
    }

    public function deleteTagUsingId($id)
    {
        $this->dao->exec('DELETE FROM t_tag_tagsc WHERE TTC_id = ' . (int)$id);
        $this->dao->exec('DELETE FROM t_tag_tagsd WHERE ttd_fk_ttc = ' . (int)$id);

    }

    public function searchTagUsingName($name)
    {
        $requete = $this->dao->prepare('SELECT * FROM T_tag_tagsc WHERE TTC_name LIKE :name');
        $requete->bindValue(':name', $name."%");
        $requete->execute();
        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Tag');

        return $requete->fetchAll();
    }
}