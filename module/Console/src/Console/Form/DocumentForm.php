<?php
namespace Console\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;

class DocumentForm extends Form
{
    function __construct()
    {
        parent::__construct('console_document');
    
        $element_id = new Element\Hidden('id');
        $element_id->setValue('');
        $this->add($element_id);
    
        $this->add(array(
            'name' => 'title',
            'type' => 'Text',
        ));
        
        $this->add(array(
            'name' => 'content',
            'type' => 'Textarea',
        ));
    
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
        ));
    
    }
}

?>