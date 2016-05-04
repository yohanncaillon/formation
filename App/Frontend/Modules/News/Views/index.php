<?php foreach ($listeNews as $news) : ?>
    <h2><a class="break" href="/news-<?= $news['id'] ?>.html"><?= $news['titre'] ?></a></h2>
    <p><?= nl2br($news['contenu']) ?></p>
<?php endforeach; ?>

<?php if($pageNumber > 0): ?>
<a href="/page-<?= $pageNumber-1 ?>/">< Page précédente</a>
<?php endif; ?>

<?php if ($next) : ?>
<a style="float: right" href="/page-<?= $pageNumber+1 ?>/">Page suivante ></a>
<?php endif; ?>