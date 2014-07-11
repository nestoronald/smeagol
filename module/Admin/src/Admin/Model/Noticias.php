<?php
namespace Admin\Model;
use Smeagol\Model\NodeTable;
use Zend\Db\TableGateway\TableGateway;
// Class Select
use Zend\Db\Sql\Select;

class Noticias extends NodeTable 
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

	public function getNoticias($id)
	{
		return $this->getNode($id);
	}
	
	public function getNoticiasByIdentifier($identifier)
	{
		$rowset = $this->tableGateway->select(array('url' => $identifier));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $identifier");
		}
		return $row;
	}
        public function saveNoticias($noticias)
	{
		$data = array(
				'content'  => $noticias->content,
				'title' => $noticias->title,
				'url'  => $noticias->url,
				'node_type_id'  => $noticias->node_type_id,
				'user_id'  => $noticias->user_id,
				'created'  => $noticias->created,
				'modified'  => $noticias->modified,
		);
	
		$id = (int)$noticias->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
		} else {
			if ($this->getPage($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('Notice does not exist');
			}
		}
	}
        public function deleteNoticias($id)
	{
		$this->tableGateway->delete(array('id' => $id));
	}
	
}
