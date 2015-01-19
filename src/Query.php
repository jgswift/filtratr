<?php
namespace filtratr {
    use qtil;
    use qinq;
    use qtil\Chain\Registry;
    
    class Query implements Interfaces\FiltrateQuery {
        use qtil\Chain, qtil\Executable;
        
        /**
         * Default query execution method
         * @param mixed $value
         * @return array
         */
        public function execute(&$value = null) {
            if(!is_array($value)) {
                $value = [$value];
            }
            
            $data = new qinq\Collection($value);
            $linkProperty = Registry::getLinkProperty($this);
            
            $filters = $this->$linkProperty;
            
            if(!empty($filters)) {
                foreach($filters as $filter) {
                    $this->filter($filter, $data);
                }
            }
            
            return $data->toArray();
        }
        
        public function extend($namespace) {
            $this->registerNamespace($namespace);
            return $this;
        }
        
        /**
         * Performs selector query if necessary
         * @param mixed $filter
         * @param mixed $data
         */
        protected function filter($filter, &$data) {
            $name = $filter->getName();

            if(empty($name)) {
                $value = $data->selector($name)->toArray();
            } else {
                $value = $data->toArray();
            }

            $this->iterate($filter, $value, $data);
        }
        
        /**
         * Iterates and filters values
         * @param mixed $filter
         * @param mixed $value
         * @param mixed $data
         */
        private function iterate($filter, $value, &$data) {
            $keys = array_keys($value);
            $firstKey = $keys[0];
            
            foreach($value as $k => $val) {
                if($filter instanceof Interfaces\QueryStatement) {
                    $this->result($filter, $filter->execute($val), $data, $k, $firstKey);
                } elseif(is_callable($filter)) {
                    $this->result($filter, $filter($val), $data, $k, $firstKey);
                }
            }
        }
        
        /**
         * Handles filter result
         * @param mixed $filter
         * @param mixed $result
         * @param mixed $data
         * @param string|int $key
         * @param string|int $firstKey
         */
        private function result($filter, $result, &$data, $key, $firstKey = null) {
            if($filter instanceof Query\Filter) {
                if(($result === null ||
                    $result === false)) {
                    unset($data[$key]);
                }
            } elseif($filter instanceof Interfaces\AggregateStatement) {
                if($key !== $firstKey) {
                    unset($data[$key]);
                }

                $data[$firstKey] = $result;
            } else {
                $data[$key] = $result;
            }
        }
    }
}