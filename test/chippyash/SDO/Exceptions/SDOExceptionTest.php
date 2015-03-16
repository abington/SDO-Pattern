<?php
/**
 * chippyash/sdo-pattern
 * Service Data Objects
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson, UK, 2015
 * @license GPL V3 or later
 */

namespace chippyash\Test\SDO\Exceptions;

use chippyash\SDO\Exceptions\SDOException;

class SDOExceptionTest extends \PHPUnit_Framework_TestCase {

    public function testConstructingAnSDOExceptionCreatesCorrectClass()
    {
        $this->assertInstanceOf('chippyash\SDO\Exceptions\SDOException', new SDOException());
    }
}
