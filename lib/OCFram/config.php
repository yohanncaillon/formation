<?php
namespace OCFram;

class Config extends ApplicationComponent
{
    protected $var_a = [];

    public function get($var)
    {
        if (!$this->var_a) {
            $xml = new \DOMDocument;
            $xml->load(__DIR__ . '/../../App/' . $this->App->name() . '/Config/app.xml');

            $elements = $xml->getElementsByTagName('define');

            foreach ($elements as $element) {
                $this->var_a[$element->getAttribute('var')] = $element->getAttribute('value');
            }
        }

        if (isset($this->var_a[$var])) {
            return $this->var_a[$var];
        }

        return null;
    }
}