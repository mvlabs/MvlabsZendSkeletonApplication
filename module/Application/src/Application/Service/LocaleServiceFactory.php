<?php
namespace Application\Service;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

/**
 * Creates locale service
 *
 */
class LocaleServiceFactory implements FactoryInterface {

	public function createService(ServiceLocatorInterface $I_serviceLocator) {

		$am_config = $I_serviceLocator->get('config');
		$am_localeConf = $am_config['mvlabs_environment']['locale'];

		$I_router = $I_serviceLocator->get('Router');
		$I_translator = $I_serviceLocator->get('translator');

		$I_routeMatch = $I_serviceLocator->get('Application')->getMvcEvent()->getRouteMatch();

		return new LocaleService($I_router, $I_routeMatch, $I_translator, $am_localeConf);

	}

}
