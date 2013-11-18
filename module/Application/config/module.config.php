<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(

	'router' => array(

		'routes' => array(

        		'locale' => array(

        			// You can choose one of these alternate ways of specifying language
        			// either through hostname or first query param

        			/*
        			'type' => 'Hostname',
        			'options' => array(
        					'route'    => '[:locale].dev.skel.it',
        					'constraints' => array(
        							'locale' => '[a-zA-Z]{2}',
        					),
        					'defaults' => array(
        							'controller' => 'index',
        							'action'     => 'index',
        					),
        			),
					*/

        			// 'type'    => 'Zend\Mvc\Router\Http\Segment',
        			'type'    => 'Segment',
        			'options' => array(
        					'route'    => '/[:locale]',
        					'constraints' => array(
        							'locale' => '[a-zA-Z]{2}',
        					),
        					'defaults' => array(
        							'__NAMESPACE__' => 'Application\Controller',
        							'controller' => 'index',
        							'action'     => 'index',
        					),
        			),

        			/*
        			 * No Multilanguage:
        			 * (remember to comment language-select below)
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        						'route'    => '/',
        						'defaults' => array(
        								'controller' => 'Application\Controller\Index',
        								'action'     => 'index',
        						),
        			),
        			*/

        			'may_terminate' => true,
        			'child_routes' => array(

        				'default' => array(
        					'type'    => 'Segment',
        					'options' => array(
        						'route'    => '/[:controller[/:action]]',
        							'constraints' => array(
        								'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
        								'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
        							),
        							'defaults' => array(
        								'__NAMESPACE__' => 'Application\Controller',
        								'controller' => 'index',
        								'action'     => 'index',
        							),
        						),
        						'may_terminate' => true,
        						'child_routes' => array(
        							'wildcard' => array(
        								'type' => 'Wildcard',
        								),
        							),
        						),

        						/*'contact' => array(
        								'type' => 'Literal',
        								'options' => array(
        										'route' => '/contact',
        										'defaults' => array(
        												'__NAMESPACE__' => 'PhlyContact\Controller',
        												'controller'    => 'PhlyContact\Controller\Contact',
        												'action'        => 'index',
        										),
        								),
        								'may_terminate' => true,
        								'child_routes' => array(
        										'process' => array(
        												'type' => 'Literal',
        												'options' => array(
        														'route' => '/process',
        														'defaults' => array(
        																'action' => 'process',
        														),
        												),
        										),
        										'thank-you' => array(
        												'type' => 'Literal',
        												'options' => array(
        														'route' => '/thank-you',
        														'defaults' => array(
        																'action' => 'thank-you',
        														),
        												),
        										),
        								),
        						),*/


        			),
        		),


        	// This route is used to handle redirections when fatal errors occur
        	// Better to have a nice page, rather than an ugly blank one
        	'error' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/error',
        					'defaults' => array(
        							'__NAMESPACE__' => 'Application\Controller',
        							'controller' => 'error',
        							'action'     => 'index',
        					),
        			),
        	),

        	'avoid-duplicates' => array(
        			'type' => 'Zend\Mvc\Router\Http\Segment',
        			'options' => array(
        				'route'    => '/[:locale]/index',
        				'constraints' => array(
        						'locale' => '[a-zA-Z]{2}',
        				),
        				'defaults' => array(
        						'controller' => 'Application\Controller\Language',
        						'action'     => 'duplicated',
        				),
        			),
        			'may_terminate' => true,
        			'child_routes' => array(
        					'index-action' => array(
        							'type'    => 'Segment',
        							'options' => array(
        									'route'    => '/index',
        									'defaults' => array(
        												'__NAMESPACE__' => 'Application\Controller',
        												'controller' => 'language',
        												'action'     => 'duplicated',
        										),
        								),
        								'may_terminate' => true,
        								'child_routes' => array(
        										'wildcard' => array(
        												'type' => 'Wildcard',
        										),
        								),
        					),
        			),
        	),


        	// Either a "select language" page can be created, or user can be redirected
        	// to proper location (default). Change language action in controller to allow manual language selection
        	// COMMENT IF USING HOSTNAME BASED locale ROUTE
        	'language-select' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/',
        					'defaults' => array(
        							'__NAMESPACE__' => 'Application\Controller',
        							'controller' => 'language',
        							'action'     => 'index',
        					),
        			),
        	),
        ),

    ),


    'service_manager' => array(
    		'factories' => array(
    				'localeService' => 'Application\Service\LocaleServiceFactory',
    		),
    		'allow_override' => true
    ),


    'controllers' => array(
        'invokables' => array(
            'Application\Controller\error' => 'Application\Controller\ErrorController',
            'Application\Controller\index' => 'Application\Controller\IndexController',
            'Application\Controller\docs' => 'Application\Controller\DocsController',            
        ),
        'factories' => array(
        	'language' => 'Application\Controller\LanguageControllerFactory',
        ),

    ),


    'view_helpers' => array(

    	'factories' => array(
			'languageMenu' => 'Application\View\Helper\LanguagemenuFactory',
			'localeUrl' => 'Application\View\Helper\LocaleurlFactory',
    	),

    ),


    'view_manager' => array(

    	'display_not_found_reason' => false,
    	'display_exceptions'       => false,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(

            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',

            // A nice page showing nothing about reasons for error (users do not care)
            // 'error/index'             => __DIR__ . '/../view/error/index.phtml',
            'error/index'             => __DIR__ . '/../view/error/debug.phtml',

        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),


    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),

);
