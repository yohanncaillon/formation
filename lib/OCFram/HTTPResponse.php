<?php
namespace OCFram;

class HTTPResponse extends ApplicationComponent
{
    protected $Page;
    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    public function addHeader($header)
    {
        header($header);
    }

    public function redirect($location)
    {

        header('Location: ' . $location);
        exit;
    }

    public function redirect404()
    {
        $this->Page = new Page($this->App);
        $this->Page->setContentFile(__DIR__ . '/../../Errors/404.php');
        $this->addHeader('HTTP/1.0 404 Not Found');

        $this->send();
    }

    public function send()
    {

        exit($this->Page->getGeneratedPage());

    }

    public function setPage(Page $Page)
    {
        $this->Page = $Page;
    }

    // Changement par rapport à la fonction setcookie() : le dernier argument est par défaut à true
    public function setCookie($name, $value = '', $expire = 0, $path = null, $domain = null, $secure = false, $httpOnly = true)
    {
        setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
    }
}