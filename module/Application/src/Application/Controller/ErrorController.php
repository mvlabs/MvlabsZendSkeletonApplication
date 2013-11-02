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
use Zend\Mvc\MvcEvent;
use Application\Module;

class ErrorController extends AbstractActionController
{

	public function indexAction() {

		// This can be intercepted for logging purposes, for instance...
		$this->getEventManager()->trigger('app_issue', $this, array('message' => 'A critical Error Has Occurred'));

		// throw new \RuntimeException('Error page has been reached. Most likely a fatal error has previously occurred');
		$response = $this->getResponse();
		$response->setStatusCode(500);
		$response->setContent("A critical Error has Occurred");
		return $response;

    }

}
