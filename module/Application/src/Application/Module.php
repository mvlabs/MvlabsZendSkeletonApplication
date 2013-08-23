<?php

/**
 * MV Labs ZF2 Skeleton application module initialization file
 *
 * @copyright Copyright (c) 2010-2013 MV Labs (http://www.mvlabs.it)
 * @link      http://github.com/mvlabs/MvlabsZendSkeletonApplication
 * @license   Please view the LICENSE file that was distributed with this source code
 * @author    Steve Maraspin <steve@mvlabs.it>
 * @package   MvlabsZendSkeletonApplication
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{

	public function onBootstrap(MvcEvent $I_e) {

    	$s_env = getenv('APPLICATION_ENV');

    	if (empty($s_env)) {
    		throw new Exception('Environment not set. Cannot continue. Too risky!');
    	}

    	// Proper environment name is sent to the view part (for CSS or other customizations, if needed)
    	$I_application = $I_e->getParam('application');
    	$I_viewModel = $I_application->getMvcEvent()->getViewModel();
    	$I_viewModel->environment = $s_env;

    	// Environment configuration parameters are set
        $am_config = $I_application->getConfig();
        $this->loadConfig($am_config);

    }

    /**
     * Application configuration is returned
     * @return array Application configuration
     */
    public function getConfig()
    {

    	return include __DIR__ . '/../../config/module.config.php';

    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ ,
                ),
            ),
        );
    }

    /**
     * Takes care of loading mvlabs environment configuration parameters
     *
     * @param array $am_config application configuration
     */
    private function loadConfig(array $am_config) {

    	if(array_key_exists('mvlabs_environment', $am_config)) {

    		$am_environmentConf = $am_config['mvlabs_environment'];

    		// PHP Settings are Set
    		if (array_key_exists('php_settings', $am_environmentConf)) {
    			$this->setPhpEnvVars($am_environmentConf['php_settings']);
    		}

    		// Should we attempt to recover from fatal errors also - IE w/ a Redirection to a nicely crafted page?
    		if (array_key_exists('recover_from_fatal', $am_environmentConf) && $am_environmentConf['recover_from_fatal']) {

    			// @TODO: I'm sure there's a better way to obtain this...
    			$s_redirectUrl = $am_config['router']['routes']['error']['options']['route'];

    			$s_callback = $am_environmentConf['fatal_errors_callback'];

    			register_shutdown_function(array('Application\Module', 'handleFatalPhpErrors'), $s_redirectUrl, $s_callback);
    		}

    		// Should PHP errors be turned into exceptions?
    		if (array_key_exists('exceptions_from_errors', $am_environmentConf) && $am_environmentConf['exceptions_from_errors']) {
    			set_error_handler(array('Application\Module','handlePhpErrors'));
    		}


    		// Timezone is set
    		$s_timeZone = (array_key_exists('default_timezone', $am_environmentConf)?$am_environmentConf['default_timezone']:'Europe/London');
    		date_default_timezone_set($s_timeZone);

    	}

    }

	/**
	 * Sets php environment variables
	 *
	 * @param array $am_phpSettings php specific settings
	 */
    private function setPhpEnvVars(array $am_phpSettings) {
    	foreach($am_phpSettings as $key => $value) {
    		ini_set($key, $value);
    	}
    }


    /**
     * Error handler which turns PHP errors into exceptions
     *
     * @param integer $i_type
     * @param string $s_message
     * @param string $s_file
     * @param integer $i_line
     * @throws Exception
     */
    public static function handlePhpErrors($i_type, $s_message, $s_file, $i_line) {
    	if (!($i_type & error_reporting())) return;
		throw new Exception("Error: " . $s_message . " in file " . $s_file . " at line " . $i_line);
    }


	/**
	 * Redirects user to nice page after fatal has occurred
	 *
	 * @param string $s_redirectUrl URL where user is directed to after a fatal has occurred
	 * @param string $s_callback callback function to be called - IE for specific mailing/logging purposes
	 */
    public static function handleFatalPhpErrors($s_redirectUrl, $s_callback = null) {

    	if (php_sapi_name() != 'cli' && @is_array($e = @error_get_last())) {

    		if (null != $s_callback) {

    			// This is the most stuff we can get, since all of this happens in a "new context" outside of framework
    			$m_code = isset($e['type']) ? $e['type'] : 0;
    			$s_msg = isset($e['message']) ? $e['message'] : '';
    			$s_file = isset($e['file']) ? $e['file'] : '';
    			$i_line = isset($e['line']) ? $e['line'] : '';

    			$s_callback($s_msg, $s_file, $i_line);

    		}

    		header("location: ". $s_redirectUrl);

    	}

    	return false;

    }


}
