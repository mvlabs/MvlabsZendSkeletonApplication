<?php

// Documentation here:
// http://framework.zend.com/manual/2.1/en/modules/zend.test.phpunit.html 


namespace ApplicationTest\Controller;

use Zend\Mvc\Application;

use ApplicationTest\Bootstrap;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use Application\Controller\IndexController;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use PHPUnit_Framework_TestCase;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;


class IndexControllerTest extends AbstractHttpControllerTestCase {

	protected $controller;
    protected $request;
    protected $response;
    protected $routeMatch;
    protected $event;

    
    public function setUp() {
    	$this->setApplicationConfig(include __DIR__ . '/../../TestConfig.php.dist');
    	parent::setUp();
    }

    
    /**
     * Verifies that index can be accessed
     */
    public function testIndexAccess() {
    	$this->dispatch('/');
    	$this->assertResponseStatusCode(200);
    	$this->assertModuleName('application');
    	$this->assertControllerName('application\controller\index');
    	$this->assertControllerClass('IndexController');
    	$this->assertMatchedRouteName('home');
    }
    
    
    /**
     * Verifies that param works as expected
     */
    public function testParamAction() {
    	
    	$s_param = 'Steve';
    	
    	$this->dispatch('/param', 'GET', array('name' => $s_param));
    	
    	// Assertion through CSS Query
    	$this->assertQueryContentContains("html body div.container div.hero-unit h1 span.zf-green", $s_param);
    	
    }
    
    
    /**
     * Tests end to end function
     */
    public function testModelInteractionAction() {
    	
    	$I_model = new \Application\Model\SimpleClass();

    	// Do example creating new route w/ same name...
    	$this->dispatch('/model');
    	 
    	// Counting elements
    	$this->assertQueryCount("html body div.container div.row div.span4 table tbody tr td", $I_model->getValue());
    	
    	// XPath to pick Nth element (no support for CSS selectors)
    	$this->assertXPathQueryContentContains('/html/body/div[2]/div[2]/div/table/tbody/tr['.
    			                               $I_model->getValue().']/td', 
    			                               $I_model->getValue()
    			                              );
    }
    
    
    
    
}
