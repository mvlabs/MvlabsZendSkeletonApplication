<?php
namespace Application\Service;
use Zend\Mvc\Router\SimpleRouteStack;
use Zend\Mvc\I18n\Translator;
use Zend\Mvc\Router\Http\RouteMatch;

/**
 * Implements SummaryBuilder
 *
 * @author mauilap
 */
class LocaleService {

	/**
	 * Language configuration
	 * @var array
	 */
	private $am_localeConf;

	/**
	 * Router instance
	 * @var unknown
	 */
	private $I_router;

	/**
	 * Translator instance
	 * @var unknown
	 */
	private $I_translator;

	/**
	 * Matched Route
	 * @var unknown
	 */
	private $I_routeMatch;


	/**
	 * Builds service
	 *
	 * @param SimpleRouteStack $I_router
	 * @param Translator $I_translator
	 * @param array $am_localeConf
	 */
    public function __construct(SimpleRouteStack $I_router,
    							RouteMatch $I_routeMatch,
    		                    Translator $I_translator,
    		                    array $am_localeConf) {
    	$this->am_localeConf = $am_localeConf;
    	$this->I_router = $I_router;
    	$this->I_translator = $I_translator;
    	$this->I_routeMatch = $I_routeMatch;

    }

     /**
     * Returns user locale from preferences, or default if none provided
     *
     * @param array locale conf for this application
     * @return string selected locale for user
     */

     public function getUserLocale() {

     	// Default language is set
     	$s_language = $this->am_localeConf['default'];

     	// Are we receiving a preference
     	$s_accepted = strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']);

     	if (array_key_exists(substr($s_accepted, -2), $this->am_localeConf['available'])) {

     		// Is there a specific country version available (IE US)?
     		$s_language = $this->am_localeConf['available'][substr($s_accepted, -2)];

     	} else {

     		// Is there a generic language version available (IE en)?
     		$s_userLocale = substr($s_accepted,0,2);

     		if (array_key_exists($s_userLocale, $this->am_localeConf['available'])) {
     			$s_language = $s_userLocale;
     		}
     	}

     	return $s_language;

     }


     /**
      * Returns Current Locale
      */
     public function getCurrentLocale() {
     	return $this->I_routeMatch->getParam('locale');
     }


     /**
     * Returns currently invoked URI, translated in all available languages.
     *
     * @return array URI translations
     */
     public function getTranslatedURL(){

		$as_result = array();

		// $I_translator = $I_application->getServiceManager()->get('translator');
		$I_routeMatch = $this->I_routeMatch;
		$am_params = $I_routeMatch->getParams();

		foreach($this->am_localeConf['available'] as $s_locale => $am_configParams) {

			$am_translatedParams = array();
			foreach ($am_params as $s_paramName => $m_paramValue) {
				// echo $s_paramName." ".$m_paramValue." - ";
				$am_translatedParams[$s_paramName] = $this->I_translator->translate($m_paramValue,
						'routing',
						$this->am_localeConf['available'][$s_locale]['language']);
			}
			$am_translatedParams['locale'] = $s_locale;

			// $I_router = $I_application->getServiceManager()->get('Router');
			$am_configParams['url'] = $this->I_router->assemble(
					$am_translatedParams,
					array('name' => $I_routeMatch->getMatchedRouteName()
					)
			);
			// $am_configParams['url'] =  '/' . $s_newLang . $I_translateAdapter->translate($s_controller) . $s_action;

			$as_result[$s_locale] = $am_configParams;

		}

		return $as_result;
     }

}

