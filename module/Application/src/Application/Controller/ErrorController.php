<?php

/**
 * MV Labs ZF2 Skeleton application default error controller
 *
 * Used to handle redirects in the case of an error
 *
 * @copyright Copyright (c) 2010-2013 MV Labs (http://www.mvlabs.it)
 * @link      http://github.com/mvlabs/MvlabsZendSkeletonApplication
 * @license   Please view the LICENSE file that was distributed with this source code
 * @author    Steve Maraspin <steve@mvlabs.it>
 * @package   MvlabsZendSkeletonApplication
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Module;

class ErrorController extends AbstractActionController
{

	public function indexAction() {

		$this->getResponse()->setStatusCode(500);

		$I_vm = new ViewModel();
		$I_vm->setTemplate('error/index');

		return $I_vm;

    }

}
