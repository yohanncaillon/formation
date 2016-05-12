<h1>Les dernières actions de <?= $User->name() ?></h1>
<hr>
<?php foreach ($listeAction_a as $Action) : ?>

    <?php if (isset($Action['action']) && $Action['action'] == \Entity\News::ADDED) : ?>
    <h2><a class="break" href="<?=OCFram\Router::getInstance()->getRouteUrl("showNews", "Frontend", array("id"=>$Action['id'])) ?>"><i><?= $User->name() ?></i> a posté la news : <?= $Action["newsName"] ?></a></h2>
        <p><?=$Action['content'] ?></p>
    <?php elseif (isset($Action['action']) && $Action['action'] == \Entity\News::MODIFIED) : ?>
    <h2><a class="break" href="<?=OCFram\Router::getInstance()->getRouteUrl("showNews", "Frontend", array("id"=>$Action['id'])) ?>"><i><?= $User->name() ?></i> a modifié la news <?= $Action['newsName'] ?></a></h2>

        <p><?=$Action['content'] ?></p>
    <?php else : ?>
    <h2><a class="break" href="<?=OCFram\Router::getInstance()->getRouteUrl("showNews", "Frontend", array("id"=>$Action['id'])) ?>"><i><?= $User->name() ?></i> a commenté la news <?= $Action['newsName'] ?></a></h2>

        <p><?=$Action['content'] ?></p>
    <?php endif; ?>
    
<?php endforeach; ?>