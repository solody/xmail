<?php
namespace Console\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Console\Service\TaskService;

class TaskServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        //$realServiceLocator = $serviceLocator->getServiceLocator();
        $adapter            = $serviceLocator->get('Zend\Db\Adapter\Adapter');
    
        return new TaskService($adapter);
    }
}