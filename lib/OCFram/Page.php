<?php
namespace OCFram;

class Page extends ApplicationComponent
{
    const AJAX_PAGE = 'ajax';
    const DEFAULT_PAGE = 'default';
    protected $contentFile;
    protected $var_a = [];
    protected $type;

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }


    public function addVar($var, $value)
    {
        if (!is_string($var) || is_numeric($var) || empty($var)) {

            throw new \InvalidArgumentException('Le nom de la variable doit être une chaine de caractères non nulle');
        }

        $this->var_a[$var] = $value;
    }

    public function getGeneratedPage()
    {

        if (!file_exists($this->contentFile)) {

            throw new \RuntimeException('La vue spécifiée n\'existe pas');

        }

        $session = $this->App()->Session();
        extract($this->var_a);


        switch ($this->type) {

            case Page::AJAX_PAGE:

                $content = require $this->contentFile;
                $json_content = require __DIR__ . '/../../App/' . $this->App->name() . '/Templates/ajaxTemplate.php';
                header('Content-Type: application/json');
                return json_encode($json_content);

            default:

                ob_start();
                require $this->contentFile;
                $content = ob_get_clean();

                ob_start();
                require __DIR__ . '/../../App/' . $this->App->name() . '/Templates/layout.php';
                return ob_get_clean();
        }


    }

    public function setContentFile($contentFile)
    {
        if (!is_string($contentFile) || empty($contentFile)) {
            throw new \InvalidArgumentException('La vue spécifiée est invalide');
        }

        $this->contentFile = $contentFile;
    }
}