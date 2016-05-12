<h1>CLOUD TAG</h1>
<hr>

<div id="cloud-block">
<?php foreach ($listeTag_a as $Tag) : ?>
    <span class="cloud" data-poids="<?= $Tag->count ?>"><?= $Tag->tagHtml() ?></span>
<?php endforeach; ?>
</div>
   