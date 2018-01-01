# chippyash/SDO-Pattern

## Quality Assurance

![PHP 5.5](https://img.shields.io/badge/PHP-5.5-blue.svg)
![PHP 5.6](https://img.shields.io/badge/PHP-5.6-blue.svg)
![PHP 7](https://img.shields.io/badge/PHP-7-blue.svg)
[![Build Status](https://travis-ci.org/chippyash/SDO-Pattern.svg?branch=master)](https://travis-ci.org/chippyash/SDO-Pattern)
[![Test Coverage](https://codeclimate.com/github/chippyash/SDO-Pattern/badges/coverage.svg)](https://codeclimate.com/github/chippyash/SDO-Pattern/coverage)
[![Code Climate](https://codeclimate.com/github/chippyash/SDO-Pattern/badges/gpa.svg)](https://codeclimate.com/github/chippyash/SDO-Pattern)

The above badges represent the current development branch.  As a rule, I don't push
 to GitHub unless tests, coverage and usability are acceptable.  This may not be
 true for short periods of time; on holiday, need code for some other downstream
 project etc.  If you need stable code, use a tagged version. Read 'Further Documentation'
 and 'Installation'.
 
Please note that the Travis build servers sometimes have a wobbly and thus the build
status may be incorrect.  If you need to be certain, click on the build status badge
and checkout out the build for yourself.
 
See the [Test Contract](https://github.com/chippyash/SDO-Pattern/blob/master/docs/Test-Contract.md)

## What?

This library supplies the Service Data Object (SDO) pattern.  SDOs have a [long history](http://en.wikipedia.org/wiki/Service_Data_Objects) and provide a way to abstract
out the process and operation on retrieving and sending data to some remote service endpoint.  That endpoint can
be a file, a database, a REST service etc.

Essentially, you are not interested in where the data resides, only in using it.  The SDO pattern abstracts out the
fetching and sending process to allow you to concentrate on using the data in some internalised format.

There is a [long discourse](http://devzone.zend.com/330/introducing-service-data-objects-for-php/) on implementing SDOs 
in PHP written by Zend that describes a much more complex SDO infrastructure than is provided in this library. Personally, 
I've never had the need to get complicated with SDOs, but it's there if you need it.

The library is released under the [GNU GPL V3 or later license](http://www.gnu.org/copyleft/gpl.html)

## Why?

This pattern has emerged through many years of repeating the same thing:

- I need data in a format that I can use it
- The data is located at some endpoint over which I have no control

There are some key elements to a SDO if you ignore the complexity of session storage and potential caching of SDOs
in flight:

- You need to have a consistent internal representation of the remote data
    - mappers provide this. The mapper turns external format data (xml, json etc) into an internal format (a model
    class for instance) that can be referenced by the application. The mapper also maps that internal format back out
    to the external format for writing.
- You need to validate that the incoming remote data is conformant
    - validators provide this
- The remote endpoint may change over time
    - transports facilitate this. Transports can connect to databases, file stores, http endpoints etc.
    
## When

The current library provides the basic tools for creating SDOs, primarily the required interfaces.  Also included
 is an abstract SDO which you can extend for your concrete implementation. Simple mappers, validators and transports are
 provided. An example script is also provided to give you a flavour of how to implement them.
 
If you want more, either suggest it, or better still, fork it and provide a pull request.

Check out [ZF4 Packages](http://zf4.biz/packages?utm_source=github&utm_medium=web&utm_campaign=blinks&utm_content=sdopattern) for more packages
 
## How

### Coding Basics

An SDO requires three things in order to operate effectively:

* a TransportInterface (Transport) to do the actual fetching and sending of data from and to the service endpoint
* a MapperInterface (Mapper) to map the external data into something your application can use, and to map that internal 
 representation back out as something the service endpoint understands
* a ValidatorInterface (Validator) so that you can be assured that incoming data meets your application's requirements

The following is based on a simple scenario:

- data is contained in a file that is provided by some other system over which we have no control
    - we need a file transport
- external data format is json, the internal format is a StdClass
    - we need a mapper that maps incoming json to StdClass and back out as Json
- external data needs to match a specific minimal pattern to be valid
    - we need a validator that checks minimum requirements
     
#### Transport

The TransportInterface dictates two methods:

- public function read();
    - read data from a remote endpoint
    
- public function write($data);
    - write data to a remote endpoint

The supplied chippyash\SDO\Transport\File gives us what we need, and takes a single construction parameter, the path
to the file.

#### Mapper

The MapperInterface dictates two methods:

- public function mapIn($data);
    - map some data fetched by TransportInterface::read() into some internalised format
    
- public function mapOut($internal);
    - map internal formatted data to some external format to be used by TransportInterface::write()
    
The supplied chippyash\SDO\Mapper\Json gives what we need.

#### Validator

The ValidatorInterface dictates one method:

- public function isValid($external);
    - validate the external format data read by TransportInterface::read() and return true if valid else false.
     
The chippyash\SDO\Validator\Passthru validator simply returns true for any data it is asked to validate, and is used
for our example.

#### SDO

The SDOInterface dictates

- public function fetch();
    - fetch SDO data from remote source, validate it and map it into internalised format
- public function send();
    - convert internal format data to external format and send it to remote target
- public function getData();
    - get internal format data
- public function setData($incomingData);
    - set the SDOs internal data structure directly
- public function setMapper(MapperInterface $mapper);
    - set the mapper for the SDO
- public function setTransport(TransportInterface $transport);
    - set the transport for the SDO
- public function setValidator(ValidatorInterface $validator);
    - set the validator for the SDO
    
For our example we can create a simple SDO by extending the AbstractSDO and constructing it with

<pre>
    class FooSDO extends AbstractSDO {}
    
    $sdo = new FooSDO(
        new FileTransport(__DIR__ . '/test.json'),
        new JsonMapper(),
        new PassthruValidator()
    );
</pre>

To read and write data we can use:

<pre>
    $obj = $sdo->fetch()->getData();
    $obj->bar += 1;
    $sdo->send();
</pre>

The AbstractSDO also supports proxying the getData() method via the magic \__invoke method, so we can also read and write
thus:

<pre>
    $sdo->fetch();
    $sdo()->bar += 1;
    $sdo->send();
</pre>

This clearly is closer to true SDO usage.  

It is not beyond the wit of a PHP dev to create a descendent SDO that is 
totally self managing, caching locally when required, fetching and sending only when required.  In my day job, we have 
all of this and service classes that manage whole collections of SDOs.  Numb nuts upstream change the incoming payload;
 we just change the validator (if necessary, see below). Change the endpoint; we change the transport. Want to change the way you 
 represent the data internally; change the mapper.  
 
On Mappers, consider using the [Assembly Builder](https://github.com/chippyash/Assembly-Builder) or [Builder Pattern](https://github.com/chippyash/Builder-Pattern) if you need to create 
complex data structures.

On Validators, code defensively. Validate only what you expect to use and ignore the rest.  That way, when they change the
payload without telling you, you don't care (assuming they leave what you want in it!).
Consider using [Functional Validation](https://github.com/chippyash/Validation)

You can find the source in example/example.php.

### Changing the library

1.  fork it
2.  write the test
3.  amend it
4.  do a pull request

Found a bug you can't figure out?

1.  fork it
2.  write the test
3.  do a pull request

NB. Make sure you rebase to HEAD before your pull request

Alternatively, raise a ticket in the issue tracker

## Where?

The library is hosted at [Github](https://github.com/chippyash/SDO-Pattern). It is
available at [Packagist.org](https://packagist.org/packages/chippyash/sdo-pattern)

### Installation

Install [Composer](https://getcomposer.org/)

#### For production

add

<pre>
    "chippyash/sdo-pattern": ">=2.0.0"
</pre>

to your composer.json "requires" section

#### For development

Clone this repo, and then run Composer in local repo root to pull in dependencies

<pre>
    git clone git@github.com:chippyash/SDO-Pattern.git chippyash-sdo
    cd chippyash-sdo
    composer install
</pre>

To run the tests:

<pre>
    cd chippyash-sdo
    vendor/bin/phpunit -c test/phpunit.xml test/
</pre>
## License

This software library is released under the [GNU GPL V3 or later license](http://www.gnu.org/copyleft/gpl.html)

This software library is Copyright (c) 2015-2016, Ashley Kitson, UK

A commercial license is available for this software library, please contact the author. 
It is normally free to deserving causes, but gets you around the limitation of the GPL
license, which does not allow unrestricted inclusion of this code in commercial works.

## History

V1.0.0 Original release

V2.0.0 BC Break: namespace change from chippyash\SDO to Chippyash\SDO

V2.0.1 update examples

V2.0.2 Add link to packages

V2.0.3 verify PHP7 compatibility

V2.0.4 update build scripting
