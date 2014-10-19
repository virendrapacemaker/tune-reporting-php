<h1>Tune API PHP SDK for PHP 5.3</h1>
<h3>Incorporate Tune API services.</h3>
<h4>Update:  2014-10-18</h4>
<h4>Version: 0.9.3</h4>
===

# Table of Contents #
<ul>
    <li><a href="#introduction">Introduction</a>
        <ul>
            <li><a href="#tune_management_api_service">Tune Management API Service</a>
            </li>
            <li><a href="#advertise_reports_overview">Tune Management API's Advertise Reports Overview</a>
            </li>
            <li><a href="#exporting_advertise_reports">Exporting Advertise Reports</a>
            </li>
        </ul>
    </li>
    <li><a href="#sdk_overview">Tune API SDK Overview</a>
        <ul>
            <li><a href="#tune_api_sdk_contents">SDK Contents</a>
            </li>
            <li><a href="#advertise_reports_classes">Advertise Reports' Classes</a>
            </li>
            <li><a href="#advertise_reports_methods">Advertise Reports' Methods</a>
                <ul>
                    <li><a href="#function_count">Function count()</a>
                    </li>
                    <li><a href="#function_find">Function find()</a>
                    </li>
                    <li><a href="#function_export">Function export()</a>
                    </li>
                    <li><a href="#function_download">Function download()</a>
                    </li>
                    <li><a href="#function_status">Function status()</a>
                    </li>
                    <li><a href="#function_fetch">Function fetch()</a>
                    </li>
                </ul>
            </li>
        </ul>
    </li>

    <li><a href="#sdk_requirements">SDK Requirements</a>
        <ul>
            <li><a href="#sdk_prerequisites">SDK Prerequisites</a>
            </li>
            <li><a href="#generate_api_key">Generate API KEY</a>
            </li>
        </ul>
    </li>

    <li><a href="#sdk_installation">SDK Installation</a>
        <ul>
            <li><a href="#sdk_installation_pear">Via PEAR</a>
            </li>
            <li><a href="#sdk_installation_composer">Via composer</a>
            </li>
            <li><a href="#sdk_installation_zip">Via ZIP file</a>
            </li>
        </ul>
    </li>
    
    <li><a href="#sdk_examples">SDK Examples</a>
    </li>
    
    <li><a href="#sdk_unittests">SDK Unittests</a>
    </li>

    <li><a href="#license">License</a>
    </li>
    <li><a href="#sdk_reporting_issues">Reporting Issues</a>
    </li>

</ul>

<a name="introduction"></a>
## Introduction

<section>
<a name="tune_management_api_service"></a>
#### Tune Management API Service

The MobileAppTracking platform is built from the ground up to use this Management API for all of its data interaction. While the Measurement API consumes data and attributes it, this Management API provides the capabilities to manage everything and consume reporting data.

