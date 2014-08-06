<?php
namespace Smeagol\Model;

use Zend\Db\TableGateway\TableGateway;
// Class Select
use Zend\Db\Sql\Select;

class RoleTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }
    
    
    public function getRole($role_type)
    {
        $role_type  = (string) $role_type;
        $rowset = $this->tableGateway->select(array('role_type' => $role_type));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $role_type");
        }
        return $row;
    }
    
}