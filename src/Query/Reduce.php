<?php
namespace filtratr\Query {
    use filtratr\Interfaces\AggregateStatement;
    
    class Reduce extends Map implements AggregateStatement {
        /**
         * Locally store last value
         * @var mixed
         */
        private $lastValue = null;
        
        /**
         * Performs reduce
         * @param mixed $value
         * @param mixed $callable
         * @return mixed
         */
        protected function call($value,$callable) {
            if(is_null($this->lastValue)) {
                return $this->lastValue = $value;
            }
            
            $args = [$value, $this->lastValue];
            if((is_array($callable) && 
                array_key_exists(1,$callable) &&
                array_key_exists($name = $callable[1], $this->arguments)) ||
               (is_scalar($callable) &&
                array_key_exists($name = $callable, $this->arguments))) {
                $args = array_merge($args, $this->arguments[$name]);
            }
            
            return $this->lastValue = call_user_func_array($callable, $args);
        }
    }
}
