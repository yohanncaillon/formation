<p>Par <em><?= htmlentities($news['auteur']) ?></em>, le <?= $news['dateAjout']->format('d/m/Y à H\hi') ?></p>
<h2 class="break"><?= htmlentities($news['titre']) ?></h2>
<p class="break"><?= htmlentities(nl2br($news['contenu'])) ?></p>

<?php if ($news['dateAjout'] != $news['dateModif']) : ?>
    <p style="text-align: right;">
        <small><em>Modifiée le <?= $news['dateModif']->format('d/m/Y à H\hi') ?></em></small>
    </p>
<?php endif; ?>

<p><a href="<?=OCFram\Router::getInstance()->getRouteUrl("commenter", "Frontend", array("news" => $news['id'])) ?>">Ajouter un commentaire</a></p>

<?php if (empty($comments)) : ?>
<p>Aucun commentaire n'a encore été posté. Soyez le premier à en laisser un !</p>
<?php endif;

foreach ($comments as $comment) : ?>
<fieldset>
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

<p><a href="<?=OCFram\Router::getInstance()->getRouteUrl("commenter", "Frontend", array("news" => $news['id'])) ?>">Ajouter un commentaire</a></p>