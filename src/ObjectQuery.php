<?php
namespace filtratr {
    class ObjectQuery extends Query {
        /**
         * Locally store object
         * @var mixed
         */
        protected $subject;
        
        /**
         * Default ObjectQuery constructor
         * @param mixed $subject
         * @throws \InvalidArgumentException
         */
        function __construct($subject = null) {
            if(!is_object($subject) && !is_null($subject)) {
                throw new \InvalidArgumentException('ObjectQuery subject must be an object');
            }
            
            $this->subject = $subject;
        }
        
        /**
         * Execution method, with different result handling
         * @param mixed $value
         * @return mixed
         */
        public function execute(&$value = null) {
            if(is_null($value)) {
                if(empty($this->subject)) {
                    return $value;
                }
                $value = $this->subject;
            }
            
            $original = (array)$value;
            $data = (array)$value;
            $result = parent::execute($data);
            
            foreach($original as $key => $val) {
                if(!isset($result[$key])) {
                    unset($value->$key);
                } elseif($result[$key] !== $value->$key) {
                    $value->$key = $result[$key];
                }
            }
            
            return $value;
        }
    }
}