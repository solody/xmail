<?php
namespace Console\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Console\Service\TaskService;
use Console\Service\DocumentService;

class QueueItemsForm extends Form
{
    const ADDRESS_TYPE_ALL = 0;
    const ADDRESS_TYPE_NEW = 1;
    
    function __construct($task_id, TaskService $taskService, DocumentService $documentService)
    {
        parent::__construct('console_queueitems');
    
        $element_task_id = new Element\Hidden('task_id');
        $this->add($element_task_id);
        $element_task_id->setValue($task_id);
        
        $element_task_title = new Element\Text('task_title');
        $task = $taskService->getTask($task_id);
        $element_task_title->setValue($task->title);
        $this->add($element_task_title);
        
        $element_document = new Element\Select('document');
        $document_titles = $documentService->getDocumentTitles();
        $element_document->setValueOptions($document_titles);
        $this->add($element_document);
        
        $element_address_type = new Element\Select('address_type');
        $element_address_type->setValueOptions(array(
            self::ADDRESS_TYPE_ALL=>'所有邮件地址',
            self::ADDRESS_TYPE_NEW=>'新的邮件地址',
        ));
        $this->add($element_address_type);
        
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
        ));
    }
}

?>