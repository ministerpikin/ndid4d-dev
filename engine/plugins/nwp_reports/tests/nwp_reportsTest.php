<?php

class nwp_reportsTest extends \PHPUnit\Framework\TestCase{
    
    public function testSubtraction() {
        $result = 2 - 1;
        $this->assertEquals(1, $result);
    }
    
    public function testAddition() {
        $result = 2 + 2;
        $this->assertEquals(4, $result);
    }
    
}