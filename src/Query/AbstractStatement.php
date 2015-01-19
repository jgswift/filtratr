<?php
namespace filtratr\Query {
    use filtratr\Interfaces\QueryStatement;
    
    abstract class AbstractStatement implements QueryStatement {
        /**
         * Locally store statement name
         * @var string 
         */
        protected $name;
        
        /**
         * Locally store statement callback
         * @var mixed
         */
        protected $callable;
        
        /**
         * Locally store extra callback arguments
         * @var array 
         */
        protected $arguments = [];
        
        /**
         * Default statement constructor
         * @param mixed $name
         * @param mixed $callable
         */
        public function __construct($name, $callable = null) {
            $constructor_args = func_get_args();
            $numArgs = func_num_args();
            if(is_string($parsedName = $this->parseCallable($name))) {
                if(is_callable($parsedName) || method_exists($this,$parsedName)) {
                    $callable = $parsedName;
                }
            } 
            
            if(is_null($callable)) {
                $callable = $name;
                array_shift($constructor_args);
                array_shift($constructor_args);
            } else {
                $this->name = $name;
                array_shift($constructor_args);
            }
            
            if($numArgs > 2 && !is_null($callable)) {
                array_shift($constructor_args);
                array_shift($constructor_args);
            } elseif($numArgs === 2 && $name !== $callable) {
                array_shift($constructor_args);
            } 
            
            $args = [];
            
            if(is_array($callable) && !is_callable($callable)) {
                $this->callable = $callable;
            } elseif(is_string($callable)) {
                if(!empty($constructor_args)) {
                    $args = $constructor_args[0];
                }
                $this->callable = $this->mapCallable($callable, $args);
            } elseif(!is_callable($callable)) {
                $this->callable = [[$this, 'equals']];
                $this->arguments['equals'] = $args;
            } else {
                $this->callable = [$callable];
            }
        }
        
        /**
         * Retrieves statement callback
         * @return mixed
         */
        public function getCallable() {
            return $this->callable;
        }

        /**
         * Retrieves statement name
         * @return string
         */
        public function getName() {
            return $this->name;
        }
        
        /**
         * Maps callable
         * @param string $expression
         * @param mixed $args
         * @return array
         */
        protected function mapCallable($expression, $args = []) {
            return array_map(function($callableName)use($args) {
                $callable = $this->parseCallable($callableName);
                
                if(is_array($args)) {
                   if(isset($this->arguments[$callable])) {
                        $args = array_merge($this->arguments[$callable], $args);
                    }

                    $this->arguments[$callable] = $args; 
                }
                
                return $callable;
            }, explode('|',$expression));
        }
        
        /**
         * Stores mapped callable arguments
         * @param mixed $callableName
         * @return mixed
         */
        protected function parseCallable($callableName) {
            if(!is_string($callableName) || is_callable($callableName)) {
                return $callableName;
            }
            
            $argStart = strpos($callableName,'(');
            if($argStart !== false) {
                $argEnd = strpos($callableName,')');
                $argumentSignature = substr($callableName,$argStart+1,$argEnd-$argStart-1);
                $callableName = substr($callableName,0, $argStart);
                $this->arguments[$callableName] = explode(',', $argumentSignature);
            }
            
            return trim($callableName);
        }
        
        /**
         * Default statement execution method
         * @param mixed $value
         * @return mixed
         */
        public function execute($value) {
            foreach($this->callable as $callable) {
                if(is_string($callable) && 
                   !is_callable($callable) &&
                   method_exists($this, $callable)) {
                    $callable = [$this,$callable];
                }
                
                $value = $this->call($value,$callable);
            }
            
            return $value;
        }
        
        /**
         * Performs callable filtration
         * @param mixed $value
         * @param mixed $callable
         * @return mixed
         */
        protected function call($value,$callable) {
            $args = [$value];
            if((is_array($callable) && 
                array_key_exists(1,$callable) &&
                array_key_exists($name = $callable[1], $this->arguments)) ||
               (is_scalar($callable) &&
                array_key_exists($name = $callable, $this->arguments))) {
                $args = array_merge($args, $this->arguments[$name]);
            }
            
            return call_user_func_array($callable, $args);
        }
    }
}
