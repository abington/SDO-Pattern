#!/bin/bash
cd ~/Projects/chippyash/source/SDO
vendor/bin/phpunit -c test/phpunit.xml --testdox-html contract.html test/
tdconv -t "Chippyash SDO Pattern" contract.html docs/Test-Contract.md
rm contract.html

