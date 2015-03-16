<?php
/**
 * chippyash/sdo-pattern
 * Service Data Objects
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson, UK, 2015
 * @license GPL V3 or later
 */

namespace chippyash\SDO\Mapper;

use chippyash\SDO\MapperInterface;

/**
 * Pass Through mapper: returns whatever it is given
 */
class Passthru implements MapperInterface
{
    /**
     * Map an external data representation into an internal one
     *
     * @param mixed $data
     * @return mixed Internal structure of date for the SDO
     */
    public function mapIn($data)
    {
        return $data;
    }

    /**
     * Map the internal data representation into an external one
     *
     * @param mixed $internal Internal representation of data
     * @return mixed External representation of date
     */
    public function mapOut($internal)
    {
        return $internal;
    }
}