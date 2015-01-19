filtratr
====
filtering & mapping component 

[![Build Status](https://travis-ci.org/jgswift/filtratr.png?branch=master)](https://travis-ci.org/jgswift/filtratr)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jgswift/filtratr/badges/quality-score.png?s=4c1433cd4686440e0a8a2eb2a0d3aac9d2a62337)](https://scrutinizer-ci.com/g/jgswift/filtratr/)
[![Latest Stable Version](https://poser.pugx.org/jgswift/filtratr/v/stable.svg)](https://packagist.org/packages/jgswift/filtratr)
[![License](https://poser.pugx.org/jgswift/filtratr/license.svg)](https://packagist.org/packages/jgswift/filtratr)

## Installation

Install via cli using [composer](https://getcomposer.org/):
```sh
php composer.phar require jgswift/filtratr:0.1.*
```

Install via composer.json using [composer](https://getcomposer.org/):
```json
{
    "require": {
        "jgswift/filtratr": "0.1.*"
    }
}
```

## Description

filtratr is an extensible lightweight filtering component that facilitates filtering and mapping of data structures.  filtratr provides a functional programming interface and a fluid interface.

## Dependency

* php 5.5+
* [jgswift/qinq](http://github.com/jgswift/qinq) - quasi integrated query 

## Usage

### Filter

Includes or excludes data from result depending on provided predicate.  Predicates may be implemented functionally or through strings.

```php
$filter = filtratr\with([
    'foo' => 'bar',
    'boo' => 'baz'
])->filter('equals',['bar']);

var_dump($filter()); // ['foo' => 'bar']
```

#### Is & Not

```Is``` and ```Not``` are an inverse subset of ```Filter```.  The ```Is``` statement is simply an alias of ```Filter``` to minimize conceptual overhead.  The ```Not``` filter is the inverse version of ```Is``` and reverses whatever filtering is applied.

```php
$filter = filtratr\with([
    'foo' => 'bar',
    'boo' => 'baz'
])->is('equals',['bar']);

var_dump($filter()); // ['foo' => 'bar']
```

Conversely...

```php
$filter = filtratr\with([
    'foo' => 'bar',
    'boo' => 'baz'
])->not('equals',['bar']);

var_dump($filter()); // ['boo' => 'baz']
```

#### Using callbacks

Custom callbacks may easily be applied to every member of an array or object.  Filter callbacks that return true or any values other than false or null will be kept in the subject array/object.

```php
$filter = filtratr\with([
    'foo' => 'bar',
    'boo' => 'baz'
])->is(function($val) {
    if($val === 'bar') {
        return true;
    }
});

var_dump($filter()); // ['foo' => 'bar']
```

#### Using named callbacks

Specifying a key name before the callback will then only apply the callback to items that match that key and exclude processing anything else.  Non-matching keys will be retained by default.

```php
$filter = filtratr\with([
    'foo' => 'bar',
    'bar' => 'baz'
])->is('foo', function($val) {
    if($val === 'bar') {
        return true;
    }
});

var_dump($filter()); // ['foo' => 'bar', 'bar' => 'baz']
```

#### Predicates & Filters
* equals(comparison)
* identical(comparison)
* greaterthan(comparison)
* greaterthanorequals(comparison)
* lessthan(comparison)
* lessthanorequals(comparison)
* validate(filter, options)
* serial

### Map

Map performs a transformation on selected keys/properties using a callback function.

#### Using callbacks

Much like filter, map may receive a callback, optional key pattern, and callback arguments array.  Unlike filter, map is not inclusive and as such map can only effectively result with a transformed value or the original value.

```php
$filter = filtratr\with([
    'foo' => ' bar ',
    'fiz' => ' buz '
])->map(function($val) {
    return trim($val);
});

var_dump($filter()); // ['foo' => 'bar', 'fiz' => 'buz']
```

Callbacks of any type (string, Closures, arrays) may be used.  The following example is functionally identical to the previous

```php
\filtratr\with([
    'foo' => ' bar ',
    'fiz' => ' buz '
])->map('trim');

var_dump($filter()); // ['foo' => 'bar', 'fiz' => 'buz']
```

#### Using named callbacks

```php
$filter = filtratr\with([
    'foo' => ' bar ',
    'bar' => ' baz '
])->map('foo', 'trim');

var_dump($filter()); // ['foo' => 'bar', 'bar' => ' baz ']
```

#### Concatenated Expressions

Mapping may be chained easily using the ```|``` pipe operation.  Method chaining is alternatively an option as well.

```php
$filter = filtratr\with([
    'foo' => ' bar',
    'fiz' => 'buz '
])
->map('trim | strtoupper');

var_dump($filter()); // ['foo' => 'BAR', 'fiz' => 'BUZ']
```

Same method using chaining...

```php
$filter = filtratr\with([
    'foo' => ' bar',
    'fiz' => 'buz '
])
->map('trim')
->map('strtoupper');

var_dump($filter()); // ['foo' => 'BAR', 'fiz' => 'BUZ']
```

#### Predicates & Filters
* contains
* nuller
* empty_nuller
* serializearray(array)
* unserializestring(string)

### Reduce

Like ```array_reduce```, the reduce statement will apply a callback to many items and produce a single result.

```php
$filter = filtratr\with([
    1, 2, 3, 4
])->reduce(function($a,$b) {
    return $a * $b;
});

var_dump($filter()[0]); // 24
```

### Object support

filtratr will technically work on objects however filtratr currently only tests on public properties.  Objects are passed by reference and the query will assume the object is mutable.

```php
class User {
    public $name;

    function __construct($name) {
        $this->name = $name;
    }
}

$user = new User(' john smith ');

$filter = filtratr\with($user)->map('name', 'trim | strtoupper')

var_dump($filter()->name); // "JOHN SMITH"
```

### Extending fluid interface

The extension class...
```php
namespace MyQueryExtension;

class MyExtension extends filtratr\Query\AbstractStatement {
    /* do stuff here */
}
```

Attaching per-filter extensions...
```php
$filter = new filtratr\with([
    'foo' => 'bar'
])
->extend('MyQueryExtension')
->myextension();

var_dump($filter());
```

Attaching globally...
```php
filtratr\extend('MyQueryExtension');

$filter = new filtratr\with([
    'foo' => 'bar'
])
->myextension();

var_dump($filter());

```
