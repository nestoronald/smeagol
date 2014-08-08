<?php
namespace Smeagol\Model;
class RolePermission
{
    public $role_type;
    public $permission_resource;

    public function exchangeArray($data){
        $this->role_type     = (isset($data['role_type'])) ? $data['role_type'] : null;
        $this->permission_resource     = (isset($data['permission_resource'])) ? $data['permission_resource'] : null;

    }
}