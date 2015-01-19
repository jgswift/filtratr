<?php
namespace filtratr\Tests {
    /**
    * Base filtratr test case class
    * Class filtratrTestCase
    * @package filtratr
    */
    class ObjectFiltrateTest extends FiltratorTest {
        protected $filtrater;
        
        /**
        * Perform setUp tasks
        */
        protected function setUp()
        {
            $this->filtrater = new \filtratr\ObjectQuery();
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