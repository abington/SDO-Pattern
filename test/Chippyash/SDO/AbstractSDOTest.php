<?php
/**
 * Chippyash/sdo-pattern
 * Service Data Objects
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson, UK, 2015
 * @license GPL V3 or later
 */

namespace Chippyash\Test\SDO;

use Chippyash\SDO\AbstractSDO;

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
        $this->transport = $this->getMock('Chippyash\SDO\TransportInterface');
        $this->mapper = $this->getMock('Chippyash\SDO\MapperInterface');
        $this->validator = $this->getMock('Chippyash\SDO\ValidatorInterface');
        $this->sut = $this->getMockForAbstractClass('Chippyash\SDO\AbstractSDO',
            array($this->transport, $this->mapper, $this->validator)
        );
    }

    public function testConstructionWithParametersReturnsSDO()
    {
        $this->assertInstanceOf('Chippyash\SDO\AbstractSDO', $this->sut);
    }

    public function testConstructionParametersAreOptional()
    {
        $this->assertInstanceOf('Chippyash\SDO\AbstractSDO',
            $this->getMockForAbstractClass('Chippyash\SDO\AbstractSDO',
                array(null, $this->mapper, $this->validator)
            )
        );
        $this->assertInstanceOf('Chippyash\SDO\AbstractSDO',
            $this->getMockForAbstractClass('Chippyash\SDO\AbstractSDO',
                array($this->transport, null, $this->validator)
            )
        );
        $this->assertInstanceOf('Chippyash\SDO\AbstractSDO',
            $this->getMockForAbstractClass('Chippyash\SDO\AbstractSDO',
                array($this->transport, $this->mapper, null)
            )
        );
    }

    public function testYouCanSetATransportAfterConstruction()
    {
        $this->assertInstanceOf('Chippyash\SDO\AbstractSDO',
            $this->sut->setTransport($this->getMock('Chippyash\SDO\TransportInterface'))
        );
    }

    public function testYouCanSetAMapperAfterConstruction()
    {
        $this->assertInstanceOf('Chippyash\SDO\AbstractSDO',
            $this->sut->setMapper($this->getMock('Chippyash\SDO\MapperInterface'))
        );
    }

    public function testYouCanSetAValidatorAfterConstruction()
    {
        $this->assertInstanceOf('Chippyash\SDO\AbstractSDO',
            $this->sut->setValidator($this->getMock('Chippyash\SDO\ValidatorInterface'))
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
     * @expectedException Chippyash\SDO\Exceptions\SDOException
     * @expectedExceptionMessage No validator set
     */
    public function testFetchWillThrowExceptionIfValidatorNotSet()
    {
        $sut = $this->getMockForAbstractClass('Chippyash\SDO\AbstractSDO',
            array($this->transport, $this->mapper, null)
        );
        $sut->fetch();
    }

    /**
     * @expectedException Chippyash\SDO\Exceptions\SDOException
     * @expectedExceptionMessage No mapper set
     */
    public function testFetchWillThrowExceptionIfMapperNotSet()
    {
        $this->validator
            ->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $sut = $this->getMockForAbstractClass('Chippyash\SDO\AbstractSDO',
            array($this->transport, null, $this->validator)
        );
        $sut->fetch();
    }

    /**
     * @expectedException Chippyash\SDO\Exceptions\SDOException
     * @expectedExceptionMessage No transport set
     */
    public function testFetchWillThrowExceptionIfTransportNotSet()
    {
        $sut = $this->getMockForAbstractClass('Chippyash\SDO\AbstractSDO',
            array(null, $this->mapper, $this->validator)
        );
        $sut->fetch();
    }

    public function testYouCanSetTheInternalDataManually()
    {
        $this->assertInstanceOf('Chippyash\SDO\AbstractSDO',
            $this->sut->setData(new \StdClass())
        );
        $this->assertEquals(new \StdClass(), $this->sut->getData());
    }

    public function testSendReturnsFluentInterfaceOnSuccess()
    {
        $this->transport
            ->expects($this->once())
            ->method('write')
            ->will($this->returnSelf());
        $this->mapper
            ->expects($this->once())
            ->method('mapOut')
            ->will($this->returnValue('{"bar":1}'));
        $this->assertInstanceOf('Chippyash\SDO\AbstractSDO', $this->sut->send());
    }

    /**
     * @expectedException Chippyash\SDO\Exceptions\SDOException
     * @expectedExceptionMessage No mapper set
     */
    public function testSendWillThrowExceptionIfMapperNotSet()
    {
        $sut = $this->getMockForAbstractClass('Chippyash\SDO\AbstractSDO',
            array($this->transport)
        );
        $sut->send();
    }

    /**
     * @expectedException Chippyash\SDO\Exceptions\SDOException
     * @expectedExceptionMessage No transport set
     */
    public function testSendWillThrowExceptionIfTransportNotSet()
    {
        $sut = $this->getMockForAbstractClass('Chippyash\SDO\AbstractSDO',
            array(null, $this->mapper)
        );
        $sut->send();
    }
}
