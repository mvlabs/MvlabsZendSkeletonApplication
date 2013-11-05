<?php
namespace Application\Controller;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class IndexControllerFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     *
     * @return \Contents\Controller\IndexController;
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {

    	$am_config = $serviceLocator->getServiceLocator()->get('config');
        $I_translator = $serviceLocator->getServiceLocator()->get('translator');

        $am_transParams = array('translator' => $I_translator, 'defaultLanguage' => );

        return new IndexController($am_transParams);

    }
}
