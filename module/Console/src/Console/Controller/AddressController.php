<?php
namespace Console\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Console\Form\AddressForm;

/**
 * AddressController
 *
 * @author
 *
 * @version
 *
 */
class AddressController extends AbstractActionController
{

    /**
     * The default action - show the home page
     */
    public function indexAction()
    {
        // TODO Auto-generated AddressController::indexAction() default action
        return new ViewModel();
    }
    
    public function editAction() {
        
        $form = new AddressForm();
        $vars = array('form'=>$form);
        
        $view_page = new ViewModel($vars);
        //$view_page = $this->setChildViews($view_page);
        
        return $view_page;
    }
}