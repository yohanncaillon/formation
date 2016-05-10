<h1>Les news de <?= $user->name() ?></h1>
<hr>
<?php foreach ($listeNews as $news) : ?>
    <h2><a class="break" href="<?=OCFram\Router::getInstance()->getRouteUrl("showNews", "Frontend", array("id"=>$news['id'])) ?>"><?= htmlentities($news['titre']) ?></a></h2>
    <p><?=htmlentities(nl2br($news['contenu'])) ?></p>
<?php endforeach; ?>