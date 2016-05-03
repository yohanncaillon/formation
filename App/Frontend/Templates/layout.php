<!DOCTYPE html>
<html>
<head>
    <title>
        <?= isset($title) ? $title : 'Mon super site' ?>
    </title>

    <meta charset="utf-8"/>

    <link rel="stylesheet" href="/projet_formation/web/css/Envision.css" type="text/css"/>
    <link rel="stylesheet" href="/projet_formation/web/css/style.css" type="text/css" />
</head>

<body>
<div id="wrap">
    <header>
        <h1><a href="/projet_formation/">Les chats c tro lol</a></h1>
        <p><?= \Carbon\Carbon::now()->formatLocalized('%A %d %B %Y') ?></p>
    </header>

    <nav>
        <ul>
            <li><a href="/projet_formation/">Accueil</a></li>
            <?php if ($session->isAuthenticated()) { ?>
                <li><a href="/projet_formation/admin/">Admin</a></li>
                <li><a href="/projet_formation/admin/news-insert.html">Ajouter une news</a></li>
                <li><a href="/projet_formation/admin/logout">Se déconnecter</a></li>
            <?php } else { ?>
                <li><a href="/projet_formation/admin/login">Connexion</a></li>
            <?php } ?>
        </ul>
    </nav>

    <div id="content-wrap">
        <section id="main">
            <?php if ($session->hasFlash()) echo '<p style="text-align: center;">', $session->getFlash(), '</p>'; ?>
            <?= $content ?>
        </section>
    </div>

    <footer></footer>
</div>
</body>
</html>