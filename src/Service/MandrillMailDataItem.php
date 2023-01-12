<?php

namespace Cap\Commercio\Service;

class MandrillMailDataItem{
    private $name;
    private $content;

    public function __construct($name, $content){
        $this->name = $name;
        $this->content = $content;
    }

    public function toArray(){
        return array("name"=>$this->name, "content"=>$this->content);
    }


}