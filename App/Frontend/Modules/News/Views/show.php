<p>Par <a href="<?= \OCFram\Router::getInstance()->getRouteUrl("user", "Frontend", array("id" => $user->id())) ?>"><em><?= htmlentities($user->name()) ?></em></a>, le <?= $news['dateAjout']->format('d/m/Y à H\hi') ?></p>
<h2 class="break"><?= htmlentities($news['titre']) ?></h2>
<p class="break"><?= htmlentities(nl2br($news['contenu'])) ?></p>

<?php if ($news['dateAjout'] != $news['dateModif']) : ?>
    <p style="text-align: right;">
        <small><em>Modifiée le <?= $news['dateModif']->format('d/m/Y à H\hi') ?></em></small>
    </p>
<?php endif; ?>
<h2>Ajouter un commentaire</h2>
<form action="" class="formComment">
        <?= $form ?>

    <input type="submit" value="Commenter"/>
</form>
<?php if (empty($comments_a)) : ?>
<p>Aucun commentaire n'a encore été posté. Soyez le premier à en laisser un !</p>

<?php endif; ?>
<div class="comment-section">
<?php foreach ($comment_a as $comment) : ?>
<fieldset data-id="<?= $comment->id() ?>">
    <legend>
        <?php if ($comment['auteurId'] != \Entity\Comment::AUTEUR_INCONNU) : ?>
        Posté par <a href="<?= \OCFram\Router::getInstance()->getRouteUrl("user", "Frontend", array("id" => $comment['auteurId'])) ?>"><strong><?= htmlentities($comment['auteur']) ?></strong></a>
        <?php else: ?>
            Posté par <strong><?= htmlentities($comment['auteur']) ?></strong>
        <?php endif; ?>
        le <?= $comment['date']->format('d/m/Y à H\hi') ?>
        <?php if (\OCFram\Session::isAllowed($comment['auteur'])) : ?> -
            <a href="<?=OCFram\Router::getInstance()->getRouteUrl("commentUpdate", "Backend", array("id" => $comment['id'])) ?>">Modifier</a> |
            <a href="<?=OCFram\Router::getInstance()->getRouteUrl("commentDelete", "Backend", array("id" => $comment['id'])) ?>">Supprimer</a>
        <?php endif; ?>
    </legend>
    <p class="break"><?= htmlentities(nl2br($comment['contenu'])) ?></p>
</fieldset>
<?php endforeach; ?>
</div>
<br>