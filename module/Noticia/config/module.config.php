<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Noticia\Controller\Index' => 'Noticia\Controller\IndexController',
        ),
    ),
    
    'router' => array(
        'routes' => array(
            'noticia' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/noticia[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                    	'__NAMESPACE__' => 'Noticia\Controller',
                        'controller' => 'Noticia\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
	'view_manager' => array(
			'template_path_stack' => array(
					'noticia' => __DIR__ . '/../view',
			),
	),
);
