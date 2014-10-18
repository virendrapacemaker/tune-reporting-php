
## Overview

The utility focus of the SDKs is upon the Advertiser Reporting endpoints. Even though the the breadth of the Management API goes beyond just reports, it is these endpoints that our customers primarily access. The second goal of the SDKs is to assure that our customers’ developers are using best practices in gathering reports in the most optimal way.

## Installation

You can install **tune-api-php** via PEAR or by downloading the source.

#### Via PEAR (>= 1.9.3):

*TODO*

#### Via Composer:

*TODO*

#### Via ZIP file:

[Click here to download the source
(.zip)](https://github.com/MobileAppTracking/tune-api-php/archive/master.zip) which includes all
dependencies.

Once you download the library, move the tune-api-php folder to your project
directory and then include the library file:

```php
    require '/path/to/tune-api-php/lib/TuneApi.php';
```

and you're good to go!

## Generate API Key

To use SDK, it requires you to [Generate API Key](http://developers.mobileapptracking.com/generate-api-key/)

## Examples

```bash
./tune_examples.sh [API_KEY]
```

## Unittests

```bash
cd unittests/
./tune_unittests.sh [API_KEY]
```

## Prerequisites

* PHP >= 5.2.3
* The PHP JSON extension
* The PHP Curl extension
* The PHP pthread extension

## Reporting Issues

We would love to hear your feedback. Report issues using the [Github
Issue Tracker](https://github.com/MobileAppTracking/tune-api-php/issues) or email
[sdk@tune.com](mailto:sdk@tune.com).
