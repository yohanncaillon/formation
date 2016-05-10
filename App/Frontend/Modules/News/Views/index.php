<?php foreach ($listeNews_a as $News) : ?>
    <h2><a class="break" href="<?=OCFram\Router::getInstance()->getRouteUrl("showNews", "Frontend", array("id"=>$News['id'])) ?>"><?= htmlentities($News['titre']) ?></a></h2>
    <p><?=htmlentities(nl2br($News['contenu'])) ?></p>
<?php endforeach; ?>

<?php if($pageNumber > 0): ?>
<a href="<?=OCFram\Router::getInstance()->getRouteUrl("page", "Frontend", array("page" => $pageNumber-1)) ?>">< Page précédente</a>
<?php endif; ?>

<?php if ($next) : ?>
<a style="float: right" href="<?=OCFram\Router::getInstance()->getRouteUrl("page", "Frontend", array("page" => $pageNumber+1)) ?>">Page suivante ></a>
<?php endif; ?>