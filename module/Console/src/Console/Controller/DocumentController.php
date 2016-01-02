<?php
namespace Console\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Console\Form\DocumentForm;
use Console\Service\DocumentService;
use Console\Model\Document;
use Zend\Stdlib\Hydrator\ObjectProperty as ObjectPropertyHydrator;

/**
 * DocumentController
 *
 * @author
 *
 * @version
 *
 */
class DocumentController extends AbstractActionController
{
    protected $documentService;
    protected $dbAdapter;
    
    public function __construct(DocumentService $documentService, \Zend\Db\Adapter\Adapter $dbAdapter)
    {
        $this->documentService = $documentService;
        $this->dbAdapter = $dbAdapter;
    }
    /**
     * The default action - show the home page
     */
    public function indexAction()
    {
        $paginator = $this->documentService->getDocumentPaginator($this->params()->fromRoute('page'));
        
        return new ViewModel(array(
            'paginator'=>$paginator,
        ));
    }
    
    public function editAction()
    {
        $documentService = $this->getServiceLocator()->get('Console\Service\DocumentService');
        $ObjectPropertyHydrator = new ObjectPropertyHydrator;
        
        $form = new DocumentForm();
        $vars = array('form'=>$form);
        
        $document_id = $this->params()->fromRoute('document_id',null);
        
        if (!empty($document_id)) {
            $document = $documentService->getDocument($document_id);
            
            $document_data = $ObjectPropertyHydrator->extract($document);
            
            $form->setData($document_data);
        }
        
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            
            $post_data = $request->getPost();
            
            $form->setData($post_data);
            
            if ($form->isValid()) {
                
                
                $document = new Document;
                
                $ObjectPropertyHydrator->hydrate($form->getData(), $document);
                
                $vars['saved_int'] = $documentService->saveDocument($document);
            }
        }
        
        $view_page = new ViewModel($vars);
        //$view_page = $this->setChildViews($view_page);
        
        return $view_page;
    }
}