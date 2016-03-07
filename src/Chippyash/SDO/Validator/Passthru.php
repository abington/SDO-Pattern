<?php
/**
 * Chippyash/sdo-pattern
 * Service Data Objects
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson, UK, 2015
 * @license GPL V3 or later
 */

namespace Chippyash\SDO\Validator;

use Chippyash\SDO\ValidatorInterface;

/**
 * Pass Through validator: Returns true
 */
class Passthru implements ValidatorInterface
{
    /**
     * Validate incoming data prior to mapping
     *
     * @param mixed $external External data to validate
     *
     * @return boolean
     */
    public function isValid($external)
    {
        return true;
    }
}