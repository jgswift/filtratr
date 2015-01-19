<?php
namespace filtratr;

/**
 * Creates filtratr query
 * @param mixed $value
 * @return \filtratr\ArrayQuery|\filtratr\ObjectQuery
 */
function apply($value = null) {
    if(is_object($value)) {
        return new ObjectQuery($value);
    } elseif(is_array($value)) {
        return new ArrayQuery($value);
    }
}

/**
 * Creates filtratr query with additional array conversion
 * @param mixed $value
 * @return \filtratr\ArrayQuery|\filtratr\ObjectQuery
 */
function with($value = null) {
    if(is_object($value)) {
        return new ObjectQuery($value);
    } elseif(!is_array($value)) {
        $value = [$value];
    }
    
    return new ArrayQuery($value);
}

/**
 * Helper function to create filter query
 * @param mixed $callable
 * @param array $data
 * @return \filtratr\ArrayQuery|\filtratr\ObjectQuery
 */
function filter($callable, array $data) {
    $query = apply($data)->filter($callable);
    
    return $query();
}

/**
 * Helper function to create default query
 * @param mixed $callable
 * @param array $data
 * @return \filtratr\ArrayQuery|\filtratr\ObjectQuery
 */
function is($callable, array $data) {
    return filter($callable, $data);
}

/**
 * Helper function to create map query
 * @param mixed $callable
 * @param array $data
 * @return \filtratr\ArrayQuery|\filtratr\ObjectQuery
 */
function map($callable, array $data) {
    $query = apply($data)->map($callable);
    
    return $query();
}

/**
 * @see \filtratr\map
 * @param mixed $callable
 * @param array $data
 * @return \filtratr\ArrayQuery|\filtratr\ObjectQuery
 */
function to($callable, array $data) {
    return map($callable, $data);
}

/**
 * Helper function to create reduce query
 * @param mixed $callable
 * @param array $data
 * @return \filtratr\ArrayQuery|\filtratr\ObjectQuery
 */
function reduce($callable, array $data) {
    $query = apply($data)->reduce($callable);
    
    return $query();
}

function extend($namespace) {
    \qtil\Chain\ClassRegistry::addNamespace('filtratr\Query', $namespace);
}