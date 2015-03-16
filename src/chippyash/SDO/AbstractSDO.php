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

use chippyash\SDO\SDOInterface;
use chippyash\SDO\MapperInterface;
use chippyash\SDO\TransportInterface;
use chippyash\SDO\ValidatorInterface;
use chippyash\SDO\Exceptions\SDOException;

/**
 * An abstract base for your SDOs
 */
abstract class AbstractSDO implements SDOInterface
{
    /**@+
     * Error messages
     */
    const ERR1 = 'No transport set';
    const ERR2 = 'No mapper set';
    const ERR3 = 'No validator set';
    /**@-*/

    /**
     * @var MapperInterface
     */
    protected $mapper;

    /**
     * @var TransportInterface
     */
    protected $transport;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * Internal format data
     *
     * @var mixed
     */
    protected $data;

    /**
     * Constructor
     *
     * @param TransportInterface $transport
     * @param MapperInterface $mapper
     * @param ValidatorInterface $validator
     */
    public function __construct(TransportInterface $transport = null, MapperInterface $mapper = null, ValidatorInterface $validator = null)
    {
        if (!is_null($transport)) {
            $this->setTransport($transport);
        }
        if (!is_null($mapper)) {
            $this->setMapper($mapper);
        }
        if (!is_null($validator)) {
            $this->setValidator($validator);
        }
    }

    /**
     * Proxy to get
     * @see get()
     *
     * @return mixed
     */
    public function __invoke()
    {
        return $this->getData();
    }

    /**
     * Fetch data from remote location via the transport
     *
     * @see setData()
     * @return SDOInterface Fluent Interface
     */
    public function fetch()
    {
        return $this->setRawData($this->getTransport()->read());
    }

    /**
     * Send data to remote location via the transport
     *
     * Side effect: The SDO's internal data is mapped out using the mapper
     * before sending via the transport
     *
     * @return SDOInterface Fluent Interface
     */
    public function send()
    {
        $this->getTransport()->write($this->getMapper()->mapOut($this->data));

        return $this;
    }

    /**
     * Return the internal representation of the data
     * Internal representation will be defined by the mapper
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set the data for the SDO
     *
     * Incoming data must be in internal format for the SDO
     *
     * @param mixed $incomingData
     * @return SDOInterface Fluent Interface
     */
    public function setData($incomingData)
    {
        $this->data = $incomingData;

        return $this;
    }

    /**
     * Set the data mapper
     *
     * @param MapperInterface $mapper
     * @return SDOInterface Fluent Interface
     */
    public function setMapper(MapperInterface $mapper)
    {
        $this->mapper = $mapper;

        return $this;
    }

    /**
     * Set the data transporter
     *
     * @param TransportInterface $transport
     *
     * @return SDOInterface Fluent Interface
     */
    public function setTransport(TransportInterface $transport)
    {
        $this->transport = $transport;

        return $this;
    }

    /**
     * Set the incoming data validator
     *
     * @param ValidatorInterface $validator
     *
     * @return SDOInterface Fluent Interface
     */
    public function setValidator(ValidatorInterface $validator)
    {
        $this->validator = $validator;

        return $this;
    }

    /**
     * Set the data for the SDO form data in an external format
     *
     * Side effect: the incoming data is validated by the validator
     * Side effect: if the incoming data is valid, it will be mapped to an internal
     * representation by the mapper
     *
     * @param mixed $incomingData
     * @return SDOInterface Fluent Interface
     */
    protected function setRawData($incomingData)
    {
        if ($this->getValidator()->isValid($incomingData)) {
            $this->setData($this->getMapper()->mapIn($incomingData));
        }

        return $this;
    }

    /**
     * Return the Transport
     *
     * @return TransportInterface
     */
    protected function getTransport()
    {
        if (empty($this->transport)) {
            throw new SDOException(self::ERR1);
        }

        return $this->transport;
    }

    /**
     * Return the Mapper
     *
     * @return MapperInterface
     */
    protected function getMapper()
    {
        if (empty($this->mapper)) {
            throw new SDOException(self::ERR2);
        }

        return $this->mapper;
    }

    /**
     * Return the validator
     *
     * @return ValidatorInterface
     */
    protected function getValidator()
    {
        if (empty($this->validator)) {
            throw new SDOException(self::ERR3);
        }

        return $this->validator;
    }
}