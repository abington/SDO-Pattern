#! /usr/bin/env php
<?php
/**
 * catChippyash/sdo
 * Service Data Objects
 * Example
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson, UK, 2015
 * @license GPL V3 or later
 */
namespace Chippyash\example;

require_once('../vendor/autoload.php');

use Chippyash\SDO\Transport\File as FileTransport;
use Chippyash\SDO\Validator\Passthru as PassthruValidator;
use Chippyash\SDO\Mapper\Json as JsonMapper;
use Chippyash\SDO\AbstractSDO;

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


