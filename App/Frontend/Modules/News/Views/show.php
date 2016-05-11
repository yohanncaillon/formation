<p>Par <a href="<?= \OCFram\Router::getInstance()->getRouteUrl("user", "Frontend", array("id" => $User->id())) ?>"><em><?= htmlentities($User->name()) ?></em></a>, le <?= $News['dateAjout']->format('d/m/Y à H\hi') ?></p>
<h2 class="break"><?= htmlentities($News['titre']) ?></h2>
<p class="break"><?= htmlentities(nl2br($News['contenu'])) ?></p>

<?php if ($News['dateAjout'] != $News['dateModif']) : ?>
    <p style="text-align: right;">
        <small><em>Modifiée le <?= $News['dateModif']->format('d/m/Y à H\hi') ?></em></small>
    </p>
<?php endif; ?>
<?= $News->tagHtml() ?>
<h2>Ajouter un commentaire</h2>
<form action="" class="formComment">
        <?= $Form ?>

    <input type="submit" value="Commenter"/>
</form>
<?php if (empty($comment_a)) : ?>
<p>Aucun commentaire n'a encore été posté. Soyez le premier à en laisser un !</p>

<?php endif; ?>
<div class="comment-section">
<?php foreach ($comment_a as $Comment) : ?>
<fieldset data-id="<?= $Comment->id() ?>">
    <legend>
        <?php if ($Comment['auteurId'] != \Entity\Comment::AUTEUR_INCONNU) : ?>
        Posté par <a href="<?= \OCFram\Router::getInstance()->getRouteUrl("user", "Frontend", array("id" => $Comment['auteurId'])) ?>"><strong><?= htmlentities($Comment['auteur']) ?></strong></a>
        <?php else: ?>
            Posté par <strong><?= htmlentities($Comment['auteur']) ?></strong>
        <?php endif; ?>
        le <?= $Comment['date']->format('d/m/Y à H\hi') ?>
        <?php if (\OCFram\Session::isAllowed($Comment['auteur'])) : ?> -
            <a href="<?=OCFram\Router::getInstance()->getRouteUrl("commentUpdate", "Backend", array("id" => $Comment['id'])) ?>">Modifier</a> |
            <a href="<?=OCFram\Router::getInstance()->getRouteUrl("commentDelete", "Backend", array("id" => $Comment['id'])) ?>">Supprimer</a>
        <?php endif; ?>
    </legend>
    <p class="break"><?= htmlentities(nl2br($Comment['contenu'])) ?></p>
</fieldset>
<?php endforeach; ?>
</div>
<br>