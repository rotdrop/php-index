<?php

namespace malkusch\index\test;

use PHPUnit\Framework\Attributes\DataProvider;
use malkusch\index\Index;
use malkusch\index\test\Generator\FixedSizeIndexGenerator;
use malkusch\index\test\Generator\IndexGenerator;


/**
 * Tests an index
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license WTFPL
 */
class IndexTest extends AbstractTest
{

    /**
     * Tests searching
     *
     * @param IndexGenerator $generator Index generator
     *
     * @return void
     */
    #[DataProvider('provideTestSearch')]
    public function testSearch(IndexGenerator $generator)
    {
        $index = $generator->getIndex();
        foreach ($generator->getKeys() as $key) {
            $result = $index->search($key);
            $this->assertNotNull(
                $result,
                "key: $key, max: {$generator->getMaximum()},"
                . " step: {$generator->getStepSize()}"
                . " length: {$generator->getIndexLength()}"
            );
            $expected = preg_quote($generator->generateData($key));
            $this->assertMatchesRegularExpression("/$expected/", $result->getData());
            
        }
    }
    
    /**
     * Test cases for testSearch()
     *
     * @return void
     */
    public static function provideTestSearch()
    {
        $cases = array();
        
        $lengths = array(
            1,
            2,
            10,
            IndexGenerator::getBlockSize(),
        );
        
        $steps = array(
            1,
            3,
            IndexGenerator::getBlockSize()
        );
        
        foreach ($lengths as $length) {
            foreach ($steps as $step) {
                $generator = new FixedSizeIndexGenerator();
                $generator->setIndexLength($length);
                $generator->setStepSize($step);
                $cases[] = array($generator);
                
            }
        }
        
        return $cases;
    }
    
    /**
     * Tests that failing terminates
     *
     * @return void
     */
    #[DataProvider('provideTestFailSearch')]
    public function testFailSearch(Index $index, $key)
    {
        $this->assertNull($index->search($key));
    }

    /**
     * Test cases for testFailSearch()
     *
     * @return void
     */
    public static function provideTestFailSearch()
    {
        $cases  = array();
        
        // different sizes
        $lengths = array(
            0,
            1,
            10,
            IndexGenerator::getBlockSize() - 1,
            IndexGenerator::getBlockSize(),
            IndexGenerator::getBlockSize() + 1,
            IndexGenerator::getBlockSize() * 4
        );
        
        foreach ($lengths as $length) {
            // Fail searching for MIN(index) - 1
            $generator = new FixedSizeIndexGenerator();
            $generator->setIndexLength($length);
            $generator->getIndex();
            $cases[] = array(
                $generator->getIndex(),
                $generator->getMinimum() - 1
            );
            
            // Fail searching for MAX(index) + 1
            $generator = new FixedSizeIndexGenerator();
            $generator->setIndexLength($length);
            $generator->getIndex();
            $cases[] = array(
                $generator->getIndex(),
                $generator->getMaximum() + 1
            );
            
            // Fail searching for any missing key inside the index range
            $generator = new FixedSizeIndexGenerator();
            $generator->setIndexLength($length);
            $generator->setStepSize(2);
            $generator->getIndex();
            $cases[] = array(
                $generator->getIndex(),
                $generator->getMaximum() / 2 + 0.1
            );
            
        }
        
        return $cases;
    }
}
