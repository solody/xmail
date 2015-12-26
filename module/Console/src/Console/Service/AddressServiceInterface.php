<?php
namespace Console\Service;

use Console\Model\Address;

interface AddressServiceInterface
{
    /**
     * Save an address.
     * 
     * @param AddressInterface $address
     * 
     * @return int database record id.
     */
    public function saveAddress(Address $address);
    
    /**
     * check an address that if it can be save into database.
     * 
     * @param AddressInterface $address
     * 
     * @return boolean true for not exsist in database.
     */
    public function checkAddress($address);
    
    /**
     * get an address object.
     * 
     * @param integer $id
     * 
     * @return AddressInterface $address
     */
    public function getAddress($id);
}