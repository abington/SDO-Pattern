<?php
/**
 * chippyash/sdo-pattern
 * Service Data Objects
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson, UK, 2015
 * @license GPL V3 or later
 */

namespace chippyash\Test\SDO;

use chippyash\SDO\AbstractSDO;

class AbstractSDOTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var AbstractSDO
     */
    protected $sut;
    /**
     * @var TransportInterface
     */
    protected $transport;
    /**
     * @var MapperInterface
     */
    protected $mapper;
    /**
     * @var ValidatorInterface
     */
    protected $validator;

    protected function setUp()
    {
        $this->transport = $this->getMock('chippyash\SDO\TransportInterface');
        $this->mapper = $this->getMock('chippyash\SDO\MapperInterface');
        $this->validator = $this->getMock('chippyash\SDO\ValidatorInterface');
        $this->sut = $this->getMockForAbstractClass('chippyash\SDO\AbstractSDO',
            array($this->transport, $this->mapper, $this->validator)
        );
    }

    public function testConstructionWithParametersReturnsSDO()
    {
        $this->assertInstanceOf('chippyash\SDO\AbstractSDO', $this->sut);
    }

    public function testConstructionParametersAreOptional()
    {
        $this->assertInstanceOf('chippyash\SDO\AbstractSDO',
            $this->getMockForAbstractClass('chippyash\SDO\AbstractSDO',
                array(null, $this->mapper, $this->validator)
            )
        );
        $this->assertInstanceOf('chippyash\SDO\AbstractSDO',
            $this->getMockForAbstractClass('chippyash\SDO\AbstractSDO',
                array($this->transport, null, $this->validator)
            )
        );
        $this->assertInstanceOf('chippyash\SDO\AbstractSDO',
            $this->getMockForAbstractClass('chippyash\SDO\AbstractSDO',
                array($this->transport, $this->mapper, null)
            )
        );
    }

    public function testYouCanSetATransportAfterConstruction()
    {
        $this->assertInstanceOf('chippyash\SDO\AbstractSDO',
            $this->sut->setTransport($this->getMock('chippyash\SDO\TransportInterface'))
        );
    }

    public function testYouCanSetAMapperAfterConstruction()
    {
        $this->assertInstanceOf('chippyash\SDO\AbstractSDO',
            $this->sut->setMapper($this->getMock('chippyash\SDO\MapperInterface'))
        );
    }

    public function testYouCanSetAValidatorAfterConstruction()
    {
        $this->assertInstanceOf('chippyash\SDO\AbstractSDO',
            $this->sut->setValidator($this->getMock('chippyash\SDO\ValidatorInterface'))
        );
    }

    public function testCallingGetDataBeforeFetchWillReturnNull()
    {
        $this->assertNull($this->sut->getData());
    }

    public function testCallingGetDataAfterFetchWillReturnDataIfAvailable()
    {
        $internalData = json_decode('{"bar":1}');
        $this->transport
            ->expects($this->once())
            ->method('read')
            ->will($this->returnValue('{"bar":1}'));
        $this->mapper
            ->expects($this->once())
            ->method('mapIn')
            ->will($this->returnValue($internalData));
        $this->validator
            ->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $this->assertEquals($internalData, $this->sut->fetch()->getData());
    }

    public function testMagicInvokeMethodProxiesToGetDataMethod()
    {
        $internalData = json_decode('{"bar":1}');
        $this->transport
            ->expects($this->once())
            ->method('read')
            ->will($this->returnValue('{"bar":1}'));
        $this->mapper
            ->expects($this->once())
            ->method('mapIn')
            ->will($this->returnValue($internalData));
        $this->validator
            ->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $sut = $this->sut;
        $sut->fetch();
        $this->assertEquals($internalData, $sut());
    }

    /**
     * @expectedException chippyash\SDO\Exceptions\SDOException
     * @expectedExceptionMessage No validator set
     */
    public function testFetchWillThrowExceptionIfValidatorNotSet()
    {
        $sut = $this->getMockForAbstractClass('chippyash\SDO\AbstractSDO',
            array($this->transport, $this->mapper, null)
        );
        $sut->fetch();
    }

    /**
     * @expectedException chippyash\SDO\Exceptions\SDOException
     * @expectedExceptionMessage No mapper set
     */
    public function testFetchWillThrowExceptionIfMapperNotSet()
    {
        $this->validator
            ->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $sut = $this->getMockForAbstractClass('chippyash\SDO\AbstractSDO',
            array($this->transport, null, $this->validator)
        );
        $sut->fetch();
    }

    /**
     * @expectedException chippyash\SDO\Exceptions\SDOException
     * @expectedExceptionMessage No transport set
     */
    public function testFetchWillThrowExceptionIfTransportNotSet()
    {
        $sut = $this->getMockForAbstractClass('chippyash\SDO\AbstractSDO',
            array(null, $this->mapper, $this->validator)
        );
        $sut->fetch();
    }

    public function testYouCanSetTheInternalDataManually()
    {
        $this->assertInstanceOf('chippyash\SDO\AbstractSDO',
            $this->sut->setData(new \StdClass())
        );
        $this->assertEquals(new \StdClass(), $this->sut->getData());
    }

    /**
     * @expectedException chippyash\SDO\Exceptions\SDOException
     * @expectedExceptionMessage No mapper set
     */
    public function testSendWillThrowExceptionIfMapperNotSet()
    {
        $sut = $this->getMockForAbstractClass('chippyash\SDO\AbstractSDO',
            array($this->transport)
        );
        $sut->send();
    }

    /**
     * @expectedException chippyash\SDO\Exceptions\SDOException
     * @expectedExceptionMessage No transport set
     */
    public function testSendWillThrowExceptionIfTransportNotSet()
    {
        $sut = $this->getMockForAbstractClass('chippyash\SDO\AbstractSDO',
            array(null, $this->mapper)
        );
        $sut->send();
    }
}
