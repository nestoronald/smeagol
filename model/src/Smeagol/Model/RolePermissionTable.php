<?php

namespace Smeagol\Model;

use Zend\Db\TableGateway\TableGateway;

class RolePermissionTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll() {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getRolePermission($role_type,$permission_resource) {
        $rowset = $this->tableGateway->select(array('role_type' => $role_type,
            'permission_resource'=>$permission_resource));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $type");
        }
        return $row;
    }
}
