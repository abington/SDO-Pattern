<?php
/**
 * chippyash/sdo-pattern
 * Service Data Objects
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson, UK, 2015
 * @license GPL V3 or later
 */

namespace chippyash\SDO;

use chippyash\SDO\MapperInterface;
use chippyash\SDO\TransportInterface;
use chippyash\SDO\ValidatorInterface;

interface SDOInterface {

    /**
     * Fetch data from remote location
     * @return SDOInterface Fluent Interface
     */
    public function fetch();

    /**
     * Send data to remote location
     * @return SDOInterface Fluent Interface
     */
    public function send();

    /**
     * Return the internal representation of the data
     * Internal representation will be defined by the mapper
     *
     * @return mixed
     */
    public function getData();

    /**
     * Set the data for the SDO
     *
     * Incoming data must be in internal format for the SDO
     *
     * @param mixed $incomingData
     * @return SDOInterface Fluent Interface
     */
    public function setData($incomingData);

    /**
     * Set the data mapper
     *
     * @param MapperInterface $mapper
     * @return SDOInterface Fluent Interface
     */
    public function setMapper(MapperInterface $mapper);

    /**
     * Set the data transporter
     *
     * @param TransportInterface $transport
     *
     * @return SDOInterface Fluent Interface
     */
    public function setTransport(TransportInterface $transport);

    /**
     * Set the incoming data validator
     *
     * @param ValidatorInterface $validator
     *
     * @return SDOInterface Fluent Interface
     */
    public function setValidator(ValidatorInterface $validator);
}