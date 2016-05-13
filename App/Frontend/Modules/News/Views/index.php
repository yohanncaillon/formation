
<?php foreach ($listeNews_a as $News) : ?>
<div class="news">
    <h2><a class="break" href="<?=OCFram\Router::getInstance()->getRouteUrl("showNews", "Frontend", array("id"=>$News['id'])) ?>"><?= htmlentities($News['titre']) ?></a></h2>
    <p><?=htmlentities(nl2br($News['contenu'])) ?></p>
</div>
<?php endforeach; ?>

<div class="pre-footer">
    <button class="voirPlus">voir plus</button>
</div>
