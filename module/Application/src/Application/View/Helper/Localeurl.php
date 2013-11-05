<?php
namespace Application\View\Helper;
use Zend\View\Helper\AbstractHelper;

/**
 * Prepares an URL, keeping current language
 *
 * @author Steve
 */
class Localeurl extends AbstractHelper {

	private $I_router;

	private $I_localeService;

	public function __construct($I_router, $I_localeService) {
		$this->I_router = $I_router;
		$this->I_localeService = $I_localeService;
	}

    public function __invoke( $s_routeName, array $am_params = null, array $am_options = null) {

    	// Get current language
    	$s_locale = $this->I_localeService->getCurrentLocale();

    	// If no current language is defined, we fall back to user preference, or default
    	if (empty($s_locale)) {
    		$s_locale = $this->I_localeService->getUserLocale();
    	}

    	// Locale is set to be current one
    	$am_params['locale'] = $s_locale;

    	// Route name is passed as an option before route is prepared
    	$am_options['name'] = $s_routeName;

    	return $this->I_router->assemble(
    			$am_params,
    			$am_options
    	);

    }

}
