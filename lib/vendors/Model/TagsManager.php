<?php
namespace Model;

use Entity\News;
use \OCFram\Manager;
use \Entity\Tag;


abstract class TagsManager extends Manager
{

    abstract public function insertTag(Tag $Tag);

    abstract public function getTagUsingName($name);

    abstract public function existsTagUsingName($name);

    abstract public function deleteTagUsingId($id);

    abstract public function searchTagUsingName_a($name);

}