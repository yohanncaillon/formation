<!DOCTYPE html>
<html>
  <head>
    <title>
      <?= isset($title) ? $title : 'Mon super site' ?>
    </title>
    
    <meta charset="utf-8" />
    
    <link rel="stylesheet" href="/css/bootstrap.min.css" type="text/css" />
    <link rel="stylesheet" href="/css/style.css" type="text/css" />
  </head>
    <body>
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="<?=OCFram\Router::getInstance()->getRouteUrl("index","Frontend") ?>">Mon super site</a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="<?=OCFram\Router::getInstance()->getRouteUrl("index","Frontend") ?>">Accueil</a></li>
                        <?php if ($session->isAuthenticated()) : ?>
                            <li><a href="<?=OCFram\Router::getInstance()->getRouteUrl("indexAdmin","Backend") ?>">Admin</a></li>
                            <li><a href="<?=OCFram\Router::getInstance()->getRouteUrl("addNews", "Backend") ?>">Ajouter une news</a></li>
                            <li><a href="<?=OCFram\Router::getInstance()->getRouteUrl("addAdmin", "Backend") ?>">Ajouter un admin</a></li>
                            <li><a href="<?=OCFram\Router::getInstance()->getRouteUrl("logout", "Backend") ?>">Se déconnecter</a></li>
                        <?php endif; ?>
                    </ul>
                </div><!--/.nav-collapse -->
            </div>
        </nav>
        <div class="container">
            <div id="content-wrap">
                <section id="main">
                    <?php if ($session->hasFlash()) : ?>
                        <div class="alert alert-info" role="alert"><?=$session->getFlash() ?></div>
                    <?php endif; ?>
                    <?= $content ?>
                </section>
            </div>
        </div>
    </body>
</html>