<h2>tune-api-php</h2>
<h2>Tune API SDK for PHP 5.3</h2>
<h3>Incorporate Tune API services.</h3>
<h4>Update:  $Date: 2014-11-21 17:34:43 $</h4>
<h4>Version: 0.9.16</h4>
===

### Overview

PHP helper library for Tune API services.

The utility focus of this SDK is upon the Advertiser Reporting endpoints.

Even though the the breadth of the Management API goes beyond just reports, it is these endpoints that our customers primarily access.

The second goal of the SDKs is to assure that our customers’ developers are using best practices in gathering reports in the most optimal way.

### Documentation

Please see documentation here:

[Tune API SDKs](https://developers.mobileapptracking.com/tune-api-sdks/)

<a name="sdk_requirements"></a>
### SDK Requirements

<a name="sdk_prerequisites"></a>
#### Prerequisites

    * PHP >= 5.2.3
    * The PHP JSON extension
    * The PHP Curl extension

Examples requires setting within your `php.ini` parameter `date.timezone`:

```bash
[Date]
; Defines the default timezone used by the date functions
; http://php.net/date.timezone
date.timezone = "America/Los_Angeles"
```

<a name="generate_api_key"></a>
#### Generate API Key

To use SDK, it requires you to [Generate API Key](http://developers.mobileapptracking.com/generate-api-key/)

<a name="sdk_installation"></a>
### Installation

You can install **tune-api-php** via composer or by downloading the source.

<a name="sdk_installation_composer"></a>
#### Via Composer:

Add the following script within your `composer.json` file to include `tune-api-php`:

```json
{
    "require": {
        "mobileapptracking/tune-api-php": "dev-master"
    },
    "require-dev": {
        "mobileapptracking/tune-api-php": "dev-master"
    },
    "repositories": [
        {
            "type": "vcs",
            "url": "git@github.com:MobileAppTracking/tune-api-php.git"
        }
    ],
}
```

<a name="sdk_installation_zip"></a>
#### Via ZIP file:

[Click here to download the source code
(.zip)](https://github.com/MobileAppTracking/tune-api-php/archive/master.zip) for `tune-api-php`.

Once you download the library, move the tune-api-php folder to your project
directory and then include the library file:

```php
    require '/path/to/tune-api-php/lib/TuneApi.php';
```

and you're good to go!

<a name="sdk_code_samples"></a>
### Code Samples

<a name="sdk_examples"></a>
#### SDK Examples

Run the following script to view execution of all examples:
```bash
    $ make api_key=[API_KEY] examples
```

<a name="sdk_unittests"></a>
#### SDK Unittests

Run the following script to view execution of all unittests:
```bash
    $ make api_key=[API_KEY] tests
```

<a name="sdk_docs_phpdoc"></a>
#### SDK Documentation -- phpDocumentor

The following will generate [PhpDocumentor](http://en.wikipedia.org/wiki/PhpDocumentor) from PHP codebase:

```bash
    $ make docs-phpdoc
```

<a name="sdk_docs_doxygen"></a>
#### SDK Documentation -- Doxygen

The following will generate [Doxygen](http://en.wikipedia.org/wiki/Doxygen) from PHP codebase:

```bash
    $ make docs-doxygen
```

Requires installation of [Doxygen](http://www.stack.nl/~dimitri/doxygen/index.html).

<a name="license"></a>
### License

[MIT License](http://opensource.org/licenses/MIT)

<a name="sdk_reporting_issues"></a>
### Reporting Issues

Report issues using the [Github Issue Tracker](https://github.com/MobileAppTracking/tune-api-php/issues) or Email [sdk@tune.com](mailto:sdk@tune.com).
