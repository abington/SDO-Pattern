# chippyash/SDO

## Quality Assurance

Certified for PHP 5.3+

[![Build Status](https://travis-ci.org/chippyash/SDO-Pattern.svg?branch=master)](https://travis-ci.org/chippyash/SDO-Pattern)
[![Coverage Status](https://coveralls.io/repos/chippyash/SDO-Pattern/badge.png)](https://coveralls.io/r/chippyash/SDO-Pattern)

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

There are some key elements to an SDO if you ignore the complexity of session storage and potential caching of SDOs
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

See [The Matrix Packages](http://the-matrix.github.io/packages/) for other packages from chippyash
 
## How

### Coding Basics

An SDO requires three things in order to operate effectively:

* a TransportInterface (Transport) to do the actual fetching and sending of data from and to the service endpoint
* a MapperInterface (Mapper) to map the external data into something your application can use, and to map that internal 
 representation back out as something the service endpoint understands
* a ValidatorInterface (Validator) so that you can be assured that incoming data meets your application's requirements

The following is based on a simple scenario:

- data is contained in file that is provided by some other system over which we have no control
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
    
- public function write($internal);
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
    - convert internal format data to extenal format and send it to remote target
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
    "chippyash/sdo-pattern": ">=1.0.0"
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

## History

V1.0.0 Original release