![Tune Management API Popular Endpoints](https://developers.mobileapptracking.com/wp-content/uploads/management-api-map-600x268.png "Tune Management API Popular Endpoints")

MobileAppTracking’s clients are the advertisers. If you are an advertiser, then you use the MobileAppTracking platform (demand-side). The MobileAppTracking platform uses the advertiser endpoints in the Management API. While partners currently use the MAT Partner Portal (supply-side), we currently don’t provide access to the publisher endpoints because they are still in development. The above diagram only illustrates the advertiser endpoints.
</section>

<section>
<a name="advertise_reports_overview"></a>
#### Tune Management API's Advertise Reports Overview 
<p>&nbsp;</p>
<p>The utility focus of the SDKs is upon the <a href="https://developers.mobileapptracking.com/advertiser-reporting-endpoints/">Advertiser Reporting endpoints</a>. Even though the the breadth of the Management API goes beyond just reports, it is these endpoints that our customers primarily access. The second goal of the SDKs is to assure that our customers&#8217; developers are using best practices in gathering reports in the most optimal way.</p>
<p>The endpoints interfaced by Tune API SDKs provide access in gathering four types of reports:</p>
<dl>
<dt>Logs</dt>
<dd>
Log reports provide measurement records for each Click, Install, Update, Event, Event Item and Postbacks. Instead of being aggregated, the data is on a per transaction / request basis. These logs are used to generate the aggregated data in the Actuals and Cohort reports. Note that we don’t provide Log reports for Impressions and Opens currently.</p>
<p>Log report endpoints include:</p>
<ul>
<li><a href="http://developers.mobileapptracking.com/management-api/explorer/root/endpoint/#/advertiser__stats__clicks/">/advertiser/stats/clicks/</a></li>
<li><a href="http://developers.mobileapptracking.com/management-api/explorer/root/endpoint/#/advertiser__stats__event__items/">/advertiser/stats/event/items/</a></li>
<li><a href="http://developers.mobileapptracking.com/management-api/explorer/root/endpoint/#/advertiser__stats__events/">/advertiser/stats/events/</a></li>
<li><a href="http://developers.mobileapptracking.com/management-api/explorer/root/endpoint/#/advertiser__stats__installs/">/advertiser/stats/installs/</a></li>
<li><a href="http://developers.mobileapptracking.com/management-api/explorer/root/endpoint/#/advertiser__stats__postbacks/">/advertiser/stats/postbacks/</a></li>
<li>/advertiser/stats/updates/</li>
</ul>
</dd>
<dt>Actuals</dt>
<dd>
The Actuals report gives you quick insight into the performance of your apps and advertising partners (publishers). Use this report for reconciliation, testing, debugging, and ensuring that all measurement and attribution continues to operate smoothly. MAT generates this report by aggregating all the logs of each request (MAT updates the report every 5 minutes).</p>
<p>Actuals report endpoint include: <a href="http://developers.mobileapptracking.com/management-api/explorer/root/endpoint/#/advertiser__stats/">/advertiser/stats/</a>
</dd>
<dt>Cohort</dt>
<dd>
The cohort report analyzes user behavior back to click date time (Cohort by Click) or to install date time (Cohort by Install). Based on whether you are viewing the results based on click or install, the data in the report is vastly different.</p>
<p>Cohort report endpoint include: <a href="http://developers.mobileapptracking.com/management-api/explorer/root/endpoint/#/advertiser__stats__ltv/">/advertiser/stats/ltv/</a>
</dd>
<dt>Retention</dt>
<dd>
The Retention Report shows you how many of your installed users open or engage with your app over time (how users continue to get value from the app). Retention Reports are particularly good for evaluating the quality of users as opposed to the quantity of users (as in the case of user acquisition campaigns). For more information about retention reports, please visit Running Retention Reports.</p>
<p>Retention report endpoint include: <a href="http://developers.mobileapptracking.com/management-api/explorer/root/endpoint/#/advertiser__stats__retention/">/advertiser/stats/retention/</a>
</dd>
</dl>

![Management API Advertiser Reports covered by Tune API SDK](http://developers.mobileapptracking.com/wp-content/uploads/management-api-report-endpoints.png "Management API Advertiser Reports covered by Tune API SDK")
</section>

<section>
<a name="exporting_advertise_reports"></a>
#### Exporting Advertise Reports

Currently, there are two different ways of handling export of advertiser reports. Both approaches requires (A) an action to request an report to be exported and (B) another action to request status of the report if ready to be exported, and if ready then provide url to download completed reports.

Logs and Actuals reports all request an export using action <code>find_export_queue.json</code> which returns an <code>job_id</code>. This is then requires passing <code>job_id</code> onto another endpoint <code>Export::download.json</code> which performs the status checking and report url retrieval.

![Exporting Logs and Actuals Reports](http://developers.mobileapptracking.com/wp-content/uploads/management-api-report-exports1.png "Exporting Logs and Actuals Reports")

Cohort and Retention reports all request an export using action <code>export.json</code> which returns an <code>job_id</code>. This is then requires passing <code>job_id</code> onto another action <code>status.json</code> which performs the status checking and report url retrieval.

![Exporting Cohort and Retention Reports](http://developers.mobileapptracking.com/wp-content/uploads/management-api-insight-report-exports.png "Exporting Cohort and Retention Reports")
</section>

<a name="sdk_overview"></a>
## Tune API SDK Overview

<section>
<a name="tune_api_sdk_contents"></a>
#### SDK Contents

The SDKs contents typically provide <strong>library</strong>, <strong>examples</strong>, <strong>unittests</strong>, and <strong>documentation</strong>.

![Package tune-api-php sdk contents](http://developers.mobileapptracking.com/wp-content/uploads/tune-api-php-package.png "Package tune-api-php sdk contents")

The <strong>library</strong> folder provides the Tune API SDK, which currently only contains Tune <strong>/lib/Tune/Management/</strong> sub-module contain Advertiser Reports via classes within <strong>/lib/Tune/Management/Api/</strong> and its <strong>/lib/Tune/Management/Shared/</strong> sub-module to provide client access to Tune Management API. There another <strong>/lib/Tune/Shared</strong> sub-module with the intent that there will be other Tune API services supported by this SDK.

![Tune API Modules including Management API and Shared](http://developers.mobileapptracking.com/wp-content/uploads/tune-api-major-blocks.png "Tune API Modules including Management API and Shared")

Within packaging of <strong>/lib/Tune/Management/</strong>, the sub-module labeled <strong>API</strong> provides classes that defines data access to Management API's advertiser records endpoints. All these classes are intended for public interfacing by another service.

![Tune Management module with API sub-module providing Advertiser Reports access.](http://developers.mobileapptracking.com/wp-content/uploads/tune-api-management-api-block.png "Tune Management module with API sub-module providing Advertiser Reports access.")

Within packaging of <strong>/lib/Tune/Management/</strong>, the sub-module labeled <strong>Shared</strong> provides classes that defines data access to Management API's advertiser records endpoints. All the classes within its sub-module <strong>Records</strong> are abstract and define the unique access signature for each advertising reporting endpoints. The classes within sub-module Service provide <code>TuneManagementClient</code> for defining basic access to Tune Management API, <code>TuneManagementBase</code> defines basic functionality of Tune Management API endpoints, and <code>TuneResponse</code> will be populated with response date returned from Tune Management Service and returned by all API functions.

![Tune Management module with Shared sub-module providing Tune Management API client and abstract classes defining Advertiser Reports access.](http://developers.mobileapptracking.com/wp-content/uploads/tune-api-management-shared-block.png "Tune Management module with Shared sub-module providing Tune Management API client and abstract classes defining Advertiser Reports access.")

It is the intention that there will be other Tune service APIs to be made accessible via this SDK other than just Tune Management API. The purpose of the general sub-module Shared is to provide functionality that would be shared by all Tune API services. Currently only custom exceptions are provided: <code>TuneSdkException</code> for error conditions discovered within the SDK, and <code>TuneServiceException</code> for error conditions returned externally from the Tune API service.

![Tune module with Shared sub-module containing custom exceptions.](http://developers.mobileapptracking.com/wp-content/uploads/tune-api-shared-block.png "Tune module with Shared sub-module containing custom exceptions.")

The following diagram shows the layering from the foundation using the Management API Client <code>TuneManagementClient</code>, foundation of building a request with <code>TuneManagementBase</code>, applying the base features of advertiser reporting with abstract class <code>ReportsBase</code> and narrowing specific reporting types specific to <strong>Logs</strong>, <strong>Actuals</strong>, and <strong>Insights</strong>, to define the publicly interfacing report classes within <strong>/lib/Tune/Management/Api</strong>:

![Tune Management sub-module functionality layering to provide Advertiser Reports access through Reports classes.](http://developers.mobileapptracking.com/wp-content/uploads/tune-api-management-layers.png "Tune Management sub-module functionality layering to provide Advertiser Reports access through Reports classes.")
</section>

<section>
<a name="advertise_reports_classes"></a>
#### Advertise Report Classes
</section>

<section>
<a name="advertise_reports_methods"></a>
#### Advertise Reports Classes Methods

The benefit of using Tune API SDKs is it provides the same interface across all advertiser reports. The following class diagram lists what are all the expected functions. The signature of the expected parameters for each function will be consistent with the action it is interfacing.

![Tune Management API Report classes' available methods.](http://developers.mobileapptracking.com/wp-content/uploads/management-sdk-class.png "Tune Management API Report classes' available methods.")

<hr align="left" width="50%">

<a name="function_count"></a>
<h5>Function <code>count()</code></h5>

Function <code>count()</code> finds all existing records matching provided filter criteria and returns total count. It returns a populated instance of <code>class Response</code>, class of Tune API SDK, with <strong>record count</strong>.

```php
    $yesterday      = date('Y-m-d', strtotime("-1 days"));
    $start_date     = "{$yesterday} 00:00:00";
    $end_date       = "{$yesterday} 23:59:59";

    $clicks = new Clicks($api_key, $validate = true);

    echo "======================================================" . PHP_EOL;
    echo "= advertiser/stats/clicks/count.json request =" . PHP_EOL;
    $response = $clicks->count(
        $start_date,
        $end_date,
        $filter              = null,
        $response_timezone   = "America/Los_Angeles"
    );

    if ($response->getHttpCode() != 200) {
        throw new \Exception(
            sprintf("Failed: %d: %s", $response->getHttpCode(), print_r($response->getErrors()))
        );
    }
    echo "= Count:" . $response->getData() . PHP_EOL;
```

![Function count()](http://developers.mobileapptracking.com/wp-content/uploads/management-api-action-count.png "Function count()")

<hr align="left" width="50%">

<a name="function_find"></a>
<h5>Function <code>find()</code></h5>

Function <code>find()</code> gathers all existing records that match filter criteria and returns an array of found model data. Even though calling the action <code>find.json</code> is commonly used for gathering data, however it is not the preferred way of gathering full reports. It returns a populated instance of <code>class Response</code> with <strong>records</strong>.

```php
    $yesterday      = date('Y-m-d', strtotime("-1 days"));
    $start_date     = "{$yesterday} 00:00:00";
    $end_date       = "{$yesterday} 23:59:59";

    $clicks = new Clicks($api_key, $validate = true);

    echo "======================================================" . PHP_EOL;
    echo "= advertiser/stats/clicks/find.json request =" . PHP_EOL;
    $response = $clicks->find(
        $start_date,
        $end_date,
        $filter              = null,
        $fields              = "created,site.name,campaign.name,publisher.name"
        . ",is_unique,publisher_ref_id,country.name,region.name"
        . ",site_id,campaign_id,publisher_id"
        . ",agency_id,country_id,region_id",
        $limit               = 5,
        $page                = null,
        $sort                = array("created" => "DESC"),
        $response_timezone   = "America/Los_Angeles"
    );

    echo "= advertiser/stats/clicks/find.json response:" . PHP_EOL;
    echo print_r($response, true) . PHP_EOL;

    if ($response->getHttpCode() != 200) {
        throw new \Exception(
            sprintf("Failed: %d: %s", $response->getHttpCode(), print_r($response->getErrors()))
        );
    }
```

![Function find()](http://developers.mobileapptracking.com/wp-content/uploads/management-api-action-find.png "Function find()")

<hr align="left" width="50%">

<a name="function_export"></a>
<h5>Function <code>export()</code></h5>

Function <code>export()</code> provides the same signature as function find(), accept parameters <code>limit</code> and <code>page</code>, because this function's intent is to request export of a full report. It returns a populated instance of <code>class Response</code> with <strong>job identifier</strong> of report in queue and ready to be processed. Format of content can be requested to be either CSV or JSON.

```php
    $yesterday      = date('Y-m-d', strtotime("-1 days"));
    $start_date     = "{$yesterday} 00:00:00";
    $end_date       = "{$yesterday} 23:59:59";

    $clicks = new Clicks($api_key, $validate = true);

    echo "======================================================" . PHP_EOL;
    echo "= advertiser/stats/clicks/find_export_queue.json request =" . PHP_EOL;
    $response = $clicks->export(
        $start_date,
        $end_date,
        $filter              = null,
        $fields              = "created,site.name,campaign.name,publisher.name"
        . ",is_unique,publisher_ref_id,country.name,region.name"
        . ",site_id,campaign_id,publisher_id"
        . ",agency_id,country_id,region_id",
        $format              = "csv",
        $response_timezone   = "America/Los_Angeles"
    );

    echo "= advertiser/stats/clicks/find_export_queue.json response:" . PHP_EOL;
    echo print_r($response, true) . PHP_EOL;

    if ($response->getHttpCode() != 200) {
        throw new \Exception(
            sprintf("Failed: %d: %s", $response->getHttpCode(), print_r($response->getErrors()))
        );
    }
    echo "= Job ID: " . print_r($response->getData(), true) . PHP_EOL;

    $job_id = $response->getData();
```

![Function export()](http://developers.mobileapptracking.com/wp-content/uploads/management-api-action-export.png "Function export()")

<hr align="left" width="50%">

<a name="function_download"></a>
<h5>Function <code>download()</code></h5>

As discussed in <a href="#exporting-reports">Exporting Advertise Reports</a>, dependent upon the endpoint it requires using the appropriate method for gathering report export status. For Logs and Actuals records classes, use <code>Export::download()</code>; and for Cohort and Retention record classes, use its own function <code>status()</code>. It returns a populated instance of <code>class Response</code> with <strong>report url</strong> ready for downloading only if status indicates "complete"; otherwise if "pending" or "running", then no data is returned. If not "complete", then this function call is required again until it is.

```php
    $export = new Export($api_key);

    $status = null;
    $response = null;
    $attempt = 0;

    while (true) {
        $response = $export->download($job_id);

        if (is_null($response)) {
            throw new \Exception("No response returned from export request.");
        }

        $request_url = $response->getRequestUrl();
        $response_http_code = $response->getHttpCode();

        if (is_null($response->getData())) {
            throw new \Exception(
                "No response data returned from export. Request URL: '{$request_url}'"
            );
        }

        if ($response_http_code != 200) {
            throw new \Exception(
                "Service failed request: {$response_http_code}. Request URL: '{$request_url}'"
            );
        }

        $status = $response->getData()["status"];
        if ($status == "fail" || $status == "complete") {
            break;
        }

        $attempt += 1;
        echo "= attempt: {$attempt}, response: " . print_r($response, true) . PHP_EOL;
        sleep(10);
    }

    if ($status != "complete") {
        throw new \Exception(
            "Export request '{$status}':, response: " . print_r($response, true)
        );
    }

    $report_url = $response->getData()["data"]["url"];
```

![Function download()](http://developers.mobileapptracking.com/wp-content/uploads/management-api-action-download.png "Function download()")

<hr align="left" width="50%">

<a name="function_status"></a>
<h5>Function <code>status()</code></h5>


<hr align="left" width="50%">

<a name="function_fetch"></a>
<h5>Function <code>fetch()</code></h5>

Function <code>fetch()</code> is a helper function that creates a threaded worker that handles the status request appropriate to it class. This function handles the polling of the service waiting for status of "complete" and its "report url". Upon completion, fetch downloads data into a reader that parses the contents that is appropriate requested content format type, CSV or JSON.

```php
    $export = new Export($api_key);

    $csv_report_reader = $export->fetch(
        $job_id,
        $report_format = "csv",
        $verbose = true,
        $sleep = 10
    );

    $csv_report_reader->read();
    $csv_report_reader->prettyPrint($limit = 5);
```
 
![Function fetch()](http://developers.mobileapptracking.com/wp-content/uploads/management-api-action-fetch.png "Function fetch()")
</section>

<section>
<a name="sdk_parameters"></a>
#### Common SDK Parameters

Most of the functions provided for the record classes have the following parameters in common. All of these parameters are based upon knowledge of available fields for that endpoint, which are discovered using endpoint's functions <code>define()</code> and <code>fields()</code>.

Parameter <code>fields</code> can either be an array of field names, or a string containing comma-delimited field named:

![Parameter fields](http://developers.mobileapptracking.com/wp-content/uploads/parameter-fields.png "Parameter fields")

Parameter <code>group</code> is the same as parameter <code>fields</code> can either be an array of field names, or a string containing comma-delimited field named:

![Parameter group](http://developers.mobileapptracking.com/wp-content/uploads/parameter-group.png "Parameter group")

Parameter <code>sort</code> is a dictionary (associative array), where the key is the field name and value is the expected sort direction of either <code>"DESC"</code> or <code>"ASC"</code>.

![Parameter sort](http://developers.mobileapptracking.com/wp-content/uploads/parameter-sort.png "Parameter sort")

Parameter <code>filter</code> is a string that contains a set of query expressions based upon matching conditions to endpoint's fields. It is especially important to to provide an invalid field name because that will cause request to fail.

![Parameter filter](http://developers.mobileapptracking.com/wp-content/uploads/parameter-filter.png "Parameter filter")
</section>


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
## SDK Installation

You can install **tune-api-php** via PEAR or by downloading the source.

<a name="sdk_installation_pear"></a>
#### Via PEAR (>= 1.9.3):

*TODO*

<a name="sdk_installation_composer"></a>
#### Via Composer:

*TODO*

<a name="sdk_installation_zip"></a>
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

<a name="sdk_reporting_issues"></a>
## Reporting Issues

We would love to hear your feedback. Report issues using the [Github
Issue Tracker](https://github.com/MobileAppTracking/tune-api-php/issues) or email
[sdk@tune.com](mailto:sdk@tune.com).
