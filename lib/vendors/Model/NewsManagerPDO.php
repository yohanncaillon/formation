<?php
namespace Model;

use \Entity\News;

class NewsManagerPDO extends NewsManager
{
    protected function insertNews(News $News)
    {

        $requete = $this->dao->prepare('INSERT INTO news SET auteur = :auteur, titre = :titre, contenu = :contenu, dateAjout = NOW(), dateModif = NOW()');

        $requete->bindValue(':titre', $News->titre());
        $requete->bindValue(':auteur', $News->auteur());
        $requete->bindValue(':contenu', $News->contenu());

        $requete->execute();
        $newsId = $this->dao->lastInsertId();

        foreach ($News->tag() as $Tag) {

            $requete = $this->dao->prepare('INSERT INTO t_tag_tagsd SET ttd_fk_tnc = :newsId, ttd_fk_ttc = :tagId');
            $requete->bindValue(':newsId', $newsId);
            $requete->bindValue(':tagId', $Tag->id());
            $requete->execute();

        }
    }

    public function count()
    {
        return $this->dao->query('SELECT COUNT(*) FROM news')->fetchColumn();
    }

    public function deleteNewsUsingId($id)
    {
        $this->dao->exec('DELETE FROM news WHERE id = ' . (int)$id);
        $this->dao->exec('DELETE FROM t_tag_tagsd WHERE ttd_fk_tnc = ' . (int)$id);
    }

    public function getNews_a($debut = -1, $limite = -1)
    {

        $sql = 'SELECT id, auteur, titre, contenu, dateAjout, dateModif FROM news ORDER BY id DESC';

        if ($debut != -1 || $limite != -1) {
            $sql .= ' LIMIT ' . (int)$limite . ' OFFSET ' . (int)$debut;
        }

        $requete = $this->dao->query($sql);
        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\News');

        $listeNews_a = $requete->fetchAll();

        foreach ($listeNews_a as $news) {
            $news->setDateAjout(new \DateTime($news->dateAjout()));
            $news->setDateModif(new \DateTime($news->dateModif()));
            $this->getTag_a($news);
        }

        $requete->closeCursor();

        return $listeNews_a;
    }

    public function getNewsUsingId($id)
    {
        $requete = $this->dao->prepare('SELECT id, auteur, titre, contenu, dateAjout, dateModif FROM news WHERE id = :id');
        $requete->bindValue(':id', (int)$id, \PDO::PARAM_INT);
        $requete->execute();

        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\News');

        if ($news = $requete->fetch()) {
            $news->setDateAjout(new \DateTime($news->dateAjout()));
            $news->setDateModif(new \DateTime($news->dateModif()));
            $this->getTag_a($news);

            return $news;
        }

        return null;
    }

    protected function updateNews(News $News)
    {
        $requete = $this->dao->prepare('UPDATE news SET titre = :titre, contenu = :contenu, dateModif = NOW() WHERE id = :id');

        $requete->bindValue(':titre', $News->titre());
        $requete->bindValue(':contenu', $News->contenu());
        $requete->bindValue(':id', $News->id(), \PDO::PARAM_INT);
        $requete->execute();

        $listeChangeTags = array('newTags' => $News->tag());

        $this->getTag_a($News);
        $listeChangeTags['oldTags'] = $News->tag();

        foreach ($listeChangeTags['newTags'] as $Tag) {

            // si un tag a été ajouté
            if (!in_array($Tag, $listeChangeTags['oldTags'])) {

                $requete = $this->dao->prepare('INSERT INTO t_tag_tagsd SET ttd_fk_tnc = :newsId, ttd_fk_ttc = :tagId');
                $requete->bindValue(':newsId', $News->id());
                $requete->bindValue(':tagId', $Tag->id());
                $requete->execute();

            }

        }

        foreach ($listeChangeTags['oldTags'] as $Tag) {

            // si un tag a été retiré
            if (!in_array($Tag, $listeChangeTags['newTags'])) {
                $requete = $this->dao->prepare('DELETE FROM t_tag_tagsd WHERE ttd_fk_tnc = :newsId AND ttd_fk_ttc = :tagId');
                $requete->bindValue(':newsId', $News->id());
                $requete->bindValue(':tagId', $Tag->id());
                $requete->execute();
            }

        }

    }

    public function getNewsUsingUserId($userId, $debut = -1, $limite = -1)
    {

        $sql = 'SELECT id, auteur, titre, contenu, dateAjout, dateModif FROM news WHERE auteur = :userId ORDER BY id DESC';

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
            $this->getTag_a($news);

        }

        $requete->closeCursor();

        return $listeNews;

    }

    public function getNewsUsingTag_a($tag, $debut = -1, $limite = -1)
    {
        $sql = 'SELECT id, auteur, titre, contenu, dateAjout, dateModif FROM news INNER JOIN t_tag_tagsd ON ttd_fk_tnc = id WHERE ttd_fk_ttc = :tagId ORDER BY id DESC';

        if ($debut != -1 || $limite != -1) {
            $sql .= ' LIMIT ' . (int)$limite . ' OFFSET ' . (int)$debut;
        }

        $requete = $this->dao->prepare($sql);
        $requete->bindValue(':tagId', $tag->id(), \PDO::PARAM_INT);
        $requete->execute();
        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\News');

        $listeNews_a = $requete->fetchAll();

        foreach ($listeNews_a as $news) {
            $news->setDateAjout(new \DateTime($news->dateAjout()));
            $news->setDateModif(new \DateTime($news->dateModif()));
            $this->getTag_a($news);
        }

        $requete->closeCursor();

        return $listeNews_a;

    }


    private function getTag_a($News)
    {

        $requete = $this->dao->prepare('SELECT TTC_id, TTC_name FROM t_tag_tagsc INNER JOIN t_tag_tagsd ON ttc_id = ttd_fk_ttc WHERE ttd_fk_tnc = :id');
        $requete->bindValue(':id', $News->id(), \PDO::PARAM_INT);
        $requete->execute();

        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Tag');

        $News->setTag($requete->fetchAll());
    }

}