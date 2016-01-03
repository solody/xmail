<?php
namespace Console\Factory;

use Console\Controller\TaskController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TaskControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $realServiceLocator = $serviceLocator->getServiceLocator();
        $taskService     = $realServiceLocator->get('Console\Service\TaskService');
        $documentService = $realServiceLocator->get('Console\Service\DocumentService');
        $dbAdapter          = $realServiceLocator->get('Zend\Db\Adapter\Adapter');
    
        return new TaskController($taskService, $documentService, $dbAdapter);
    }
}

?>