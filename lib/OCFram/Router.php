<?php
namespace OCFram;

class Router
{
    protected $route_a = [];
    protected static $router = null;
    const NO_ROUTE = 1;

    private function __construct(){}

    private function __clone(){}

    public function addRoute(Route $route)
    {
        if (!in_array($route, $this->route_a)) {
            $this->route_a[$route->name()] = $route;
        }
    }

    public function getRoute($url)
    {

        foreach ($this->route_a as $route) {
            // Si la route correspond à l'URL
            if (($varsValue_a = $route->match($url)) !== false) {
                // Si elle a des variables
                if ($route->hasVars()) {
                    $varsName_a = $route->varsNames();
                    $listVar_a = [];

                    // On crée un nouveau tableau clé/valeur
                    // (clé = nom de la variable, valeur = sa valeur)
                    foreach ($varsValue_a as $key => $match) {
                        // La première valeur contient entièrement la chaine capturée (voir la doc sur preg_match)
                        if ($key !== 0) {
                            $listVar_a[$varsName_a[$key - 1]] = $match;
                        }
                    }

                    // On assigne ce tableau de variables � la route
                    $route->setVars($listVar_a);
                }
                return $route;
            }
        }

        throw new \RuntimeException('Aucune route ne correspond à l\'URL', self::NO_ROUTE);
    }

    public function getRouteUrl($name, $appName, array $params = null)
    {
        if (isset($this->route_a[$name])) {

            return $this->route_a[$name]->url($params);

        } else {

            $xml = new \DOMDocument;
            $xml->load(__DIR__ . '/../../App/' . $appName . '/Config/routes.xml');

            $routeXml_a = $xml->getElementsByTagName('route');
            $laRoute = null;
            // On parcourt les routes du fichier XML.
            foreach ($routeXml_a as $route) {

                $var_a = [];
                $pattern_a = [];

                // On regarde si des variables sont présentes dans l'URL.
                if ($route->hasAttribute('vars')) {
                    $var_a = explode(',', $route->getAttribute('vars'));
                }

                if ($route->hasAttribute('patterns')) {
                    $pattern_a = explode(',', $route->getAttribute('patterns'));
                }

                $Route = new Route($route->getAttribute('name'), $route->getAttribute('url'), $route->getAttribute('module'), $route->getAttribute('action'), $var_a, $pattern_a);
                $this->addRoute($Route); // On ajoute les routes du fichier

                if ($Route->name() == $name)
                    $laRoute = $Route;
            }
            if ($laRoute != null)
                return $laRoute->url($params);

        }
        return "/error-404";

    }

    public static function getInstance()
    {
        if (!(self::$router instanceof self))
            self::$router = new self();

        return self::$router;
    }

}