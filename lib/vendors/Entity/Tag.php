<?php
namespace Entity;

use \OCFram\Entity;
use OCFram\Router;

class Tag extends Entity implements \JsonSerializable
{

    protected $TTC_id;
    protected $TTC_name;

    /**
     * @return mixed
     */
    public function id()
    {
        return $this->TTC_id;
    }

    /**
     * @param mixed $TTC_id
     */
    public function setId($TTC_id)
    {
        $this->TTC_id = $TTC_id;
    }

    /**
     * @return mixed
     */
    public function name()
    {
        return $this->TTC_name;
    }

    /**
     * @param mixed $TTC_name
     */
    public function setName($TTC_name)
    {
        $this->TTC_name = $TTC_name;
    }


    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [

            "id" => $this->id(),
            "name" => $this->name()

        ];
    }
    public function tagHtml()
    {
        return "<a class='tag' href='". Router::getInstance()->getRouteUrl("tag", "Frontend", array("name" => $this->name())) . "'>". $this->name() ."</a>";
    }

}