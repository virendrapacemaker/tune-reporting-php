#!/bin/bash
phpcs --error-severity=1 --warning-severity=4 --tab-width=4 --standard=PSR2 ./lib
phpcs --error-severity=1 --warning-severity=4 --tab-width=4 --standard=PSR2 ./examples
phpcs --error-severity=1 --warning-severity=4 --tab-width=4 --standard=PSR2 ./unittests
