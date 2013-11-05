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
	 * Locale Service
	 *
	 * @var unknown
	 */
	protected $I_localeService;


	/**
	 * Constructor
	 *
	 * @param array $am_translationParams
	 */
	public function __construct($I_localeService) {
		$this->I_localeService = $I_localeService;
	}


	/*
	 * Redirects user to proper I18N site version
	 *
	 * Remove code below and prepare view file if you wish user to
	 * manually select location/language.
	 */
	public function indexAction() {

		$s_language = $this->I_localeService->getUserLocale();

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
