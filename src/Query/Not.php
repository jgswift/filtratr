<?php
namespace filtratr\Query {
    class Not extends Filter {
        
        /**
         * Inverse filter
         * @param mixed $value
         * @return boolean
         */
        public function execute($value) {
            $result = parent::execute($value);
            
            if($result === false || $result === null) {
                return $value;
            }
            
            return false;
        }
    }
}