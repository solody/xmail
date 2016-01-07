<?php
namespace Console\Service;

use Zend\Db\Adapter\Adapter;
use Console\Model\Task;
use Console\Model\Queue;
use Zend\Db\TableGateway\TableGateway;
use Zend\Stdlib\Hydrator\ObjectProperty as ObjectPropertyHydrator;
use Console\Form\QueueItemsForm;

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
    
    public function getTaskList($page_num){
    
        $hydrator = new ObjectPropertyHydrator();
        $objectPrototype = new Task();
        $resultSet = new \Zend\Db\ResultSet\HydratingResultSet($hydrator, $objectPrototype);
    
        $query = new \Zend\Db\Sql\Select();
        $query->from('task');
    
        $adapter = new \Zend\Paginator\Adapter\DbSelect($query, $this->adapter, $resultSet);
        $paginator = new \Zend\Paginator\Paginator($adapter);
    
        $paginator->setCurrentPageNumber($page_num);
    
        return $paginator;
    }
    
    public function getQueue($task_id,$page_num){
        
        $hydrator = new ObjectPropertyHydrator();
        $objectPrototype = new Queue();
        $resultSet = new \Zend\Db\ResultSet\HydratingResultSet($hydrator, $objectPrototype);
        
        $query = new \Zend\Db\Sql\Select();
        $query->from('queue')->where(array('task_id'=>$task_id));
        
        $adapter = new \Zend\Paginator\Adapter\DbSelect($query, $this->adapter, $resultSet);
        $paginator = new \Zend\Paginator\Paginator($adapter);
        
        $paginator->setCurrentPageNumber($page_num);
        
        return $paginator;
    }
    
    public function appendQueueItems($task_id, $document_id, $address_type)
    {
        $insert_data = array();
        $address_data = null;
        
        $sql = "SELECT `id` FROM `address`";
        $sql_pamas = array();
        if (QueueItemsForm::ADDRESS_TYPE_NEW === intval($address_type)) {
            $sql .= "WHERE `id` NOT IN (SELECT `address_id` FROM `queue` WHERE `document_id`=?)";
            array_push($sql_pamas, $document_id);
        }
        $address_data = $this->adapter->query($sql, $sql_pamas);
        
        if ($address_data->count()) {
            foreach ($address_data as $address_data_row) {
                array_push($insert_data, array(
                    'task_id' => intval($task_id),
                    'document_id' => intval($document_id),
                    'address_id' => intval($address_data_row['id']),
                    'create_time' => date('Y-n-j H:i:s',time()),
                ));
            }
        }
        
        $queueTable = new TableGateway('queue', $this->adapter);
        
        $insert_count = 0;
        foreach ($insert_data as $insert_data_row) {
            if ($queueTable->insert($insert_data_row)) $insert_count++;
        }
        
        return $insert_count;
    }
}