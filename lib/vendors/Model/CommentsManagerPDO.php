<?php
namespace Model;

use \Entity\Comment;

class CommentsManagerPDO extends CommentsManager
{
    protected function insertComment(Comment $comment)
    {
        $q = $this->dao->prepare('INSERT INTO comments SET news = :news, auteur = :auteur, contenu = :contenu, auteurId = :auteurId, date = NOW()');

        $q->bindValue(':news', $comment->news(), \PDO::PARAM_INT);
        $q->bindValue(':auteur', $comment->auteur());
        $q->bindValue(':contenu', $comment->contenu());
        $q->bindValue(':auteurId', $comment->auteurId());

        $q->execute();

        $comment->setId($this->dao->lastInsertId());

    }

    public function deleteCommentUsingId($id)
    {
        $this->dao->exec('DELETE FROM comments WHERE id = ' . (int)$id);
    }

    public function deleteCommentUsingNewsId($news)
    {
        $this->dao->exec('DELETE FROM comments WHERE news = ' . (int)$news);
    }

    public function getCommentUsingNewsId_a($news, $offsetId = 0)
    {
        if ($offsetId == null)
            $offsetId = 0;

        if (!ctype_digit($news)) {
            throw new \InvalidArgumentException('L\'identifiant de la news passé doit être un nombre entier valide');
        }

        $q = $this->dao->prepare('SELECT id, news, auteur, contenu, auteurId, date FROM comments WHERE news = :news AND id > :offsetId ORDER BY date DESC');
        $q->bindValue(':news', $news, \PDO::PARAM_INT);
        $q->bindValue(':offsetId', $offsetId, \PDO::PARAM_INT);
        $q->execute();

        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Comment');

        $comment_a = $q->fetchAll();

        foreach ($comment_a as $Comment) {
            $Comment->setDate(new \DateTime($Comment->date()));
        }

        return $comment_a;
    }

    protected function UpdateComment(Comment $comment)
    {
        $q = $this->dao->prepare('UPDATE comments SET auteur = :auteur, contenu = :contenu WHERE id = :id');

        $q->bindValue(':auteur', $comment->auteur());
        $q->bindValue(':contenu', $comment->contenu());
        $q->bindValue(':id', $comment->id(), \PDO::PARAM_INT);

        $q->execute();
    }

    public function getCommentUsingId($id)
    {
        $q = $this->dao->prepare('SELECT id, news, auteur, auteurId, contenu FROM comments WHERE id = :id');
        $q->bindValue(':id', (int)$id, \PDO::PARAM_INT);
        $q->execute();

        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Comment');

        return $q->fetch();
    }
}