<?php
namespace Console\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Console\Service\AddressService;

class AddressServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        //$realServiceLocator = $serviceLocator->getServiceLocator();
        $adapter            = $serviceLocator->get('Zend\Db\Adapter\Adapter');
    
        return new AddressService($adapter);
    }
}