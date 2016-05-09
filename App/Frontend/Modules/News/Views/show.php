<p>Par <em><?= htmlentities($news['auteur']) ?></em>, le <?= $news['dateAjout']->format('d/m/Y à H\hi') ?></p>
<h2 class="break"><?= htmlentities($news['titre']) ?></h2>
<p class="break"><?= htmlentities(nl2br($news['contenu'])) ?></p>

<?php if ($news['dateAjout'] != $news['dateModif']) : ?>
    <p style="text-align: right;">
        <small><em>Modifiée le <?= $news['dateModif']->format('d/m/Y à H\hi') ?></em></small>
    </p>
<?php endif; ?>
<h2>Ajouter un commentaire</h2>
<form action="" class="formComment">
    <p>
        <?= $form ?>

        <input type="submit" value="Commenter"/>
    </p>
</form>
<?php if (empty($comments)) : ?>
<p>Aucun commentaire n'a encore été posté. Soyez le premier à en laisser un !</p>

<?php endif; ?>
<div class="comment-section">
<?php foreach ($comments as $comment) : ?>
<fieldset data-id="<?= $comment->id() ?>">
    <legend>
        Posté par <strong><?= htmlentities($comment['auteur']) ?></strong>
        le <?= $comment['date']->format('d/m/Y à H\hi') ?>
        <?php if ($session->isAuthenticated()) : ?> -
            <a href="<?=OCFram\Router::getInstance()->getRouteUrl("commentUpdate", "Backend", array("id" => $comment['id'])) ?>">Modifier</a> |
            <a href="<?=OCFram\Router::getInstance()->getRouteUrl("commentDelete", "Backend", array("id" => $comment['id'])) ?>">Supprimer</a>
        <?php endif; ?>
    </legend>
    <p class="break"><?= htmlentities(nl2br($comment['contenu'])) ?></p>
</fieldset>
<?php endforeach; ?>
</div>
<br>