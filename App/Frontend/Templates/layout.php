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
            <h1><a href="<?=OCFram\Router::getInstance()->getRouteUrl("index", "Frontend") ?>">Les chats c tro lol</a></h1>
            <p><?= \Carbon\Carbon::now()->formatLocalized('%A %d %B %Y') ?> </p>
        </header>
        <nav>
            <ul>
                <li><a href="<?=OCFram\Router::getInstance()->getRouteUrl("index","Frontend") ?>">Accueil</a></li>
                <?php if ($session->isAuthenticated()) : ?>
                    <li><a href="<?=OCFram\Router::getInstance()->getRouteUrl("indexAdmin","Backend") ?>">Admin</a></li>
                    <li><a href="<?=OCFram\Router::getInstance()->getRouteUrl("addNews", "Backend") ?>">Ajouter une news</a></li>
                    <li><a href="<?=OCFram\Router::getInstance()->getRouteUrl("addAdmin", "Backend") ?>">Ajouter un admin</a></li>
                    <li><a href="<?=OCFram\Router::getInstance()->getRouteUrl("logout", "Backend") ?>">Se déconnecter</a></li>
                <?php else : ?>
                    <li><a href="<?=OCFram\Router::getInstance()->getRouteUrl("connexion", "Backend") ?>">Connexion</a></li>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
<script src="/js/script.js"></script>
</html>