<?php
namespace filtratr\Tests {
    abstract class FiltratorTest extends filtratrTestCase {
        
        
        protected function populateSubject(array $data) {
            foreach($data as $key => $value) {
                if(is_object($this->subject)) {
                    $this->subject->$key = $value;
                } elseif(is_array($this->subject)) {
                    $this->subject[$key] = $value;
                }
            }
            
            return $this->subject;
        }
        
        protected function resultTest($data, $key = 'test') {
            $filtrater = $this->filtrater;
            $result = $filtrater($data);
            
            
            
            if(is_array($this->subject)) {
                if(empty($result)) {
                    return $result;
                }

                return $result[$key];
            } elseif(is_object($this->subject)) {
                if(!isset($result->$key)) {
                    return null;
                }
                
                return $result->$key;
            }
        }
        
        function testStringMap() {
            $filtrater = $this->filtrater;
            
            $filtrater->map('test', 'trim');
            
            $data = $this->populateSubject([
                'test' => ' something '
            ]);
            
            $this->assertEquals('something', $this->resultTest($data));
        }
        
        function testMultipleStringMap() {
            $filtrater = $this->filtrater;
            
            $filtrater->map('test', 'trim')->map('test', 'strtoupper');
            
            $data = $this->populateSubject([
                'test' => ' something '
            ]);
            
            $this->assertEquals('SOMETHING', $this->resultTest($data));
        }
        
        function testMultipleStringMapFromArray() {
            $filtrater = $this->filtrater;
            
            $filtrater->map('test', [
                'trim',
                'strtoupper'
            ]);
            
            $data = $this->populateSubject([
                'test' => ' something '
            ]);
            
            $this->assertEquals('SOMETHING', $this->resultTest($data));
        }
        
        function testMultipleRootStringMapFromArray() {
            $filtrater = $this->filtrater;
            
            $filtrater->map([
                'trim',
                'strtoupper'
            ]);
            
            $data = $this->populateSubject([
                'test' => ' something '
            ]);
            
            $this->assertEquals('SOMETHING', $this->resultTest($data));
        }
        
        function testMultipleRootStringMapFromOperator() {
            $filtrater = $this->filtrater;
            
            $filtrater->map('trim | strtoupper');
            
            $data = $this->populateSubject([
                'test' => ' something '
            ]);
            
            $this->assertEquals('SOMETHING', $this->resultTest($data));
        }
        
        function testRootStringMapWithArguments() {
            $filtrater = $this->filtrater;
            
            $filtrater->map('substr(0,4)');
            
            $data = $this->populateSubject([
                'test' => 'something'
            ]);
            
            $this->assertEquals('some', $this->resultTest($data));
        }
        
        function testRootStringMapWithPHPArguments() {
            $filtrater = $this->filtrater;
            
            $filtrater->map('substr',[0,4]);
            
            $data = $this->populateSubject([
                'test' => 'something'
            ]);
            
            $this->assertEquals('some', $this->resultTest($data));
        }
        
        function testRootStringMapNuller() {
            $filtrater = $this->filtrater;
            
            $filtrater->map('nuller');
            
            $data = $this->populateSubject([
                'test' => 'something'
            ]);
            
            $this->assertEquals(null, $this->resultTest($data));
        }
        
        function testRootStringMapEmptyNuller() {
            $filtrater = $this->filtrater;
            
            $filtrater->map('empty_nuller');
            
            $data = $this->populateSubject([
                'test' => '0'
            ]);
            
            $this->assertTrue(empty($this->resultTest($data)));
        }
        
        function testStringFilterEmail() {
            $filtrater = $this->filtrater;
            
            $filtrater->filter('test','filter_var', [FILTER_VALIDATE_EMAIL]);
            
            $data = $this->populateSubject([
                'test' => 'bob@example.com'
            ]);
            
            $this->assertEquals('bob@example.com', $this->resultTest($data));
        }
        
        function testRootStringFilterEmail() {
            $filtrater = $this->filtrater;
            
            $filtrater->filter('filter_var', [FILTER_VALIDATE_EMAIL]);
            
            $data = $this->populateSubject([
                'test' => 'bob@example.com'
            ]);
            
            $this->assertEquals('bob@example.com', $this->resultTest($data));
        }
        
        function testStringFilterEquals() {
            $filtrater = $this->filtrater;
            
            $filtrater->filter('test','equals(bob@example.com)');
            
            $data = $this->populateSubject([
                'test' => 'bob@example.com'
            ]);
            
            $this->assertEquals('bob@example.com', $this->resultTest($data));
        }
        
        function testRootStringFilterEquals() {
            $filtrater = $this->filtrater;
            
            $filtrater->filter('equals(bob@example.com)');
            
            $data = $this->populateSubject([
                'test' => 'bob@example.com'
            ]);
            
            $this->assertEquals('bob@example.com', $this->resultTest($data));
        }
    }
}