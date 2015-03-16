<?php
/**
 * chippyash/sdo-pattern
 * Service Data Objects
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson, UK, 2015
 * @license GPL V3 or later
 */

namespace chippyash\Test\SDO\Mapper;

use chippyash\SDO\Mapper\Passthru as PassthruMapper;

class PassthruTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var PassthruMapper
     */
    protected $sut;

    protected function setUp()
    {
        $this->sut = new PassthruMapper();
    }

    public function testMapInReturnsWhatItIsGiven()
    {
        $test = new \StdClass();
        $test->bar = 1;
        $this->assertEquals($test, $this->sut->mapIn($test));
    }

    public function testMapOutReturnsWhatItIsGiven()
    {
        $test = new \StdClass();
        $test->bar = 1;
        $this->assertEquals($test, $this->sut->mapOut($test));
    }
}
