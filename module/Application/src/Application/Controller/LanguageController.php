<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class LanguageController extends AbstractActionController {

	/**
	 * Zend Translator Instance
	 *
	 * @var unknown
	 */
	protected $I_translator;

	/**
	 * Language Configuration Params
	 *
	 * @var array configuration params
	 */
	protected $am_languageConf;

	/**
	 * Constructor
	 *
	 * @param array $am_translationParams
	 */
	public function __construct(array $am_translationParams) {
		$this->I_translator = $am_translationParams['translator'];
		$this->am_languageConf = $am_translationParams['languages'];
	}

	/*
	 * Redirects user to proper I18N site version
	 *
	 * Remove code below and prepare view file if you wish user to
	 * manually select location/language.
	 */
	public function indexAction() {

		$s_language = \Application\Module::getUserLocale($this->am_languageConf);

		// Let's redirect user to selected I18N site version
		return $this->redirect()->toRoute("locale",array("locale"=>$s_language));

    }

    /*
     * Redirects user to proper canonical I18N site version
     *
     */
    public function duplicatedAction() {

    	// Let's redirect user to selected I18N site version
    	return $this->redirect()->toRoute("locale", array("locale" => $this->params()->fromRoute('locale')));

    }

}
