<?php
namespace OCFram;

abstract class Application
{
    protected $HttpRequest;
    protected $HttpResponse;
    protected $name;
    protected $Session;
    protected $Config;
    protected $Router;

    public function __construct($appName)
    {

        $this->HttpRequest = new HTTPRequest($this);
        $this->HttpResponse = new HTTPResponse($this);
        $this->Session = new Session();
        $this->Config = new Config($this);
        $this->name = $appName;
        $this->Router = Router::getInstance();

        $xml = new \DOMDocument;
        $xml->load(__DIR__ . '/../../App/' . $this->name . '/Config/routes.xml');

        $routes_a = $xml->getElementsByTagName('route');

        // On parcourt les routes du fichier XML.
        foreach ($routes_a as $Route) {

            $var_a = [];
            $pattern_a = [];
            // On regarde si des variables sont présentes dans l'URL.
            if ($Route->hasAttribute('vars')) {
                $var_a = explode(',', $Route->getAttribute('vars'));
            }

            if ($Route->hasAttribute('patterns')) {
                $pattern_a = explode(',', $Route->getAttribute('patterns'));
            }

            // On ajoute la route au routeur.
            $this->Router->addRoute(new Route($Route->getAttribute('name'), $Route->getAttribute('url'), $Route->getAttribute('module'), $Route->getAttribute('action'), $var_a, $pattern_a));
        }
    }


    public function getController()
    {

        try {

            // On récupère la route correspondante à l'URL.
            $matchedRoute = $this->Router->getRoute($this->HttpRequest->requestURI());

        } catch (\RuntimeException $e) {

            if ($e->getCode() == Router::NO_ROUTE) {
                // Si aucune route ne correspond, c'est que la page demandée n'existe pas.
                $this->HttpResponse->redirect404();
            }
        }

        // On ajoute les variables de l'URL au tableau $_GET.
        $_GET = array_merge($_GET, $matchedRoute->vars());

        // On instancie le contrôleur.
        $controllerClass = 'App\\' . $this->name . '\\Modules\\' . $matchedRoute->module() . '\\' . $matchedRoute->module() . 'Controller';
        return new $controllerClass($this, $matchedRoute->module(), $matchedRoute->action());
    }

    abstract public function run();

    public function HttpRequest()
    {
        return $this->HttpRequest;
    }

    public function HttpResponse()
    {
        return $this->HttpResponse;
    }

    public function name()
    {
        return $this->name;
    }

    public function Config()
    {
        return $this->Config;
    }

    public function Session()
    {
        return $this->Session;
    }
}