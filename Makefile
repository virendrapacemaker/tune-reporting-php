# Tune API helper library.
# See LICENSE file for copyright and license details.

export API_KEY=$(api_key)

COMPOSER = $(shell which composer)
ifeq ($(strip $(COMPOSER)),)
	COMPOSER = php composer.phar
endif

all: test

clean:
	sudo rm -rf Vendors

tests-install: clean
	sudo pear upgrade PHP_CodeSniffer
	if [ -f composer.lock ] ; then sudo rm composer.lock ; fi
	if [ -f composer.phar ] ; then sudo rm composer.phar ; fi
	wget http://getcomposer.org/composer.phar
	sudo $(COMPOSER) install

install:

analysis:
	phpcs --error-severity=1 --warning-severity=1 --tab-width=4 --standard=PSR2 ./src
	
examples-analysis: analysis
	phpcs --error-severity=1 --warning-severity=1 --tab-width=4 --standard=PSR2 ./examples
	
examples:
	php ./examples/TuneExamples.php $(api_key)
	
tests-analysis: analysis
	phpcs --error-severity=1 --warning-severity=1 --tab-width=4 --standard=PSR2 ./tests

# if these fail, you may need to install the helper library - run "make
# tests-install"
tests:
	@printenv | grep API_KEY
	@PATH=vendor/bin:$(PATH) phpunit --strict --colors --configuration tests/phpunit.xml;

docs:
	sudo rm -fR ./doc/doxygen/*
	sudo doxygen ./doc/Doxyfile

.PHONY: all clean dist test examples docs test-install analysis test-analysis examples-analysis
