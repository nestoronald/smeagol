<?php
namespace Admin\Model;
use Smeagol\Model\NodeTable;
use Zend\Db\TableGateway\TableGateway;
// Class Select
use Zend\Db\Sql\Select;

class Noticia extends NodeTable 
{
	public function __construct(TableGateway $tableGateway)
	{
		parent::__construct($tableGateway);
	}

	public function fetchAllNoticias()
	{
  		// Realizando un select para obetner los nodos de tipo página
        	$resultSet = $this->tableGateway->select(function (Select $select) {
     		$select->where->equalTo('node_type_id', 2);
     		$select->order('id DESC');
		});
        	return $resultSet;
	}

	public function getNoticia($id)
	{
		return $this->getNode($id);
	}
	
	public function getNoticiaByIdentifier($identifier)
	{
		$rowset = $this->tableGateway->select(array('url' => $identifier));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $identifier");
		}
		return $row;
	}

        public function saveNoticia($noticia) {
        $data = array(
            'content' => $noticia->content,
            'title' => $noticia->title,
            'url' => $noticia->url,
            'node_type_id' => $noticia->node_type_id,
            'user_id' => $noticia->user_id,
            'created' => $noticia->created,
            'modified' => $noticia->modified,
        );

        $id = (int) $noticia->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getNoticia($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Noticia does not exist');
            }
        }
    }

    public function deleteNoticia($id) {
        $this->tableGateway->delete(array('id' => $id));
    }

    
    
    
    
    
    
}