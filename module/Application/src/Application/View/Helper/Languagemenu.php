<?php
namespace Application\View\Helper;
use Zend\View\Helper\AbstractHelper;

/**
 * Returns I18N menu of all available languages
 *
 * @author Steve
 */
class Languagemenu extends AbstractHelper {

	private $I_router;

	private $I_localeService;

	public function __construct($I_localeService) {
		$this->I_localeService = $I_localeService;
	}


    public function __invoke($s_cssClass = '', $b_showCurrent = true) {

    	$s_result = '<ul' . (!empty($s_cssClass)?' class="' . $s_cssClass . '"':'') . '>';

    	foreach ($this->I_localeService->getTranslatedURL() as $s_key => $am_info) {

    		if ($s_key != $this->I_localeService->getCurrentLocale()) {
    			$s_result .= '<li><a href="'.$am_info['url'].'">'.$am_info['name'].'</a></li>';
    		} else {
    			$s_result .= '<li class="active"><a>' . $am_info['name'] . '</a></li>';
    		}

    	}

    	$s_result .= '</ul>';

    	return $s_result;

    }

}
