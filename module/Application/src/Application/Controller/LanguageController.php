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

	protected $I_translator;

	protected $am_languageConf;

	public function __construct(array $am_translationParams) {
		$this->I_translator = $am_translationParams['translator'];
		$this->am_languageConf = $am_translationParams['languages'];
	}

	public function indexAction() {

		$s_language = $this->am_languageConf['default'];
		$s_accepted = strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']);

		if (array_key_exists(substr($s_accepted, -2), $this->am_languageConf['available'])) {

			// Is there a specific country version available (IE US)?
			$s_language = $this->am_languageConf['available'][substr($s_accepted, -2)];

		} else {

			// Is there a generic language version available (IE GB)?
			$s_userLocale = substr($s_accepted,0,2);
			if (array_key_exists($s_userLocale, $this->am_languageConf['available'])) {
				$s_language = $s_userLocale;
			}

		}

		return $this->redirect()->toRoute("locale",array("locale"=>$s_language));

    }

}
