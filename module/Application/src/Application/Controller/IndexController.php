<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController {
    
	protected $I_model;
	
	public function indexAction() {
        return new ViewModel();
    }
    
    public function paramAction() {
    	$m_value = $this->getRequest()->getQuery('name', null);
    	return new ViewModel(array('user_name' => $m_value));
    }
    
   public function modelAction() {
   		$this->I_model = new \Application\Model\SimpleClass();
    	return new ViewModel(array('iterations' => $this->I_model->getValue()));
    }
    
}
