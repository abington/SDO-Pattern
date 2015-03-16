<?php
/**
 * chippyash/sdo-pattern
 * Service Data Objects
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson, UK, 2015
 * @license GPL V3 or later
 */

namespace chippyash\Test\SDO\Transport;

use chippyash\SDO\Transport\File as FileTransport;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamContent;

class FileTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var FileTransport
     */
    protected $sut;

    /**
     * @var  vfsStreamDirectory
     */
    protected $root;

    /**
     * @var string
     */
    protected $fh;

    public function setUp()
    {
        $this->root = vfsStream::setup('exampleDir');
        $this->root->addChild(vfsStream::newFile('foo')->withContent('{"bar":1}'));
        $this->fh = vfsStream::url('exampleDir/foo');
        $this->sut = new FileTransport($this->fh);
    }

    public function testReadWillReturnFileContentIfFileExists()
    {
        $this->assertEquals(file_get_contents($this->fh), $this->sut->read());
    }

    /**
     * @expectedException \chippyash\SDO\Exceptions\SDOException
     * @expectedExceptionMessage File does not exist
     */
    public function testReadWillThrowExceptionIfFileDoesNotExist()
    {
        unlink($this->fh);
        $this->sut->read();
    }

    public function testWriteWillWriteContentsToFile()
    {
        $this->sut->write('{"bar":2}');
        $this->assertEquals('{"bar":2}', file_get_contents($this->fh));
    }
}
