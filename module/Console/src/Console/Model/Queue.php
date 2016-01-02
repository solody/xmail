<?php
namespace Console\Model;

class Queue
{
    public $id;
    public $task_id;
    public $address_id;
    public $document_id;
    public $status;
    public $create_time;
    
    public function __construct()
    {
    }
}