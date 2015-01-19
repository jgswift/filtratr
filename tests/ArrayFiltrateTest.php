<?php
namespace filtratr\Tests {
    /**
    * Base filtratr test case class
    * Class filtratrTestCase
    * @package filtratr
    */
    class ArrayFiltrateTest extends FiltratorTest {
        protected $filtrater;
        
        /**
        * Perform setUp tasks
        */
        protected function setUp()
        {
            $this->filtrater = new \filtratr\ArrayQuery();
            parent::setUp();
        }
        
        

        /**
         * Perform clean up / tear down tasks
         */
        protected function tearDown()
        {
        }
    }
}