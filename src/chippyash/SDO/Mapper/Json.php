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

use chippyash\SDO\Exceptions\SDOException;
use chippyash\SDO\MapperInterface;

/**
 * Json Mapper. Convert from json to a StdClass and back again
 */
class Json implements MapperInterface
{
    /**
     * Map an external data representation into an internal one
     *
     * @param mixed $data
     * @return mixed Internal structure of date for the SDO
     *
     * @throws SDOException
     */
    public function mapIn($data)
    {
        $ret = json_decode($data);
        if (is_null($ret)) {
            throw new SDOException('Failed to decode json. Error given was: ' . json_last_error_msg());
        }

        return $ret;
    }

    /**
     * Map the internal data representation into an external one
     *
     * @param mixed $internal Internal representation of data
     * @return mixed External representation of date
     */
    public function mapOut($internal)
    {
        return json_encode($internal);
    }
}
