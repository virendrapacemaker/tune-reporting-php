#   Makefile
#
#   Copyright (c) 2014 Tune, Inc
#   All rights reserved.
#
#   Permission is hereby granted, free of charge, to any person obtaining a copy
#   of this software and associated documentation files (the "Software"), to deal
#   in the Software without restriction, including without limitation the rights
#   to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
#   copies of the Software, and to permit persons to whom the Software is
#   furnished to do so, subject to the following conditions:
#
#   The above copyright notice and this permission notice shall be included in
#   all copies or substantial portions of the Software.
#
#   THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
#   IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
#   FITNESS FOR A PARTICULAR PURPOSE AND NON-INFRINGEMENT. IN NO EVENT SHALL THE
#   AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
#   LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
#   OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
#   THE SOFTWARE.
#
# category  Tune
# package   tune.tests
# author    Jeff Tanner <jefft@tune.com>
# copyright 2014 Tune (http://www.tune.com)
# license   http://opensource.org/licenses/MIT The MIT License (MIT)
# version   $Date: 2014-12-31 15:52:00 $
# link      https://developers.mobileapptracking.com/tune-reporting-sdks
#

.PHONY: all clean dist analysis tests examples docs-doxygen docs-phpdoc tests-install

export API_KEY=$(api_key)

COMPOSER = $(shell which composer)
ifeq ($(strip $(COMPOSER)),)
	COMPOSER = php composer.phar
endif

all: test

clean:
	sudo rm -fR ./docs/doxygen/*
	sudo rm -fR ./docs/phpdoc/*
	sudo rm -rf Vendors

install:
	if [ -f composer.lock ] ; then sudo rm composer.lock ; fi
	if [ -f composer.phar ] ; then sudo rm composer.phar ; fi
	wget http://getcomposer.org/composer.phar
	sudo $(COMPOSER) install

tests-install:
	sudo pear upgrade PHP_CodeSniffer
	sudo pear upgrade phpdoc/phpDocumentor

analysis:
	phpcs --error-severity=1 --warning-severity=1 --tab-width=4 --standard=PSR2 ./src

examples:
	php ./examples/TuneReportingExamples.php $(api_key)

# if these fail, you may need to install the helper library - run "make
# tests-install"
tests:
	@printenv | grep API_KEY
	@PATH=vendor/bin:$(PATH) phpunit --strict --stop-on-failure --colors --configuration ./tests/phpunit.xml

docs-doxygen:
	sudo rm -fR ./docs/doxygen/*
	sudo doxygen ./docs/Doxyfile
	x-www-browser docs/doxygen/html/index.html

docs-phpdoc:
	sudo rm -fR ./docs/phpdoc/*
	phpdoc -d ./src/ -t ./docs/phpdoc --template="responsive-twig" --title "Tune Reporting API SDK for PHP" --sourcecode
	x-www-browser docs/phpdoc/index.html
