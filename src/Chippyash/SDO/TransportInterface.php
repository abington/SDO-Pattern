<?php
/**
 * Chippyash/sdo-pattern
 * Service Data Objects
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson, UK, 2015
 * @license GPL V3 or later
 */

namespace Chippyash\SDO;

/**
 * Fetch and send data on an SDO's behalf
 */
interface TransportInterface {

    /**
     * Read data from remote source
     *
     * @return mixed Data returned from remote source
     */
    public function read();

    /**
     * Write data to remote destination
     *
     * @param mixed $data
     * @return TransportInterface Fluent Interface
     */
    public function write($data);
}