<h1>Les news de <?= $User->name() ?></h1>
<hr>
<?php foreach ($listeNews_a as $News) : ?>
    <h2><a class="break" href="<?=OCFram\Router::getInstance()->getRouteUrl("showNews", "Frontend", array("id"=>$News['id'])) ?>"><?= htmlentities($News['titre']) ?></a></h2>
    <p><?=htmlentities(nl2br($News['contenu'])) ?></p>
<?php endforeach; ?>