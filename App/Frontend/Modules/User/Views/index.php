<h1>Les dernières actions de <?= $User->name() ?></h1>
<hr>
<form class="checkform">
    <p>Filtrer :</p>
    <label>News :</label>
    <input type="checkbox" name="news" id="check-news" checked> |
    <label>Comments :</label>
    <input type="checkbox" name="comment" id="check-comment" checked>
</form>
<?php foreach ($listeAction_a as $Action) : ?>
<div class="action type-<?=$Action['type'] ?>">
    <span class="float-right"><?= \Carbon\Carbon::instance($Action['date'])->diffForHumans() ?></span>
    <?php if (isset($Action['action']) && $Action['action'] == \Entity\News::ADDED) : ?>
    <h2><a class="break" href="<?=OCFram\Router::getInstance()->getRouteUrl("showNews", "Frontend", array("id"=>$Action['id'])) ?>"><i><?= $User->name() ?></i> a posté la news : <?= $Action["newsName"] ?></a></h2>
    <p><?=$Action['content'] ?></p>

    <?php elseif (isset($Action['action']) && $Action['action'] == \Entity\News::MODIFIED) : ?>
    <h2><a class="break" href="<?=OCFram\Router::getInstance()->getRouteUrl("showNews", "Frontend", array("id"=>$Action['id'])) ?>"><i><?= $User->name() ?></i> a modifié la news : <?= $Action['newsName'] ?></a></h2>
    <p><?=$Action['content'] ?></p>

    <?php else : ?>
    <h2><a class="break" href="<?=OCFram\Router::getInstance()->getRouteUrl("showNews", "Frontend", array("id"=>$Action['id'])) ?>"><i><?= $User->name() ?></i> a commenté la news : <?= $Action['newsName'] ?></a></h2>
    <p><?=$Action['content'] ?></p>
    <?php endif; ?>
</div>
<?php endforeach; ?>

