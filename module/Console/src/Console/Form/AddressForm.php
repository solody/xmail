<?php
namespace Console\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;

class AddressForm extends Form
{

    const ADDRESS_SEX_BOY = 1;
    const ADDRESS_SEX_GIRL = 2;
    function __construct()
    {
        parent::__construct('console_address');

        $element_id = new Element\Hidden('id');
        $element_id->setValue('');
        $this->add($element_id);
        
        $this->add(array(
            'name' => 'username',
            'type' => 'Text',
        ));
        
        $this->add(array(
            'name' => 'domain',
            'type' => 'Text',
        ));
        
        $this->add(array(
            'name' => 'nickname',
            'type' => 'Text',
        ));
        
        $element_sex = new Element\Select('sex');
        $element_sex->setValueOptions(array(
            self::ADDRESS_SEX_BOY=>'男性',
            self::ADDRESS_SEX_GIRL=>'女性',
        ));
        $this->add($element_sex);
        
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
        ));
        
        
        /**
         * Setting up inputFilter
         */
        $input_id = new Input('id');
        $input_id->setRequired(false)
                            ->getValidatorChain()
                            ->attach( new \Zend\Validator\StringLength(array('max' => 50)) )
                            ->attach( new \Zend\Validator\Digits() );
                            
        $input_username = new Input('username');
        $input_username->setRequired(true)
                            ->getValidatorChain()
                            ->attach( new \Zend\Validator\StringLength(array('max' => 18)) )
                            ->attach( new \Zend\Validator\Digits() );
        
        
        $input_filter = new InputFilter();
        $input_filter->add($input_id);
        $input_filter->add($input_username);
        
        $this->setInputFilter($input_filter);
        
    }
}