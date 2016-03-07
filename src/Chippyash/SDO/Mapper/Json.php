<?php
/**
 * Chippyash/sdo-pattern
 * Service Data Objects
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson, UK, 2015
 * @license GPL V3 or later
 */

namespace Chippyash\SDO\Mapper;

use Chippyash\SDO\Exceptions\SDOException;
use Chippyash\SDO\MapperInterface;

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
            throw new SDOException('Failed to decode json');
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
