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
            <td><a href="/news-<?=$news['id'] ?>.html"><?=htmlentities($news['titre']) ?></a></td>
            <td><?=htmlentities($news['auteur']) ?></td>
            <td><?=\Carbon\Carbon::instance($news['dateAjout'])->diffForHumans() ?></td>
            <td><?=($news['dateAjout'] == $news['dateModif'] ? '-' : \Carbon\Carbon::instance($news['dateModif'])->diffForHumans()) ?></td>
            <td>
                <a href="news-update-<?=$news['id'] ?>.html"><img src="/images/update.png" alt="Modifier" /></a>
                <a href="news-delete-<?=$news['id'] ?>.html"><img src="/images/delete.png" alt="Supprimer" /></a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>