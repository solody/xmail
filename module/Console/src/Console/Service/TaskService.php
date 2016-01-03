<?php
namespace Console\Service;

use Zend\Db\Adapter\Adapter;
use Console\Model\Task;
use Zend\Db\TableGateway\TableGateway;
use Zend\Stdlib\Hydrator\ObjectProperty as ObjectPropertyHydrator;

class TaskService
{
    protected $adapter;
    
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }
    
    public function saveTask(Task $task)
    {
        $taskTable = new TableGateway('task', $this->adapter);
        
        $data = array(
            'id'=>intval($task->id),
            'title'=>$task->title,
        );
        
        if (empty($data['id'])) {
            $data['create_time'] = date('Y-n-j H:i:s',time());
            $taskTable->insert($data);
            $id = $taskTable->lastInsertValue;
        } else {
            $id = $taskTable->update($data, array('id'=>$data['id']));
        }
        
        return $id;
    }
    
    public function getTask($task_id)
    {
        $taskTable = new TableGateway('task', $this->adapter);
        $rs = $taskTable->select(array(
            'id'=>intval($task_id),
        ));
        
        if ($rs->count()) {
        
            $row = $rs->current();
        
            $ObjectPropertyHydrator = new ObjectPropertyHydrator;
            $task = new Task();
            $ObjectPropertyHydrator->hydrate($row->getArrayCopy(), $task);
        
            return $task;
        
        } else {
            throw new \Exception('data not exsist!');
        }
    }
}