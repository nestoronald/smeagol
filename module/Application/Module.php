<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
// Add these import statements:
use Smeagol\Model\Node;
use Smeagol\Model\NodeTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;


class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{
    public function onBootstrap(MvcEvent $e)
    {
        $e->getApplication()->getServiceManager()->get('translator');
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $eventManager->attach('route', function($e) {

            // decide which theme to use by get parameter
            $layout = 'enterprise/layout';
            $e->getViewModel()->setTemplate($layout);
        });
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                    "Smeagol" => __DIR__ . '/../../model/src/Smeagol',
                ),
            ),
        );
    }
    
    // Add this method:
    public function getServiceConfig()
    {
    	return array(
    			'factories' => array(
    					'Smeagol\Model\NodeTable' =>  function($sm) {
    						$tableGateway = $sm->get('NodeTableGateway');
    						$table = new NodeTable($tableGateway);
    						return $table;
    					},
    					'NodeTableGateway' => function ($sm) {
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$resultSetPrototype = new ResultSet();
    						$resultSetPrototype->setArrayObjectPrototype(new Node());
    						return new TableGateway('node', $dbAdapter, null, $resultSetPrototype);
    					},
    			),
    	);
    }
}