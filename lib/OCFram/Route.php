<?php
namespace OCFram;

class Route
{
    protected $action;
    protected $module;
    protected $url;
    protected $varsName_a;
    protected $var_a = [];
    protected $name;

    public function __construct($name, $url, $module, $action, array $varsNames)
    {
        $this->setUrl($url);
        $this->setModule($module);
        $this->setAction($action);
        $this->setVarsNames($varsNames);
        $this->setName($name);
    }

    public function hasVars()
    {
        return !empty($this->varsName_a);
    }

    public function match($url)
    {

        $builtUrl = $this->url;
        foreach ($this->varsName_a as $var) {

            $pattern = '/\{' . $var . '\}/';
            $builtUrl = preg_replace($pattern, '([0-9a-zA-Z]+)', $builtUrl);

        }

        if (preg_match('`^' . $builtUrl . '$`', $url, $matches)) {
            return $matches;
        } else {
            return false;
        }
    }

    public function setAction($action)
    {
        if (is_string($action)) {
            $this->action = $action;
        }
    }

    public function setModule($module)
    {
        if (is_string($module)) {
            $this->module = $module;
        }
    }

    public function setUrl($url)
    {
        if (is_string($url)) {
            $this->url = $url;
        }
    }

    public function setVarsNames(array $varsName_a)
    {
        $this->varsName_a = $varsName_a;
    }

    public function setVars(array $var_a)
    {
        $this->var_a = $var_a;
    }

    public function action()
    {
        return $this->action;
    }

    public function module()
    {
        return $this->module;
    }

    public function vars()
    {
        return $this->var_a;
    }

    public function varsNames()
    {
        return $this->varsName_a;
    }


    public function url($params)
    {
        if ($params == null)
            return $this->url;

        $builtUrl = $this->url;
        foreach ($params as $param => $value) {

            $pattern = '/\{' . $param . '\}/';
            $builtUrl = preg_replace($pattern, $value, $builtUrl);

        }
        return $builtUrl;

    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function name()
    {
        return $this->name;
    }

}