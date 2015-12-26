<?php
namespace Console\Model;

class Address
{
    public $id;
    public $address;
    public $name;
    public $sex;
    
    public function __construct($address = '', $name = '', $sex = NULL)
    {
        $this->address = $address;
        $this->name    = $name;
        $this->sex     = $sex;
    }
}

?>