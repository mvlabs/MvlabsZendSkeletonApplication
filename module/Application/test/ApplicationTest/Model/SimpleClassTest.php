<?php

/**
 * Simple unit test class for model item
 *
 */
namespace ApplicationTest\Model;

use ApplicationTest\Bootstrap;
use Application\Model\SimpleClass;
use PHPUnit_Framework_TestCase;

class SimpleClassTest extends \PHPUnit_Framework_TestCase {
	
    public function setUp() {
    	parent::setUp();
    }
    
    /**
     * Verifies that right value is returned
     */
    public function testItemValue() {
    	$I_model = new \Application\Model\SimpleClass();
    	$this->assertEquals(10, $I_model->getValue());
    }
    
        
}
