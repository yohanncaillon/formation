<?php foreach ($listeNews as $news) : ?>
    <h2><a class="break" href="<?=OCFram\Router::getInstance()->getRouteUrl("showNews", "Frontend", array("id"=>$news['id'])) ?>"><?= htmlentities($news['titre']) ?></a></h2>
    <p><?=htmlentities(nl2br($news['contenu'])) ?></p>
<?php endforeach; ?>

<?php if($pageNumber > 0): ?>
<a href="<?=OCFram\Router::getInstance()->getRouteUrl("page", "Frontend", array("page" => $pageNumber-1)) ?>">< Page précédente</a>
<?php endif; ?>

<?php if ($next) : ?>
<a style="float: right" href="<?=OCFram\Router::getInstance()->getRouteUrl("page", "Frontend", array("page" => $pageNumber+1)) ?>">Page suivante ></a>
<?php endif; ?>