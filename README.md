<h1>tune-api-php</h1>
<h2>Tune API PHP SDK for PHP 5.3</h2>
<h3>Incorporate Tune API services.</h3>
<h4>Update:  2014-10-23</h4>
<h4>Version: 0.9.6</h4>
===

## Overview
Tune API client for PHP developers.

The utility focus of the SDKs is upon the Advertiser Reporting endpoints. Even though the the breadth of the Management API goes beyond just reports, it is these endpoints that our customers primarily access. The second goal of the SDKs is to assure that our customers’ developers are using best practices in gathering reports in the most optimal way.

## Documentation

Please see documentation here:

[Tune API SDKs](https://developers.mobileapptracking.com/tune-api-sdks/)

<a name="sdk_requirements"></a>
## SDK Requirements

<a name="sdk_prerequisites"></a>
#### Prerequisites

    * PHP >= 5.2.3
    * The PHP JSON extension
    * The PHP Curl extension
    * The PHP pthread extension

<a name="generate_api_key"></a>
#### Generate API Key

To use SDK, it requires you to [Generate API Key](http://developers.mobileapptracking.com/generate-api-key/)

<a name="sdk_installation"></a>
## Installation

You can install **tune-api-php** via composer or by downloading the source.

<a name="sdk_installation_composer"></a>
#### Via Composer:

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


<a name="sdk_examples"></a>
## SDK Examples

```bash
./tune_examples.sh [API_KEY]
```

<a name="sdk_unittests"></a>
## SDK Unittests

```bash
cd unittests/
./tune_unittests.sh [API_KEY]
```

<a name="license"></a>
## License

[MIT License](http://opensource.org/licenses/MIT)

<a name="sdk_reporting_issues"></a>
## Reporting Issues

Report issues using the [Github Issue Tracker](https://github.com/MobileAppTracking/tune-api-python/issues) or Email [sdk@tune.com](mailto:sdk@tune.com).
