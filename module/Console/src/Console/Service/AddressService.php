<?php
namespace Console\Service;

use Zend\Db\Adapter\Adapter;
use Console\Model\Address;
use Zend\Stdlib\Hydrator\ObjectProperty as ObjectPropertyHydrator;
use Zend\Db\TableGateway\TableGateway;
use Console\Service\Exception\AddressAlreadyExsistException;

class AddressService implements AddressServiceInterface
{
    protected $adapter;
    
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }
    
    public function saveAddress(Address $address)
    {
        if ($this->checkAddress($address->address)) {
            
            // Save now.
            $addressTable = new TableGateway('address', $this->adapter);
            
            $hydrator = new ObjectPropertyHydrator;
            $data = $hydrator->extract($address);
            $addressTable->insert($data);
            $id = $addressTable->lastInsertValue;
            
            return $id;
            
        } else {
            throw new AddressAlreadyExsistException('Address already exsist!');
        }
    }
    
    public function checkAddress($address)
    {
        $row = $this->adapter->query('SELECT count(*) as count FROM `address` WHERE `address`=?', array($address))->current();
        
        $r = false;
        if (intval($row['count']) == 0) $r = true;
        
        return $r;
    }
    
    public function getAddress($id)
    {
        
    }
}