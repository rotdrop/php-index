<?php

namespace malkusch\index\test;

use PHPUnit\Framework\Attributes\DataProvider;
use malkusch\index\File\File;
use malkusch\index\FixedSize\FixedSizeIndex;
use malkusch\index\test\Generator\FixedSizeIndexGenerator;


/**
 * Tests the xml index
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license WTFPL
 */
class FixedSizeIndexTest extends AbstractTest
{
    
    /**
     * Tests getLineLength()
     * 
     * @see FixedSizeIndex::getLineLength()
     */
    #[DataProvider('provideTestGetLineLength')]
    public function testGetLineLength($expectedLineLength, $file)
    {
        $index = new FixedSizeIndex($file, 0, 1);
        $this->assertEquals($expectedLineLength, $index->getLineLength());
    }
    
    /**
     * Test cases for testGetLineLength()
     * 
     * @return array
     * @see testGetLineLength()
     */
    public static function provideTestGetLineLength()
    {
        return array(
            array(5, __DIR__ . "/../index/fixedSize/one-line.txt"),
            array(6, __DIR__ . "/../index/fixedSize/simple.txt")
        );
    }
    
    /**
     * Tests finding all keys with different index configurations 
     * 
     */
    #[DataProvider('provideTestIndexConfiguration')]
    public function testIndexConfiguration($offset, $indexFieldLength, $length)
    {
        $generator = new FixedSizeIndexGenerator();
        $generator->setIndexFieldOffset($offset);
        $generator->setIndexFieldLength($indexFieldLength);
        $generator->setIndexLength($length);
        
        $index = $generator->getIndex();
        for ($key = 0; $key < $length; $key++) {
            $result  = $index->search($key);
            $this->assertNotNull($result, "key: $key");
            $this->assertMatchesRegularExpression(
                '/data_' . $key . '_.*\$/s',
                $result->getData(),
                "key: $key"
            );
            
        }
    }
    
    public static function provideTestIndexConfiguration()
    {
        $cases = array();
        $lengths = array(1, 10, 100, File::DEFAULT_BLOCK_SIZE * 12);
        $offsets = array(0, 1, File::DEFAULT_BLOCK_SIZE * 12);
        foreach ($lengths as $length) {
            foreach ($offsets as $offset) {
                $indexFieldLength = 1;
                $cases = \array_merge($cases, array(
                    array($offset, $indexFieldLength, $length)
                ));
                
            }
        }
        return $cases;
    }
}
