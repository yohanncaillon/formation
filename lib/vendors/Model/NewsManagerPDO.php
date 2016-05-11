<?php
namespace Model;

use \Entity\News;

class NewsManagerPDO extends NewsManager
{
    protected function insertNews(News $news)
    {
        $requete = $this->dao->prepare('INSERT INTO news SET auteur = :auteur, titre = :titre, contenu = :contenu, tag = :tag, dateAjout = NOW(), dateModif = NOW()');

        $requete->bindValue(':titre', $news->titre());
        $requete->bindValue(':auteur', $news->auteur());
        $requete->bindValue(':tag', $news->tag());
        $requete->bindValue(':contenu', $news->contenu());

        $requete->execute();
    }

    public function count()
    {
        return $this->dao->query('SELECT COUNT(*) FROM news')->fetchColumn();
    }

    public function deleteNewsUsingId($id)
    {
        $this->dao->exec('DELETE FROM news WHERE id = ' . (int)$id);
    }

    public function getNews_a($debut = -1, $limite = -1)
    {

        $sql = 'SELECT id, auteur, titre, contenu, tag, dateAjout, dateModif FROM news ORDER BY id DESC';

        if ($debut != -1 || $limite != -1) {
            $sql .= ' LIMIT ' . (int)$limite . ' OFFSET ' . (int)$debut;
        }

        $requete = $this->dao->query($sql);
        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\News');

        $listeNews_a = $requete->fetchAll();

        foreach ($listeNews_a as $news) {
            $news->setDateAjout(new \DateTime($news->dateAjout()));
            $news->setDateModif(new \DateTime($news->dateModif()));
        }

        $requete->closeCursor();

        return $listeNews_a;
    }

    public function getNewsUsingId($id)
    {
        $requete = $this->dao->prepare('SELECT id, auteur, titre, contenu, tag, dateAjout, dateModif FROM news WHERE id = :id');
        $requete->bindValue(':id', (int)$id, \PDO::PARAM_INT);
        $requete->execute();

        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\News');

        if ($news = $requete->fetch()) {
            $news->setDateAjout(new \DateTime($news->dateAjout()));
            $news->setDateModif(new \DateTime($news->dateModif()));

            return $news;
        }

        return null;
    }

    protected function updateNews(News $news)
    {
        $requete = $this->dao->prepare('UPDATE news SET titre = :titre, contenu = :contenu, tag = :tag, dateModif = NOW() WHERE id = :id');

        $requete->bindValue(':titre', $news->titre());
        $requete->bindValue(':contenu', $news->contenu());
        $requete->bindValue(':tag', $news->tag());
        $requete->bindValue(':id', $news->id(), \PDO::PARAM_INT);

        $requete->execute();
    }

    public function getNewsUsingUserId($userId, $debut = -1, $limite = -1)
    {

        $sql = 'SELECT id, auteur, titre, contenu, tag, dateAjout, dateModif FROM news WHERE auteur = :userId ORDER BY id DESC';

        if ($debut != -1 || $limite != -1) {
            $sql .= ' LIMIT ' . (int)$limite . ' OFFSET ' . (int)$debut;
        }

        $requete = $this->dao->prepare($sql);
        $requete->bindValue(':userId', $userId);
        $requete->execute();
        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\News');

        $listeNews = $requete->fetchAll();

        foreach ($listeNews as $news) {
            $news->setDateAjout(new \DateTime($news->dateAjout()));
            $news->setDateModif(new \DateTime($news->dateModif()));
        }

        $requete->closeCursor();

        return $listeNews;

    }
}