<?php
namespace Console\Service;

use Zend\Db\Adapter\Adapter;
use Console\Model\Document;
use Zend\Stdlib\Hydrator\ObjectProperty as ObjectPropertyHydrator;
use Zend\Db\TableGateway\TableGateway;

class DocumentService
{
    protected $adapter;
    
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }
    
    public function saveDocument(Document $document)
    {
        $documentTable = new TableGateway('document', $this->adapter);
        
        $data = array(
            'id'=>intval($document->id),
            'title'=>$document->title,
            'content'=>$document->content,
        );
        
        if (empty($data['id'])) {
            $documentTable->insert($data);
            $id = $documentTable->lastInsertValue;
        } else {
            $id = $documentTable->update($data, array('id'=>$data['id']));
        }
        
        return $id;
    }
    
    public function getDocument($id)
    {
        $documentTable = new TableGateway('document', $this->adapter);
        $rs = $documentTable->select(array(
            'id'=>intval($id),
        ));
        
        if ($rs->count()) {
            
            $row = $rs->current();
            
            $ObjectPropertyHydrator = new ObjectPropertyHydrator;
            $document = new Document();
            $ObjectPropertyHydrator->hydrate($row->getArrayCopy(), $document);
            
            return $document;
            
        } else {
            throw new \Exception('data not exsist!');
        }
    }
    
    public function getDocumentPaginator($page_num)
    {
        $hydrator = new ObjectPropertyHydrator();
        $objectPrototype = new Document();
        $resultSet = new \Zend\Db\ResultSet\HydratingResultSet($hydrator, $objectPrototype);
        
        $query = new \Zend\Db\Sql\Select();
        $query->from('document');
        
        $adapter = new \Zend\Paginator\Adapter\DbSelect($query, $this->adapter, $resultSet);
        $paginator = new \Zend\Paginator\Paginator($adapter);
        
        $paginator->setCurrentPageNumber($page_num);
        
        return $paginator;
    }
}

?>