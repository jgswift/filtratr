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
            
            $args = $this->parseCallArguments([$value, $this->lastValue], $callable);
            
            return $this->lastValue = call_user_func_array($callable, $args);
        }
    }
}
