
<h1><i class="glyphicon glyphicon-tasks"></i> Espace Administration</h1>
<hr>
<p style="text-align: center">Il y a actuellement <b><?= $nombreNews ?> news.</b> En voici la liste :</p>
 
<table class="table table-striped">
  <tr><th>Auteur</th><th>Titre</th><th>Date d'ajout</th><th>Dernière modification</th><th>Action</th></tr>
<?php
foreach ($listeNews as $news)
{
  echo '<tr><td>', $news['auteur'], '</td><td>', $news['titre'], '</td><td>le ', $news['dateAjout']->format('d/m/Y à H\hi'), '</td><td>', ($news['dateAjout'] == $news['dateModif'] ? '-' : 'le '.$news['dateModif']->format('d/m/Y à H\hi')), '</td><td><a href="news-update-', $news['id'], '.html"><img src="/projet_formation/web/images/update.png" alt="Modifier" /></a> <a href="news-delete-', $news['id'], '.html"><img src="/projet_formation/web/images/delete.png" alt="Supprimer" /></a></td></tr>', "\n";
}
?>
</table>