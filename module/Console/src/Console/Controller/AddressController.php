<?php
namespace Console\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Console\Form\AddressForm;
use Zend\View\Model\JsonModel;
use Console\Service\AddressServiceInterface;
use Console\Model\Address;
use Console\Service\Exception\AddressAlreadyExsistException;

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
    protected $addressService;
    protected $dbAdapter;
    
    public function __construct(AddressServiceInterface $addressService, \Zend\Db\Adapter\Adapter $dbAdapter)
    {
        $this->addressService = $addressService;
        $this->dbAdapter = $dbAdapter;
    }
    
    /**
     * 显示所有已收集的Email地址
     */
    public function indexAction()
    {
        $hydrator = new \Zend\Stdlib\Hydrator\ObjectProperty();
        $objectPrototype = new \Console\Model\Address();
        $resultSet = new \Zend\Db\ResultSet\HydratingResultSet($hydrator, $objectPrototype);
        
        $query = new \Zend\Db\Sql\Select();
        $query->from('address');
        
        $adapter = new \Zend\Paginator\Adapter\DbSelect($query, $this->dbAdapter, $resultSet);
        $paginator = new \Zend\Paginator\Paginator($adapter);
        
        
        return new ViewModel(array(
            'paginator'=>$paginator,
        ));
    }
    
    public function editAction() 
    {
        
        $form = new AddressForm();
        $vars = array('form'=>$form);
        
        $view_page = new ViewModel($vars);
        //$view_page = $this->setChildViews($view_page);
        
        return $view_page;
    }
    
    /**
     * AJAX方式检查邮件地址是否可用，可用则说明邮件地址还没有在系统中，则否已存在系统中。
     */
    public function checkemailAction() 
    {
        $this->emailSubmitPrecheck();
        
        $post_data = $this->getRequest()->getPost();
        $email = trim($post_data['email']);
            
        $status = $this->addressService->checkAddress($email);
        
        return new JsonModel(array(
            'status'=>intval($status),
            'email'=>$email,
        ));

        
        
    }
    
    /**
     * AJAX方式保存一个邮件地址
     * @return \Zend\View\Model\JsonModel
     */
    public function saveemailAction()
    {
        $this->emailSubmitPrecheck();
        
        $post_data = $this->getRequest()->getPost();
        
        $email = trim($post_data['email']);
        
        $id = null;
        $status = true;
        $msg = '成功';
        try {
            $id = $this->addressService->saveAddress(new Address($email));
        } catch (AddressAlreadyExsistException $e) {
            $status = false;
            $msg = $e->getMessage();
        } finally {
            return new JsonModel(array(
                'id'=>intval($id),
                'email'=>$email,
                'status'=>intval($status),
                'msg'=>$msg,
            ));
        }
        
        
    }
    
    protected function emailSubmitPrecheck()
    {
        $request = $this->getRequest();
        
        if ($request->isPost()) {
        
            $post_data = $request->getPost();
        
            $email = trim($post_data['email']);
        
            if (empty($email))  throw new \Exception('Empty value!');
            
            $validator = new \Zend\Validator\EmailAddress();
            $msg = '';
            if (!$validator->isValid($email)) {
                // email is invalid; print the reasons
                foreach ($validator->getMessages() as $messageId => $message) {
                    $msg .= "Validation failure '$messageId': $message\n";
                }
                
                throw new \Exception($msg);
            } else {
                return true;
            }
        
        } else {
            throw new \Exception('Error method!');
        }
    }
}