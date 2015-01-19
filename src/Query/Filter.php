<?php
namespace filtratr\Query {
    class Filter extends AbstractStatement {
        
        /**
         * Converts statement execution to boolean
         * @param mixed $value
         * @return boolean
         */
        public function execute($value) {
            $value = parent::execute($value);
            
            if(!$value) {
                return false;
            }
            
            return $value;
        }
        
        /**
         * Helper method to perform filter_var
         * @see filter_var
         * @param mixed $value
         * @param int $filter
         * @param array|null $options
         * @return mixed
         */
        protected function validate($value, $filter = FILTER_DEFAULT, $options = null) {
            return filter_var($value, $filter, $options);
        }
        
        /**
         * Check if string ends with comparator
         * @param string $haystack
         * @param string $needle
         * @return boolean
         */
        protected function endsWith($haystack, $needle) {
            return $needle === '' || strrpos($haystack, $needle, -strlen($haystack)) !== false;
        }
        
        /**
         * Check if string starts with comparator
         * @param string $haystack
         * @param string $needle
         * @return boolean
         */
        protected function startsWith($haystack, $needle) {
            return $needle === '' || strpos($haystack, $needle, strlen($haystack) - strlen($needle)) !== false;
        }
        
        /**
         * Check if string is serialized data
         * @param string $value
         * @return boolean
         */
        protected function serial($value) {
            if(!is_string($value)) {
                return null;
            }
            
            return (unserialize($value) !== false);
        }
        
        /**
         * Check if comparator equals value
         * @param mixed $value
         * @param mixed $compare
         * @return boolean
         */
        protected function equals($value, $compare) {
            return $value == $compare;
        }
        
        /**
         * Check if comparator identical to value
         * @param mixed $value
         * @param mixed $compare
         * @return boolean
         */
        protected function identical($value, $compare) {
            return $value === $compare;
        }
        
        /**
         * Check if needle exists in haystack
         * @param mixed $haystack
         * @param mixed $needle
         * @return boolean
         */
        protected function contains($haystack, $needle) {
            if(is_string($haystack)) {
                return strpos($haystack, $needle) !== false;
            } elseif(is_array($haystack)) {
                return in_array($needle, $haystack);
            }
        }
        
        /**
         * Helper method to handle string comparisons
         * @param mixed $value
         * @return mixed
         */
        private function parseValue($value) {
            if(!is_numeric($value) && is_string($value)) {
                if(($len = strlen($value)) > 1) {
                    $value = $len;
                } else {
                    $value = ord($value);
                }
            }
            
            return $value;
        }
        
        /**
         * Performs greaterthan predicate
         * @param mixed $value
         * @param mixed $compare
         * @return boolean
         */
        protected function greaterThan($value, $compare) {
            $value = $this->parseValue($value);
            $compare = $this->parseValue($compare);
            
            return $value > $compare;
        }
        
        /**
         * Performs greaterthanorequals predicate
         * @param mixed $value
         * @param mixed $compare
         * @return boolean
         */
        protected function greaterThanOrEquals($value, $compare) {
            $value = $this->parseValue($value);
            $compare = $this->parseValue($compare);
            
            return $value >= $compare;
        }
        
        /**
         * Perform lessthan predicate
         * @param mixed $value
         * @param mixed $compare
         * @return boolean
         */
        protected function lessThan($value, $compare) {
            $value = $this->parseValue($value);
            $compare = $this->parseValue($compare);
            
            return $value < $compare;
        }
        
        /**
         * Perform lessthanorequals predicate
         * @param mixed $value
         * @param mixed $compare
         * @return boolean
         */
        protected function lessThanOrEquals($value, $compare) {
            $value = $this->parseValue($value);
            $compare = $this->parseValue($compare);
            
            return $value <= $compare;
        }
    }
}