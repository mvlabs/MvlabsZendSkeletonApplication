<?php

/**
 * MV Labs ZF2 Skeleton application initialization file
 *
 * @copyright Copyright (c) 2010-2013 MV Labs (http://www.mvlabs.it)
 * @link      http://github.com/mvlabs/MvlabsZendSkeletonApplication
 * @license   Please view the LICENSE file that was distributed with this source code
 * @author    Steve Maraspin <steve@mvlabs.it>
 * @package   MvlabsZendSkeletonApplication
 */


// Everything is now relative to the application root
chdir(dirname(__DIR__));

// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
    return false;
}

// Autoloading Setup
require 'init_autoloader.php';

// What environment are we in?
$s_env = getenv('APPLICATION_ENV');
if (empty($s_env)) {
	// We cannot allow application to be run without knowing what environment we're in
	throw new Exception('Environment not set. Cannot continue. Too risky!');
}

// Application nominal environment
$am_conf = $am_originalConf = require 'config/application.config.php';

// Environment specific configuration
$s_environmentConfFile = 'config/application.'.$s_env.'.config.php';

// Do we have a specific configuration file?
// This approach: http://blog.evan.pro/environment-specific-configuration-in-zend-framework-2 has been brought here
if (file_exists($s_environmentConfFile) &&
    is_readable($s_environmentConfFile)) {

	// Specific environment configuration is fetched and merged with standard configuration
	$am_environmentConf = require $s_environmentConfFile;
	$am_conf = Zend\Stdlib\ArrayUtils::merge($am_originalConf, $am_environmentConf);

	// Additional Specific configuration files are also taken into account
	$am_conf["module_listener_options"]["config_glob_paths"][] =  'config/autoload/{,*.}' . $s_env . '.php';

}

// Let's run application!
Zend\Mvc\Application::init($am_conf)->run();
