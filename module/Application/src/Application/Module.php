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
use Zend\Validator\AbstractValidator;

class Module {

	/**
	 * Application configuration is returned
	 * @return array Application configuration
	 */
	public function getConfig()
	{

		return include __DIR__ . '/../../config/module.config.php';

	}


	/**
	 * Returns autoloader configuration
	 * @return multitype:multitype:multitype:string
	 */
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


	/*
	 *
	 */
	public function onBootstrap(MvcEvent $I_e) {

    	$s_env = getenv('APPLICATION_ENV');

    	// Application configuration
    	$I_application = $I_e->getParam('application');
    	$am_config = $I_application->getConfig();

    	// Are we running safely in this environment?
    	try {
    		$this->checkEnv($s_env, $am_config);
    	} catch (\Exception $I_exception) {

    		// $I_e = new \Zend\Mvc\MvcEvent();
    		$I_em = $I_application->getEventManager();
    		$I_e->setParam('exception', $I_exception);
    		$I_e->setParam('error', \Zend\Mvc\Application::ERROR_EXCEPTION);
    		$I_e->setName(MvcEvent::EVENT_DISPATCH_ERROR);

    		$I_em->trigger($I_e);

    	}

    	// Form message translation enabled
    	$I_translator = $I_application->getServiceManager()->get('translator');
    	AbstractValidator::setDefaultTranslator($I_translator);

    	// Proper environment name is sent to the view part (for CSS or other customizations, if needed)
    	$I_viewModel = $I_application->getMvcEvent()->getViewModel();
    	$I_viewModel->environment = $s_env;

    	// Environment configuration parameters are set
        $this->loadConfig($am_config);

        $I_application->getEventManager()->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH, array($this, 'onDispatch'));

	}


	/**
	 * I18N depends on request URL, hence is performed herein
	 * @param $e
	 */
    public function onDispatch($e) {

    	//extract application instance from input event
    	$I_application = $e->getParam('application');
    	$am_config = $I_application->getConfig();

    	// Is there multi-language support enabled?
    	if ('home' == $e->getRouteMatch()->getMatchedRouteName() ||
    		// '' == $e->getRouteMatch()->getParam('controller') ||
    		!array_key_exists('locale', $am_config['router']['routes']) ||
    		!array_key_exists('mvlabs_environment', $am_config) ||
    	    !array_key_exists('locale', $am_config['mvlabs_environment'])) {
    		return;
    	}

    	//get current language
    	$s_lang = $e->getRouteMatch()->getParam('locale');

    	$am_languageConfig = $am_config['mvlabs_environment']['locale'];

    	// Following might cause SEO issues. Hence commented & special route created
    	// if (empty($s_lang)) {
    	//	$s_lang = $am_languageConfig['default'];
    	// }

    	if (!array_key_exists($s_lang, $am_languageConfig['available'])) {
    		$I_application->getResponse()->setStatusCode(404);
    	}

    	$I_translator = $I_application->getServiceManager()->get('translator');
    	$I_translator->setLocale($am_languageConfig['available'][$s_lang]['language']);

    	/*
    	$s_basePath = $I_app->getRequest()->getBasePath();
    	$I_phpRenderer->plugin('basePath')->setBasePath($s_basePath);
    	*/

    	// $viewModel = $I_app->getMvcEvent()->getViewModel();
    	//setting to view->layout->translatedURL
    	// $viewModel->translatedURL = $this->getTranslatedURL();
    }



    /**
     * Returns the URI translated ready for the link for language change.
     *
     * @return String
     */
    private function getTranslatedURL(){

    	/*
    	//get lang, controller, action
    	$s_url = parse_url($_SERVER['REQUEST_URI']);

    	$s_path = explode('/',substr($s_url['path'], 1, strlen($s_url['path'])));
    	$s_lang = (isset($s_path[0]) ) ? $s_path[0] : '';
    	switch ($s_lang) {
    		case 'en':
    			$s_newLang = 'it';
    			break;
    		default:
    			$s_newLang = 'en';
    			break;
    	}

    	//define startup params and init gettext according to the new language
    	$as_options = array(
    			'locale' => $s_newLang,
    			'content' => __DIR__ . "/../../resources/languages/$s_newLang/texts.mo",
    			'disableNotices' => true
    	);

    	//get adapter
    	$I_translateAdapter = new \Zend\Translator\Adapter\Gettext($as_options);

    	$s_controller = (isset($s_path[1]) ) ? '/' . $I_translateAdapter->translate($s_path[1]) : '';
    	$s_action = (isset($s_path[2]) ) ? '/' . $I_translateAdapter->translate($s_path[2]) : '';

    	return '/' . $s_newLang . $I_translateAdapter->translate($s_controller) . $s_action ;

    	*/

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

    			// This is the most stuff we can get. All of this happens in a "new context" outside of framework
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


    /**
     * Checks whether application can run on current host w/ current env settings
     *
     * @param string $s_currentEnvironment
     * @param array $am_appConf application configuration
     * @return boolean
     */
    private function checkEnv($s_currentEnvironment, $am_appConf) {

		if (empty($s_currentEnvironment)) {
			throw new Exception('Environment not set. Cannot continue. Too risky!');
		}

	    if (!array_key_exists('mvlabs_environment', $am_appConf) ||
	        !array_key_exists('allowed_hosts', $am_appConf['mvlabs_environment']) ||
	        !is_array($am_appConf['mvlabs_environment']['allowed_hosts']) ||
	        count($am_appConf['mvlabs_environment']['allowed_hosts']) == 0
	   	    ) {
			// No checks are enforced
	    	return;
	   	}

	   	$s_hostName = gethostname();

	    if (!in_array($s_hostName, $am_appConf['mvlabs_environment']['allowed_hosts'])) {
	    	throw new Exception('Application is not supposed to run with ' . $s_currentEnvironment .
	    			            ' configuration on host ' . $s_hostName.
	    			            '. Did you remember to set allowed_hosts param on file configuration.'.$s_currentEnvironment.'.php? '
	    			           );
	    }

    }


    /**
     * Takes care of loading mvlabs environment configuration parameters
     *
     * @param array $am_config application configuration
     */
    private function loadConfig(array $am_config) {

    	if(!array_key_exists('mvlabs_environment', $am_config)) {
    		return;
    	}

    	$am_environmentConf = $am_config['mvlabs_environment'];

    	// PHP Settings are Set
    	if (array_key_exists('php_settings', $am_environmentConf) &&
    		is_array($am_environmentConf['php_settings'])) {
    		$this->setPhpEnvVars($am_environmentConf['php_settings']);
    	}

    	// Should we attempt to recover from fatal errors also - IE w/ a Redirection to a nicely crafted page?
    	if (array_key_exists('recover_from_fatal', $am_environmentConf) &&
    		$am_environmentConf['recover_from_fatal']) {

    		// @TODO: I'm sure there's a better way to obtain this...
    		$s_redirectUrl = $am_config['router']['routes']['error']['options']['route'];

    		$s_callback = null;
    		if (array_key_exists('fatal_errors_callback', $am_environmentConf)) {
    			$s_callback = $am_environmentConf['fatal_errors_callback'];
   			}

    		register_shutdown_function(array('Application\Module', 'handleFatalPhpErrors'),
    		                           $s_redirectUrl, $s_callback);
    	}

    	// Should PHP errors be turned into exceptions?
    	if (array_key_exists('exceptions_from_errors', $am_environmentConf) &&
    	    $am_environmentConf['exceptions_from_errors']) {
    		set_error_handler(array('Application\Module','handlePhpErrors'));
    	}

    	// Timezone is set
    	$s_timeZone = (array_key_exists('timezone', $am_environmentConf)?$am_environmentConf['timezone']:'Europe/London');
    	date_default_timezone_set($s_timeZone);

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


}
