<?php
namespace OCFram;

class Router
{
    protected $routes = [];
    protected  static $router = null;
    const NO_ROUTE = 1;

    private function __construct () {}
    private function __clone () {}

    public function addRoute(Route $route)
    {
        if (!in_array($route, $this->routes)) {
            $this->routes[$route->name()] = $route;
        }
    }

    public function getRoute($url)
    {
        foreach ($this->routes as $route) {
            // Si la route correspond à l'URL
            if (($varsValues = $route->match($url)) !== false) {
                // Si elle a des variables
                if ($route->hasVars()) {
                    $varsNames = $route->varsNames();
                    $listVars = [];

                    // On crée un nouveau tableau clé/valeur
                    // (clé = nom de la variable, valeur = sa valeur)
                    foreach ($varsValues as $key => $match) {
                        // La première valeur contient entièrement la chaine capturée (voir la doc sur preg_match)
                        if ($key !== 0) {
                            $listVars[$varsNames[$key - 1]] = $match;
                        }
                    }

                    // On assigne ce tableau de variables � la route
                    $route->setVars($listVars);
                }
                return $route;
            }
        }

        throw new \RuntimeException('Aucune route ne correspond à l\'URL', self::NO_ROUTE);
    }

    public function getRouteUrl($name, $appName, array $params = null)
    {
        if(isset($this->routes[$name])){

            return $this->routes[$name]->url($params);

        } else {

            $xml = new \DOMDocument;
            $xml->load(__DIR__ . '/../../App/' . $appName . '/Config/routes.xml');

            $routesXml = $xml->getElementsByTagName('route');
            $laRoute = null;
            // On parcourt les routes du fichier XML.
            foreach ($routesXml as $route) {

                $vars = [];
                // On regarde si des variables sont présentes dans l'URL.
                if ($route->hasAttribute('vars')) {
                    $vars = explode(',', $route->getAttribute('vars'));
                }

                $uneRoute = new Route($route->getAttribute('name'), $route->getAttribute('url'), $route->getAttribute('module'), $route->getAttribute('action'), $vars);
                $this->addRoute($uneRoute); // On ajoute les routes du fichier

                if($uneRoute->name() == $name)
                    $laRoute = $uneRoute;
            }
            if($laRoute !=null)
                return $laRoute->url($params);

        }
        return "notFound";

    }

    public static function getInstance () {
        if (!(self::$router instanceof self))
            self::$router = new self();

        return self::$router;
    }

}