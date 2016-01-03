<?php
namespace Console\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Console\Service\TaskService;
use Console\Service\DocumentService;
use Console\Model\Task;
use Console\Form\TaskForm;
use Console\Form\QueueItemsForm;
use Zend\Stdlib\Hydrator\ObjectProperty as ObjectPropertyHydrator;

/**
 * TaskController
 *
 * @author
 *
 * @version
 *
 */
class TaskController extends AbstractActionController
{
    protected $taskService;
    protected $documentService;
    protected $dbAdapter;
    
    public function __construct(TaskService $taskService, DocumentService $documentService, \Zend\Db\Adapter\Adapter $dbAdapter)
    {
        $this->taskService = $taskService;
        $this->documentService = $documentService;
        $this->dbAdapter = $dbAdapter;
    }
    /**
     * The default action - show the home page
     */
    public function indexAction()
    {
        $paginator = $this->taskService->getTaskList($this->params()->fromRoute('page'));
        
        return new ViewModel(array(
            'paginator'=>$paginator,
        ));
    }
    
    public function editAction()
    {
        $taskService = $this->getServiceLocator()->get('Console\Service\TaskService');
        $ObjectPropertyHydrator = new ObjectPropertyHydrator;
        
        $form = new TaskForm();
        $vars = array('form'=>$form);
        
        $task_id = $this->params()->fromRoute('task_id',null);
        
        if (!empty($task_id)) {
            $task = $taskService->getTask($task_id);
        
            $task_data = $ObjectPropertyHydrator->extract($task);
        
            $form->setData($task_data);
        }
        
        $request = $this->getRequest();
        
        if ($request->isPost()) {
        
            $post_data = $request->getPost();
        
            $form->setData($post_data);
        
            if ($form->isValid()) {
        
        
                $task = new Task;
        
                $ObjectPropertyHydrator->hydrate($form->getData(), $task);
        
                $vars['saved_int'] = $taskService->saveTask($task);
            }
        }
        
        $view_page = new ViewModel($vars);
        //$view_page = $this->setChildViews($view_page);
        
        return $view_page;
    }
    
    public function queueAction()
    {
        $task_id = $this->params()->fromRoute('task_id',null);
        
        $paginator = $this->taskService->getQueue($task_id, $this->params()->fromRoute('page'));
        
        return new ViewModel(array(
            'paginator'=>$paginator,
            'task_id'=>$task_id,
        ));
    }
    
    public function queueeditAction(){
        
        $task_id = $this->params()->fromRoute('task_id',null);
        $document_id = null;
        $address_id = null;
        
        $form = new QueueItemsForm($task_id, $this->taskService, $this->documentService);
        
        $vars = array('form'=>$form);
        $vars['task_id'] = $task_id;
        
        
        $request = $this->getRequest();
        
        if ($request->isPost()) {
        
            $post_data = $request->getPost();
        
            $form->setData($post_data);
        
            if ($form->isValid()) {
                
                $form_data = $form->getData();
                echo $this->taskService->appendQueueItems($task_id, $form_data['document'], $form_data['address_type']);
                $vars['saved'] = 1;
                
            }
            
        }
        
        
        $view_page = new ViewModel($vars);
        //$view_page = $this->setChildViews($view_page);
        
        return $view_page;
    }
}