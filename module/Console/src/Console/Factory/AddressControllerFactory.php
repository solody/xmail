<?php
namespace Console\Factory;

use Console\Controller\AddressController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AddressControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $realServiceLocator = $serviceLocator->getServiceLocator();
        $addressService     = $realServiceLocator->get('Console\Service\AddressServiceInterface');
        
        return new AddressController($addressService);
    }
}

?>