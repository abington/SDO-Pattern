<?php
/**
 * Chippyash/sdo-pattern
 * Service Data Objects
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson, UK, 2015
 * @license GPL V3 or later
 */

namespace Chippyash\Test\SDO\Validator;

use Chippyash\SDO\Validator\Passthru as PassthruValidator;

class PassthruTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var PassthruValidator
     */
    protected $sut;

    protected function setUp()
    {
        $this->sut = new PassthruValidator();
    }

    public function testIsValidAlwaysReturnsTrue()
    {
        $this->assertTrue($this->sut->isValid(true));
        $this->assertTrue($this->sut->isValid(false));
        $this->assertTrue($this->sut->isValid(null));
    }
}
