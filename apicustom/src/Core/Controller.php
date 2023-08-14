<?php

namespace App\Core;

class Controller
{
    public function __construct()
    {

    }

    public function render(array $assoc_array) : string {
        return json_encode($assoc_array);
    }
}