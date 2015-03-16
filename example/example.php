#! /usr/bin/env php
<?php
/**
 * chippyash/sdo
 * Service Data Objects
 * Example
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson, UK, 2015
 * @license GPL V3 or later
 */
namespace chippyash\example;

require_once('../vendor/autoload.php');

use chippyash\SDO\Transport\File as FileTransport;
use chippyash\SDO\Validator\Passthru as PassthruValidator;
use chippyash\SDO\Mapper\Json as JsonMapper;
use chippyash\SDO\AbstractSDO;

class FooSDO extends AbstractSDO {}

//open test.json and watch it change as a result of this script

$sdo = new FooSDO(
    new FileTransport(__DIR__ . '/test.json'),
    new JsonMapper(),
    new PassthruValidator()
);

//fetch and get the data object
$obj = $sdo->fetch()->getData();
//do something with data object
$obj->bar += 1;
//and send it back
$sdo->send();

//using the magic invoke
$sdo->fetch();
$sdo()->bar += 1;
$sdo->send();


