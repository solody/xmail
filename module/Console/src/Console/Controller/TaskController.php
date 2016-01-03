<?php
namespace Console\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Console\Service\TaskService;
use Console\Model\Task;
use Console\Form\TaskForm;
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
    protected $dbAdapter;
    
    public function __construct(TaskService $taskService, \Zend\Db\Adapter\Adapter $dbAdapter)
    {
        $this->taskService = $taskService;
        $this->dbAdapter = $dbAdapter;
    }
    /**
     * The default action - show the home page
     */
    public function indexAction()
    {
        // TODO Auto-generated TaskController::indexAction() default action
        return new ViewModel();
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
}