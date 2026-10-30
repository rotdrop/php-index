<?php

namespace rotdrop\index\test;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use rotdrop\index\Range;


/**
 * Test for Range
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license WTFPL
 */
class RangeTest extends TestCase
{
    
    /**
     * Tests that the borders are contained by the range
     * 
     */
    #[DataProvider('provideTestContains')]
    public function testInclusiveContains($min, $max)
    {
        $range = new Range($min, $max);
        $range->setInclusive(true);

        // Min
        $this->assertTrue($range->contains($min));
        $this->assertFalse($range->contains($min - 0.1));
        
        // Max
        $this->assertTrue($range->contains($max));
        $this->assertFalse($range->contains($max + 0.1));
    }
    
    /**
     * Tests that the borders are not contained by the range
     * 
     */
    #[DataProvider('provideTestContains')]
    public function testExclusiveContains($min, $max)
    {
        $range = new Range($min, $max);
        $range->setInclusive(false);

        $this->assertFalse($range->contains($min));
        $this->assertFalse($range->contains($max));
    }
    
    /**
     * Tests some values in the range
     * 
     */
    #[DataProvider('provideTestContains')]
    public function testContains($min, $max)
    {
        $range = new Range($min, $max);

        $step = 0.1;
        for ($i = $min + $step; $i < $max; $i += $step) {
            $this->assertTrue($range->contains($i));
            
        }
        $this->addToAssertionCount(1);
    }
    
    /**
     * Tests Range::setInclusive()
     */
    public function testSetInclusive()
    {
        $range = new Range(0, 0);
        $range->setInclusive(true);
        $this->assertTrue($range->isInclusive());
        
        $range = new Range(0, 0);
        $range->setInclusive(false);
        $this->assertFalse($range->isInclusive());
    }
    
    /**
     * Test cases
     * 
     * @return array 
     */
    public static function provideTestContains()
    {
        return array(
            array(0, 0),
            array(0, 1),
            array(-1, 1),
        );
    }
}
