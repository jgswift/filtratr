<?php
namespace filtratr {
    class ArrayQuery extends Query {
        /**
         * Local subject storage
         * @var array
         */
        protected $subject;
        
        /**
         * Default ArrayQuery constructor
         * @param array $subject
         * @throws \InvalidArgumentException
         */
        function __construct(array $subject = null) {
            if(!is_array($subject) && !is_null($subject)) {
                throw new \InvalidArgumentException('ArrayQuery subject must be an object');
            }
            
            $this->subject = $subject;
        }
        
        /**
         * Execution method
         * Bypasses constructor subject if value provided
         * @param mixed $value
         * @return array
         */
        public function execute($value = null) {
            if(is_null($value)) {
                if(empty($this->subject)) {
                    return $value;
                }
                
                $value = $this->subject;
            }
            
            return parent::execute($value);
        }
    }
}