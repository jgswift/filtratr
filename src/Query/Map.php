<?php
namespace filtratr\Query {
    class Map extends AbstractStatement {
        
        /**
         * Nulls value
         * @param mixed $value
         * @return null
         */
        protected function nuller($value) {
            return null;
        }
        
        /**
         * Nulls empty values
         * @param mixed $value
         * @return mixed|null
         */
        protected function empty_nuller($value) {
            if(empty($value)) {
                return null;
            }
            
            return $value;
        }
        
        /**
         * Converts string to ordinal representation
         * @param string $value
         * @return string
         */
        protected function ordinal($value) {
            $ends = ['th','st','nd','rd','th','th','th','th','th','th'];
            if (($value %100) >= 11 && 
                ($value%100) <= 13) {
               return $value.'th';
            }
            
            return $value.$ends[$value % 10];
        }
        
        /**
         * Unserializes string data
         * @param mixed $value
         * @return string
         */
        protected function unserializeString($value) {
            if(!is_string($value)) {
                return [];
            }
            
            $value_strings = explode(';', $value);
            $values = [];
            foreach($value_strings as $value_s) {
                $items = explode(':', $value_s);
                if(count($items) == 2) {
                    list($name, $value) = $items;
                    $values[$name] = $value;
                }
            }
            return $values;
        }
        
        /**
         * Serializes array data using colon and semicolon delimiters
         * @param type $value
         * @return string
         */
        protected function serializeArray($value) {
            $array = (array)$value;
            $string = '';
            
            foreach($array as $key => $v) {
                $string .= (string)$key.':'.(string)$v.';';
            }
            
            return $string;
        }
    }
}
