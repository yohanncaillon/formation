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
    foreach ($listeNews_a as $News) :
    ?>
        <tr>
            <td><?=$index++ ?></td>
            <td><a href="<?=\OCFram\Router::getInstance()->getRouteUrl("showNews", "Frontend", array("id" => $News['id'])) ?>"><?=htmlentities($News['titre']) ?></a></td>
            <td><?=htmlentities($News['auteur']) ?></td>
            <td><?=\Carbon\Carbon::instance($News['dateAjout'])->diffForHumans() ?></td>
            <td><?=($News['dateAjout'] == $News['dateModif'] ? '-' : \Carbon\Carbon::instance($News['dateModif'])->diffForHumans()) ?></td>
            <td>
                <a href="<?=\OCFram\Router::getInstance()->getRouteUrl("newsUpdate", "Backend", array("id" => $News['id'])) ?>"><img src="/images/update.png" alt="Modifier" /></a>
                <a href="<?=\OCFram\Router::getInstance()->getRouteUrl("newsDelete", "Backend", array("id" => $News['id'])) ?>"><img src="/images/delete.png" alt="Supprimer" /></a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>