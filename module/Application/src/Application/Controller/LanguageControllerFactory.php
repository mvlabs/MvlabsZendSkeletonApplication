<?php
namespace Application\Controller;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LanguageControllerFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     *
     * @return \Contents\Controller\IndexController;
     */
    public function createService(ServiceLocatorInterface $I_controllerSL) {

    	$I_localeService = $I_controllerSL->getServiceLocator()->get('localeService');
        return new LanguageController($I_localeService);

    }
}
