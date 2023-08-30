<?php

namespace App\Core;

class Response{

    protected $title;
    protected $text;

    public function __construct($title, $text)
    {
        $this->title = $title;
        $this->text = $text;
    }

    public function getText()
    {
        return $this->text;
    }
    public function getTitle()
    {
        return $this->title;
    }

}
