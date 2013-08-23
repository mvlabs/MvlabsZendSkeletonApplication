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
		 Specific production environment routes can be defined herein
		 'router' => array(
		 		'routes' => array(
		 				'admin' => array(
		 						'options' => array(
		 								'route'  => 'admin.mydomain.it',
		 						),
		 				),
		 		),
		 ),
		*/

		'mvlabs_environment' => array(

			// Set this w/ the timezone of your running application
			'default_timezone' => 'Europe/Rome',

			// here you should set the hostname of your production host(s)
			// production configuration will be allowed to run there only!
			'allowed_hosts' => array('my.production.host')
		),

);
