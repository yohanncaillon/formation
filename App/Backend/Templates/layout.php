<!DOCTYPE html>
<html>
  <head>
    <title>
      <?= isset($title) ? $title : 'Mon super site' ?>
    </title>
    
    <meta charset="utf-8" />
    
    <link rel="stylesheet" href="../../vendor/twbs/bootstrap/dist/css/bootstrap.min.css" type="text/css" />
    <link rel="stylesheet" href="../css/style.css" type="text/css" />
  </head>
  <!--
  <body>
    <div id="wrap">
      <header>
        <h1><a href="/projet_formation/web/">Mon super site</a></h1>
        <p>Comment ça, il n'y a presque rien ?</p>
      </header>
      
      <nav>
        <ul>
          <li><a href="/projet_formation/web/">Accueil</a></li>
          <?php if ($user->isAuthenticated()) { ?>
          <li><a href="/projet_formation/web/admin/">Admin</a></li>
          <li><a href="/projet_formation/web/admin/news-insert.html">Ajouter une news</a></li>
          <?php } ?>
        </ul>
      </nav>
      

    
      <footer></footer>
    </div>
  </body> -->
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
                    <a class="navbar-brand" href="/projet_formation/web/">Mon super site</a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="/projet_formation/web/">Accueil</a></li>
                        <?php if ($user->isAuthenticated()) { ?>
                            <li class="active"><a href="/projet_formation/web/admin/">Admin</a></li>
                            <li><a href="/projet_formation/web/admin/news-insert.html">Ajouter une news</a></li>
                            <li><a href="/projet_formation/web/admin/logout">Se déconnecter</a></li>
                        <?php } ?>
                    </ul>
                </div><!--/.nav-collapse -->
            </div>
        </nav>
        <div class="container">
            <div id="content-wrap">
                <section id="main">
                    <?php if ($user->hasFlash()) echo '<p style="text-align: center;">', $user->getFlash(), '</p>'; ?>

                    <?= $content ?>
                </section>
            </div>
        </div>
    </body>
</html>