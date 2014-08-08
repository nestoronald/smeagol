<?php

namespace Smeagol\Model;

use Zend\Db\TableGateway\TableGateway;

class RoleTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll() {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getRole($type) {
        $rowset = $this->tableGateway->select(array('type' => $type));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $type");
        }
        return $row;
    }
}
