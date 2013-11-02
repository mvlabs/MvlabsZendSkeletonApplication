<?php

/**
 * ZF2 Skeleton application development environment configuration
 *
 * @copyright Copyright (c) 2010-2013 MV Labs (http://www.mvlabs.it)
 * @link      http://github.com/mvlabs/MvlabsZendSkeletonApplication
 * @license   Please view the LICENSE file that was distributed with this source code
 * @author    Steve Maraspin <steve@mvlabs.it>
 * @package   MvlabsZendSkeletonApplication
 */

return array(

		/*
		 Specific development environment routes can be defined herein
		 'router' => array(
		 		'routes' => array(
		 				'admin' => array(
		 						'options' => array(
		 								'route'  => 'admin.dev.mydomain.it',
		 						),
		 				),
		 		),
		 ),
		*/

		// View manager is set with debugging
		'view_manager' => array(
				'display_not_found_reason' => true,
				'display_exceptions'       => true,
				'template_map' => array(
						// We want a page showing us full exception stack trace
						'error/index'             => __DIR__ . '/../../module/Application/view/error/debug.phtml',
				),
		),

		// Developer Tools Configuration
		'zenddevelopertools' => array(
				'profiler' => array(
						'enabled' => true,
						'strict' => false,
				),
				'toolbar' => array(
						 'enabled' => true,
						'auto_hide' => false,
						'position' => 'bottom',
						'version_check' => true,
				),
		),

		'mvlabs_environment' => array(

				'php_settings' => array(
					'error_reporting'  =>  E_ALL,
					'display_errors' => 'On',
					'display_startup_errors' => 'On',
				),

				'exceptions_from_errors' => true,
				'recover_from_fatal' => false,

				// here you should set the hostname of your development host(s)
				// dev configuration will be allowed to run here only!
				'allowed_hosts' => array('dev', 'my.development.host', 'my-collegue.development.host')

		),

);
