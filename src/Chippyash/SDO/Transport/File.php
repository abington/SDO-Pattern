<?php
/**
 * Chippyash/sdo-pattern
 * Service Data Objects
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson, UK, 2015
 * @license GPL V3 or later
 */

namespace Chippyash\SDO\Transport;

use Chippyash\SDO\Exceptions\SDOException;
use Chippyash\SDO\TransportInterface;

/**
 * Read and write data object to/from file
 */
class File implements TransportInterface
{
    const ERR1 = 'File does not exist';

    /**
     * @var string
     */
    protected $fileName;

    /**
     * @param string $filename
     */
    public function __construct($filename)
    {
        $this->fileName = $filename;
    }

    /**
     * Read data from remote source
     *
     * @return mixed Data returned from remote source
     */
    public function read()
    {
        if (!file_exists($this->fileName)) {
            throw new SDOException(self::ERR1);
        }
        return file_get_contents($this->fileName);
    }

    /**
     * Write data to remote destination
     *
     * @param mixed $data
     * @return TransportInterface Fluent Interface
     */
    public function write($data)
    {
        file_put_contents($this->fileName, $data);

        return $this;
    }
}
