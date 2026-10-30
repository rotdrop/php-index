<?php

namespace rotdrop\index\test;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use rotdrop\index\Iterator\IteratorUtil;
use rotdrop\index\Range;
use rotdrop\index\test\Generator\FixedSizeIndexGenerator;
use rotdrop\index\test\Generator\IndexGenerator;


/**
 * Tests searching a range
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license WTFPL
 */
class SearchRangeTest extends TestCase
{
    
    /**
     */
    #[DataProvider('provideTestAllValidRanges')]
    public function testAllValidRanges(IndexGenerator $generator)
    {
        $index = $generator->getIndex();
        for ($length = 0; $length <= $generator->getIndexLength(); $length++) {
            for ($min = $generator->getMinimum(); $min + $length <= $generator->getMaximum(); $min++) {
                $range = new Range($min, $min + $length);
                $range->setInclusive(true);
                
                $foundKeys = IteratorUtil::toKeysArray($index->searchRange($range));
                
                $expectedKeys = array();
                for ($key = $range->getMin(); $key <= $range->getMax(); $key++) {
                    $expectedKeys[] = $key;
                    
                }
                
                $this->assertEquals(
                    $expectedKeys,
                    $foundKeys,
                    "failed range[{$range->getMin()}, {$range->getMax()}] for index[{$generator->getMinimum()}, {$generator->getMaximum()}]"
                );
            }
        }
        $this->addToAssertionCount(1);
    }
    
    /**
     * Test cases for testAllValidRanges()
     */
    public static function provideTestAllValidRanges()
    {
        $cases = array();
        
        $generator = new FixedSizeIndexGenerator();
        $generator->setIndexLength(0);
        $cases[] = array($generator);
        
        $generator = new FixedSizeIndexGenerator();
        $generator->setIndexLength(1);
        $cases[] = array($generator);
        
        $generator = new FixedSizeIndexGenerator();
        $generator->setIndexLength(10);
        $cases[] = array($generator);
        
        $generator = new FixedSizeIndexGenerator();
        $generator->setIndexLength(100);
        $cases[] = array($generator);
        
        $generator = new FixedSizeIndexGenerator();
        $generator->setIndexLength(200);
        $cases[] = array($generator);
        
        return $cases;
    }
}
