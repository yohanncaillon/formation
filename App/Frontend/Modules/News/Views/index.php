<?php
foreach ($listeNews as $news) {
    ?>
    <h2><a class="break" href="news-<?= $news['id'] ?>.html"><?= $news['titre'] ?></a></h2>
    <p><?= nl2br($news['contenu']) ?></p>
    <?php
}