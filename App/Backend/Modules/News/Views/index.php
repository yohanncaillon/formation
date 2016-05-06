<h1>Espace Administration</h1>
<hr>
<p style="text-align: center">Il y a actuellement <b><?= $nombreNews ?> news.</b> En voici la liste :</p>

<table class="table table-bordered">
    <thead>
        <th>#</th>
        <th>Titre</th>
        <th>Auteur</th>
        <th>Date d'ajout</th>
        <th>Dernière modification</th>
        <th>Action</th>
    </thead>
    <tbody>
    <?php
    $index = 1;
    foreach ($listeNews as $news) :
    ?>
        <tr>
            <td><?=$index++ ?></td>
            <td><a href="<?=\OCFram\Router::getInstance()->getRouteUrl("showNews", "Frontend", array("id" => $news['id'])) ?>"><?=htmlentities($news['titre']) ?></a></td>
            <td><?=htmlentities($news['auteur']) ?></td>
            <td><?=\Carbon\Carbon::instance($news['dateAjout'])->diffForHumans() ?></td>
            <td><?=($news['dateAjout'] == $news['dateModif'] ? '-' : \Carbon\Carbon::instance($news['dateModif'])->diffForHumans()) ?></td>
            <td>
                <a href="<?=\OCFram\Router::getInstance()->getRouteUrl("newsUpdate", "Backend", array("id" => $news['id'])) ?>"><img src="/images/update.png" alt="Modifier" /></a>
                <a href="<?=\OCFram\Router::getInstance()->getRouteUrl("newsDelete", "Backend", array("id" => $news['id'])) ?>"><img src="/images/delete.png" alt="Supprimer" /></a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>