<?php
namespace filtratr\Tests {
    class GlobalTest extends filtratrTestCase {
        function testStringFilterApply() {
            $filter = \filtratr\with([
                'test' => 'foo'
            ])->filter('equals',['foo']);
            
            $this->assertEquals('foo',$filter()['test']);
        }
        
        function testStringFilterFn() {
            $filter = \filtratr\apply([
                'test' => 'foo'
            ])->filter('equals(foo)');
            
            $this->assertEquals('foo',$filter()['test']);
        }
        
        function testArrayReduceFn() {
            $filter = \filtratr\apply([
                1, 2, 3, 4
            ])->reduce(function($a,$b) {
                return $a * $b;
            });
                    
            $result = $filter();
            
            $this->assertEquals(24, $result[0]);
        }
        
        function testExtension() {
            $data = [
                'hello' => 'world'
            ];
            
            $filter = \filtratr\with($data)
            ->extend('filtratr\Tests\QueryExtended')
            ->MyExtension('equals(world)');
            
            $this->assertEquals($data, $filter());
        }
        
        function testStringIdenticalIsAndNot() {
            $filter = \filtratr\apply(['hello'])->not('equals(world)');
            
            $result = $filter();
            
            $this->assertEquals(1,count($result));
            
            $filter = \filtratr\apply(['hello'])->is('equals(world)');
            
            $result = $filter();
            
            $this->assertEquals(0,count($result));
            
            $filter = \filtratr\apply(['hello'])->is('identical(hello)');
            
            $result = $filter();
            
            $this->assertEquals(1,count($result));
        }
        
        function testNumberComparison() {
            $filter = \filtratr\apply([2,0.2])->is('greaterThan(1)');
            
            $result = $filter();
            
            $this->assertEquals(1,count($result));
            
            $filter = \filtratr\apply([1])->is('lessThan(2)');
            
            $result = $filter();
            
            $this->assertEquals(1,count($result));
            
            $filter = \filtratr\apply([2,3])->is('greaterThanOrEquals(2)');
            
            $result = $filter();
            
            $this->assertEquals(2,count($result));
            
            $filter = \filtratr\apply([2,1])->is('lessThanOrEquals(2)');
            
            $result = $filter();
            
            $this->assertEquals(2,count($result));
        }
    }
}