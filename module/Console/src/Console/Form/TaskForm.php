<?php
namespace Console\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;

class TaskForm extends Form
{
    function __construct()
    {
        parent::__construct('console_task');
    
        $element_id = new Element\Hidden('id');
        $element_id->setValue('');
        $this->add($element_id);
    
        $this->add(array(
            'name' => 'title',
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
    
        $input_title = new Input('title');
        $input_title->setRequired(true)
        ->getValidatorChain()
        ->attach( new \Zend\Validator\StringLength(array('max' => 255)) );
    
        $input_filter = new InputFilter();
        $input_filter->add($input_id);
        $input_filter->add($input_title);
    
        $this->setInputFilter($input_filter);
    }
}

?>