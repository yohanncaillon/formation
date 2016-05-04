<!DOCTYPE html>
<html>
<head>
    <title>
        <?= isset($title) ? $title : 'Mon super site' ?>
    </title>

    <meta charset="utf-8"/>

    <link rel="stylesheet" href="/css/Envision.css" type="text/css"/>
    <link rel="stylesheet" href="/css/style.css" type="text/css" />
</head>

<body>
<div id="wrap">
    <header>
        <h1><a href="/">Les chats c tro lol</a></h1>
        <p><?= \Carbon\Carbon::now()->formatLocalized('%A %d %B %Y') ?> </p>
    </header>

    <nav>
        <ul>
            <li><a href="/">Accueil</a></li>
            <?php if ($session->isAuthenticated()) : ?>
                <li><a href="/admin/">Admin</a></li>
                <li><a href="/admin/news-insert.html">Ajouter une news</a></li>
                <li><a href="/admin/register">Ajouter un admin</a></li>
                <li><a href="/admin/logout">Se déconnecter</a></li>
            <?php else : ?>
                <li><a href="/admin/login">Connexion</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <div id="content-wrap">
        <section id="main">
            <?php if ($session->hasFlash()) : ?>
                <div class="alert alert-info" role="alert"><?=$session->getFlash() ?></div>
            <?php endif; ?>
            <?= $content ?>
        </section>
    </div>
    <footer></footer>
</div>
</body>
</html>