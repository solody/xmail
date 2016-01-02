<?php
namespace Console\Model;

class Task
{
    public $id;
    public $title;
    
    public function __construct($title = '')
    {
        $this->title = $title;
    }
}