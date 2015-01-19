<?php
namespace filtratr\Tests {
    /**
    * Base filtratr test case class
    * Class filtratrTestCase
    * @package filtratr
    */
    abstract class filtratrTestCase extends \PHPUnit_Framework_TestCase {
        protected $filtrater;
        protected $subject;
        
        /**
        * Perform setUp tasks
        */
        protected function setUp()
        {
            $this->subject = $this->createSubject();
        }

        /**
         * Perform clean up / tear down tasks
         */
        protected function tearDown()
        {
        }
        
        protected function createSubject() {
            if($this->filtrater instanceof \filtratr\ArrayQuery) {
                return [];
            } elseif($this->filtrater instanceof \filtratr\ObjectQuery) {
                return new \stdClass();
            }
        }
    }
}