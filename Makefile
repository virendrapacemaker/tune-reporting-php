# Tune API SDK for PHP
# See LICENSE file for copyright and license details.

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
	php ./examples/TuneExamples.php $(api_key)

# if these fail, you may need to install the helper library - run "make
# tests-install"
tests:
	@printenv | grep API_KEY
	@PATH=vendor/bin:$(PATH) phpunit --strict --stop-on-failure --colors --configuration tests/phpunit.xml;

docs-doxygen:
	sudo rm -fR ./docs/doxygen/*
	sudo doxygen ./docs/Doxyfile
	x-www-browser docs/doxygen/html/index.html

docs-phpdoc:
	sudo rm -fR ./docs/phpdoc/*
	phpdoc -d ./src/ -t ./docs/phpdoc --template="responsive-twig" --title "Tune API SDK for PHP" --sourcecode
	x-www-browser docs/phpdoc/index.html

