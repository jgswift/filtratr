<?php
namespace persistr\Tests\Mock {
    use persistr;
    
    class MockModel implements persistr\Interfaces\Model {
        private $className;
        private static $registry;
        
        function __construct($className) {
            $this->className = $className;
            if(empty(self::$registry)) {
                self::$registry = new persistr\Object\Registry($this,'persistr\Tests\Mock\MockPersistedObject');
            }
        }
        
        public function getClassName() {
            return $this->className;
        }

        public function getRegistry() {
            return self::$registry;
        }
        
        public function bind($attribute, callable $callable=null) {
            persistr\Object\Binding\Registry::bind(self::$registry->getTypeName(), $attribute, $callable);
            return $this;
        }
        
        public function bindTo($object,$attribute,callable $callable=null) {
            persistr\Object\Binding\Registry::bindTo($object, $attribute, $callable);
            return $this;
        }
    }
}