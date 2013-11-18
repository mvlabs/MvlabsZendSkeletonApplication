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

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\ModuleEvent;
use Zend\ModuleManager\ModuleManager;
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

	public function init(ModuleManager $moduleManager)
	{
	    //$moduleManager->getEventManager()->attach(ModuleEvent::EVENT_LOAD_MODULES_POST, array($this, 'setOptions'), 1010);
	    $moduleManager->getEventManager()->attach(ModuleEvent::EVENT_LOAD_MODULES_POST, array($this, 'addLocaleToRoutes'), 1000);
	
	    $moduleManager->getEventManager()->attach(ModuleEvent::EVENT_LOAD_MODULES_POST, array($this, 'overrideViewHelperUrl'));
	}
	
	
	// override url view helper with new locale aware
	public function overrideViewHelperUrl(ModuleEvent $I_e)
	{
	    $I_serviceLocator = $I_e->getParam('ServiceManager');
	    $I_viewHelperManager = $I_serviceLocator->get('ViewHelperManager');

	    // Configure URL view helper with router
	    $I_viewHelperManager->setFactory('url', 'Application\View\Helper\LocaleurlFactory');
	   
	    
	}
	
	public function addLocaleToRoutes(ModuleEvent $I_e)
	{
	    $I_config = $I_e->getConfigListener()->getMergedConfig(false);
	    
	    $I_routes = &$I_config['router']['routes'];
	
	    $as_availableValueArray = array_keys($I_config['mvlabs_environment']['locale']['available']);
	    
	    $s_localeConstraint = '(' . implode($as_availableValueArray, '|') . ')';
	    
	
	    foreach ($I_routes as $s_key => &$as_route) {
	        
	        if (strtolower($as_route['type']) === 'literal' || $as_route['type'] === 'Zend\Mvc\Router\Http\Literal') {
	            $as_route['type'] = 'Segment';
	        }
	        
	        if (strtolower($as_route['type']) === 'segment' || $as_route['type'] === 'Zend\Mvc\Router\Http\Segment') {
	            if (strpos($as_route['options']['route'], ':locale') === false) {
	                $as_route['options']['route'] = '[/:locale]' . $as_route['options']['route'];
	            }
	            if (!isset($as_route['options']['constraints'])) $as_route['options']['constraints'] = array();
	            $as_route['options']['constraints'] = array_merge(
	                $as_route['options']['constraints'],
	                array('locale' => $s_localeConstraint)
	            );
	        } elseif (strtolower($as_route['type']) === 'regex' || $as_route['type'] === 'Zend\Mvc\Router\Http\Regex') {
	            if (strpos($as_route['options']['route'], '<locale>') === false) {
	                $as_route['options']['route'] = '(/(?P<locale>'.$s_localeConstraint.'))?' . $as_route['options']['route'];
	                $as_route['options']['spec'] = '/locale' . $as_route['options']['spec']; //@todo needs to make optional on assemling
	            }
	        } else {
	            //throw something
	        }
	        
	        //sets the default locale
	        if (!isset($as_route['options']['defaults'])) 
	            $as_route['options']['defaults'] = array();
	        
	        $as_route['options']['defaults'] = array_merge(
	            $as_route['options']['defaults'],
	            array('locale' => $I_config['mvlabs_environment']['locale']['default'])
	        );
	    }
	
	    $I_e->getConfigListener()->setMergedConfig($I_config);
	}
	
	
	/*
	 * Called up upon module bootstrap - initializes it
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


        $I_application->getEventManager()->attach(\Zend\Mvc\MvcEvent:: EVENT_ROUTE, array($this, 'onRoute'));


	}


	/**
	 * I18N depends on request URL, hence is performed herein
	 * @param $e
	 */
    public function onRoute($I_e) {

    	//extract application instance from input event
    	$I_application = $I_e->getParam('application');
    	$I_router = $I_application->getServiceManager()->get('Router');

    	$am_config = $I_application->getConfig();
    	$I_translator = $I_application->getServiceManager()->get('translator');

    	$I_localeService = $I_application->getServiceManager()->get('localeService');
    	$I_routeMatch = $I_e->getRouteMatch();

    	// Is there multi-language support enabled?
    	if ('home' == $I_routeMatch->getMatchedRouteName() ||
    	// '' == $e->getRouteMatch()->getParam('controller') ||
    	!array_key_exists('locale', $am_config['router']['routes']) ||
    	!array_key_exists('mvlabs_environment', $am_config) ||
    	!array_key_exists('locale', $am_config['mvlabs_environment'])) {
    		return;
    	}

    	//get current language
    	$s_lang = $I_routeMatch->getParam('locale');
    	$am_languageConfig = $am_config['mvlabs_environment']['locale'];

    	if (!array_key_exists($s_lang, $am_languageConfig['available'])) {

    		// We set default locale
    		$s_lang = $I_localeService->getUserLocale();

    		// We had expected for a locale to be set. An invalid one must have been choosen
    		if ('locale' == $I_routeMatch->getMatchedRouteName()) {
    			$I_application->getResponse()->setStatusCode(404);
    		}

    	}

    	$I_translator = $I_application->getServiceManager()->get('translator');
    	$I_translator->setLocale($am_languageConfig['available'][$s_lang]['language']);

    	// We get translations from current URL in other languages (to be used when user picks a different language)
    	$I_viewModel = $I_application->getMvcEvent()->getViewModel();

    	$am_languageConfig['selected'] = $s_lang;

    	// @FIXME: Set Default!
    	$I_routeMatch->setParam("locale", $s_lang);
    	$I_e->setRouteMatch($I_routeMatch);

    	$s_getTextFile = null;
    	$s_getTextFilePath = __DIR__ . "/../../../../vendor/zendframework/zendframework/resources/languages";
    	list($s_language, $s_country) = explode("_", $am_languageConfig['available'][$s_lang]['language']);
    	$s_locale = implode("_", array($s_language, strtoupper($s_country)));

    	if (file_exists($s_getTextFilePath. '/' .  $s_locale."/Zend_Validate.php")) {
    		$s_getTextFile = $s_getTextFilePath. '/' .  $s_locale."/Zend_Validate.php";
    	} elseif (file_exists($s_getTextFilePath. '/' .  $s_lang."/Zend_Validate.php")) {
    		$s_getTextFile = $s_getTextFilePath. '/' .  $s_lang."/Zend_Validate.php";
    	}

    	if (null !== $s_getTextFile) {

    		$I_translator->addTranslationFile(
    				'phparray',
    				$s_getTextFile,
    				'default',
    				$am_languageConfig['available'][$s_lang]['language']
    		);
    		AbstractValidator::setDefaultTranslator($I_translator);

    	}


    	$I_viewModel->urlTranslations = $I_localeService->getTranslatedURL();
    	$I_viewModel->currentLanguage = $s_lang;

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
