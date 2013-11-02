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
    public function createService(ServiceLocatorInterface $serviceLocator) {

    	$am_config = $serviceLocator->getServiceLocator()->get('config');
        $I_translator = $serviceLocator->getServiceLocator()->get('translator');

        $am_transParams = array('translator' => $I_translator, 'languages' => $am_config['mvlabs_environment']['locale']);

        return new LanguageController($am_transParams);

    }
}
