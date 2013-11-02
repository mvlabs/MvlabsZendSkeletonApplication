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

        $I_translator = $serviceLocator->getServiceLocator()->get('translator');
        return new IndexController($I_translator);

    }
}
