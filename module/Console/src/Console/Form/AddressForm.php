<?php
namespace Console\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;

class AddressForm extends Form
{

    function __construct()
    {
        parent::__construct('console_address');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
        
        $this->add(array(
            'name' => 'qq',
            'type' => 'Text',
        ));
        
        $this->add(array(
            'name' => 'domain',
            'type' => 'Text',
        ));
        
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
                            
        $input_qq = new Input('qq');
        $input_qq->setRequired(true)
                            ->getValidatorChain()
                            ->attach( new \Zend\Validator\StringLength(array('max' => 18)) )
                            ->attach( new \Zend\Validator\Digits() );
        
        
        $input_filter = new InputFilter();
        $input_filter->add($input_id);
        $input_filter->add($input_qq);
        
        $this->setInputFilter($input_filter);
        
    }
}

?>