<?php
namespace Console\Model;

class Document
{
    public $id;
    public $title;
    public $content;
    
    public function __construct($title = '', $content = '')
    {
        $this->title = $title;
        $this->content    = $content;
    }
}

?>