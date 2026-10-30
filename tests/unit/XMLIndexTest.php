<?php

namespace malkusch\index\test;

use PHPUnit\Framework\Attributes\DataProvider;
use malkusch\index\Index;
use malkusch\index\test\Generator\XMLIndexGenerator;



/**
 * Tests the xml index
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license WTFPL
 */
class XmlIndexTest extends AbstractTest
{

    /**
     * Tests finding every key
     *
     * @param XMLIndexGenerator $generator Index generator
     *
     */
    /*
    public function testSearch(
        XMLIndexGenerator $generator
    ) {
        $index = $generator->getIndex();
        for ($key = 0; $key < $generator->getIndexLength(); $key++) {
            $data = $index->search($key);
            $xml  = new \SimpleXMLElement($data);

            // Container
            $this->assertEquals($index->getElement(), $xml->getName());

            // Index
            $attributes = $xml->attributes();
            $this->assertTrue(isset($attributes[$index->getAttribute()]));
            $this->assertEquals(
                $key,
                (string) $attributes[$index->getAttribute()]
            );

            // Data
            $this->assertEquals(
                1,
                count($xml->{XMLIndexGenerator::ELEMENT_PAYLOAD})
            );
            $this->assertMatchesRegularExpression(
                "/^data_{$key}_.+$/",
                (string) $xml->{XMLIndexGenerator::ELEMENT_PAYLOAD}[0]
            );

        }
    }
     */
    
    public function testIncomplete()
    {
        $this->markTestIncomplete();
    }

    /**
     * Test cases for testSearch()
     *
     * @return array
     */
    #[DataProvider('provideTestSearch')]
    public static function provideTestSearch()
    {
        $cases  = array();

        $generator = new XMLIndexGenerator();
        $generator->setIndexLength(10000);
        $generator->formatOutput(true);
        $cases[] = array($generator);

        $generator = new XMLIndexGenerator();
        $generator->setIndexLength(10000);
        $generator->formatOutput(false);
        $cases[] = array($generator);

        $generator = new XMLIndexGenerator();
        $generator->setIndexLength(1);
        $generator->formatOutput(true);
        $cases[] = array($generator);

        $generator = new XMLIndexGenerator();
        $generator->setIndexLength(1);
        $generator->formatOutput(false);
        $cases[] = array($generator);

        return $cases;
    }
}
