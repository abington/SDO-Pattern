<?php
/**
 * Chippyash/sdo-pattern
 * Service Data Objects
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson, UK, 2015
 * @license GPL V3 or later
 */

namespace Chippyash\Test\SDO\Exceptions;

use Chippyash\SDO\Exceptions\SDOException;

class SDOExceptionTest extends \PHPUnit_Framework_TestCase {

    public function testConstructingAnSDOExceptionCreatesCorrectClass()
    {
        $this->assertInstanceOf('Chippyash\SDO\Exceptions\SDOException', new SDOException());
    }
}
