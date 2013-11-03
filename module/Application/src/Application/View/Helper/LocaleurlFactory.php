<?php

namespace Application\View\Helper;
use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

/**
 * Prepares an URL, keeping current language
 *
 * @author Steve
 */
class LocaleurlFactory implements FactoryInterface {

	public function createService(ServiceLocatorInterface $I_serviceLocator) {

		$I_sl = $I_serviceLocator->getServiceLocator();

		$I_router = $I_sl->get('router');
		$I_localeService = $I_sl->get('localeService');

		return new Localeurl($I_router, $I_localeService);

	}

}
