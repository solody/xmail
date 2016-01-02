<?php
namespace Console\Factory;

use Console\Controller\DocumentController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DocumentControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $realServiceLocator = $serviceLocator->getServiceLocator();
        $documentService     = $realServiceLocator->get('Console\Service\DocumentService');
        $dbAdapter          = $realServiceLocator->get('Zend\Db\Adapter\Adapter');
    
        return new DocumentController($documentService, $dbAdapter);
    }
}