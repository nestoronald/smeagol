<?php
namespace Smeagol\Rules;

use Smeagol\Model\NodeTable;
//componenente de symfony para crear reglas de negocio
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
//use Zend\Db\TableGateway\Feature\EventFeature;

class NodeRule extends NodeTable {

    public function __construct($tableGateway) {
        parent::__construct($tableGateway);
    }
    
    public function userIsOwner($nodeid, $userid){
        $node = $this->getNode($nodeid);
        $e = new ExpressionLanguage();
        // regla de negocio
        $rule = "node.user_id == userid";       
        // evaluación de la regla (retorna true o false)
        return $e->evaluate($rule,array('node'=> $node,'userid'=>$userid));
    }
}

