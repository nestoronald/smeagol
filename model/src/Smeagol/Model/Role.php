<?php
namespace Smeagol\Model;

class Node
{
    public $type;
    public $description;    

    public function exchangeArray($data)
    {
        $this->$type     = (isset($data['type'])) ? $data['type'] : null;
        $this->$description     = (isset($data['description'])) ? $data['description'] : null;        

    }
}