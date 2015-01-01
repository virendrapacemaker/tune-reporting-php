<h2>tune-reporting-php</h2>
<h2>TUNE Reporting SDK for PHP 5.3</h2>
<h3>Incorporate TUNE Reporting services.</h3>
<h4>Update:  $Date: 2014-12-31 15:52:00 $</h4>
<h4>Version: 0.9.27</h4>
===

<a id="TOP"></a>
### Table of Contents

<ul>
    <li><a href="#sdk_overview">Overview</a>
        <ul>
            <li><a href="#sdk_overview_available">Available TUNE Reporting SDKs</a></li>
            <li><a href="#sdk_overview_mobile">TUNE SDKs for Mobile Apps</a></li>
            <li><a href="#sdk_overview_dev_community">Developers Community</a></li>
        </ul>
    </li>
    <li><a href="#sdk_install">SDK Installation</a>
        <ul>
            <li><a href="#sdk_install_prereq">Prerequisites</a>
                <ul>
                    <li><a href="#sdk_install_prereq_env">Environment</a></li>
                    <li><a href="#sdk_install_prereq_ini">php.ini</a></li>
                    <li><a href="#sdk_install_prereq_apikey">Environment</a></li>
                </ul>
            </li>
            <li><a href="#sdk_install_choices">Choices</a>
                <ul>
                    <li><a href="#sdk_install_method_composer">Composer</a></li>
                    <li><a href="#sdk_install_method_zip">ZIP</a></li>
                    <li><a href="#sdk_prerequisites_api_key">Environment</a></li>
                </ul>
            </li>
            <li><a href="#sdk_install_library">Library</a></li>
            <li><a href="#sdk_install_config">Configuration</a></li>
        </ul>
    </li>

    <li><a href="#sdk_gendoc">SDK Generated Documentation</a>
        <ul>
            <li><a href="#sdk_gendoc_doxygen">Doxygen</a></li>
            <li><a href="#sdk_gendoc_phpdoc">phpDocumentor</a></li>
        </ul>
    </li>

    <li><a href="#sdk_advertiser_reporting_overview">Advertiser Reporting Overview</a>
    </li>

    <li><a href="#sdk_exporting_reports">Exporting Advertiser Reports</a>
    </li>

    <li><a href="#sdk_sources">SDK Sources</a>
        <ul>
            <li><a href="#sdk_sources_lib">Library</a></li>
            <li><a href="#sdk_sources_examples">Examples</a></li>
            <li><a href="#sdk_sources_tests">Tests</a></li>
        </ul>
    </li>

    <li><a href="#sdk_classes">SDK Classes</a>
        <ul>
            <li><a href="#sdk_classes_service">TUNE Management Service Classes</a></li>
            <li><a href="#sdk_report_readers">Helper Classes</a></li>
            <li><a href="#sdk_classes_exceptions">Exception Classes</a></li>
        </ul>
    </li>

    <li>
        <a href="#sdk_methods">Advertiser Reporting Methods</a>
        <ul>
            <li><a href="#sdk_method_count"><code>count()</code></a></li>
            <li><a href="#sdk_method_find"><code>find()</code></a></li>
            <li><a href="#sdk_method_export"><code>export()</code></a></li>
            <li><a href="#sdk_method_status"><code>status()</code></a></li>
            <li><a href="#sdk_method_fetch"><code>fetch()</code></a></li>
            <li><a href="#sdk_method_fields"><code>fields()</code></a></li>
            <li><a href="#sdk_method_define"><code>define()</code></a></li>
        </ul>
    </li>

    <li><a href="#sdk_reporting_fields">Advertiser Reporting Fields</a>
    </li>

    <li>
        <a href="#sdk_parameters">Advertiser Reporting Parameters</a>
        <ul>
            <li><a href="#sdk_parameter_fields"><code>fields</code></a></li>
            <li><a href="#sdk_parameter_group"><code>group</code></a></li>
            <li><a href="#sdk_parameter_sort"><code>sort</code></a></li>
            <li><a href="#sdk_parameter_filter"><code>filter</code></a></li>
        </ul>
    </li>

    <li><a href="#sdk_license">MIT License</a>
    </li>

    <li><a href="#sdk_issues">SDK Issues</a>
    </li>
</ul>

<p>
<a href="#TOP">
<img alt="Return to Top" src="https://raw.githubusercontent.com/MobileAppTracking/tune-reporting-php/master/docs/images/b_top.gif" border="0">
</a>
</p>

<!-- Overview -->

<a id="sdk_overview" name="sdk_overview"></a>
### Overview

The **TUNE Reporting SDKs** addressed in this posting are for creating hosted applications which require handling requests to **TUNE Management API services** with utility focus is upon Advertiser Reporting endpoints.

Even though the the breadth of the Management API goes beyond just reports, it is these reporting endpoints that our customers primarily access.

The second goal of the SDKs is to assure that our customers’ developers are using best practices in gathering reports in the most optimal way.

<a id="sdk_overview_available" name="sdk_overview_available"></a>
#### Available TUNE Reporting SDKs

Supported programming languages for TUNE Reporting SDKs are:

<ul>
    <li><b>PHP</b>: <a href="https://github.com/MobileAppTracking/tune-reporting-php" target="_blank">tune-reporting-php</a></li>
    <li><b>Python</b>: <a href="https://github.com/MobileAppTracking/tune-reporting-python" target="_blank">tune-reporting-python</a></li>
    <li><b>Java</b>: <a href="https://github.com/MobileAppTracking/tune-reporting-java" target="_blank">tune-reporting-java</a></li>
    <li><b>Node.js</b>: <a href="https://github.com/MobileAppTracking/tune-reporting-node" target="_blank">tune-reporting-node</a></li>
    <li><b>Go</b>: Coming soon</li>
    <li><b>C#</b>: Coming soon</li>
</ul>

<a id="sdk_overview_mobile" name="sdk_overview_mobile"></a>
#### TUNE SDKs for Mobile Apps

The **TUNE Reporting SDKs** should absolutely not be included within Mobile Apps.

All information pertaining to **TUNE SDKs for Mobile Apps** are found [here](http://developers.mobileapptracking.com/sdks/).

<a id="sdk_overview_dev_community" name="sdk_overview_dev_community"></a>
#### Developers Community

Developer Community portal for MobileAppTracking™ (MAT), the industry leader in mobile advertising attribution and analytics. From API documentation to best practices, get everything you need to be successful with MAT.

[https://developers.mobileapptracking.com](https://developers.mobileapptracking.com)

Additional positions on TUNE Reporting SDKs can be found here:

[https://developers.mobileapptracking.com/tune-reporting-sdks/](https://developers.mobileapptracking.com/tune-reporting-sdks/)

<p>
<a href="#TOP">
<img alt="Return to Top" src="https://raw.githubusercontent.com/MobileAppTracking/tune-reporting-php/master/docs/images/b_top.gif" border="0">
</a>
</p>

<!-- Installation -->

<a id="sdk_install" name="sdk_install"></a>
### SDK Installation

This section detail what is required to use this SDK and how to install it for usage.

<a id="sdk_install_prereq" name="sdk_install_prereq"></a>
#### Installation Prerequisites

<a id="sdk_install_prereq_env" name="sdk_install_prereq_env"></a>
##### Environment

These are the basic requirements to use this SDK:

    * PHP >= 5.2.3
    * PHP Curl extension -- Connect to TUNE Management API Service.
    * PHP JSON extension -- TUNE Mangement API response is JSON.
    * PHPUnit -- Execute SDK tests.

<a id="sdk_install_prereq_ini" name="sdk_install_prereq_ini"></a>
##### Prepare `php.ini`

Examples requires setting within `php.ini` its parameter `date.timezone`.

The following is an example of setting `date.timezone` using a value from [List of Supported Timezones](http://php.net/manual/en/timezones.php).

```bash
[Date]
; Defines the default timezone used by the date functions
; http://php.net/date.timezone
date.timezone = "America/Los_Angeles"
```

<a id="sdk_install_prereq_apikey" name="sdk_install_prereq_apikey"></a>
##### Generate API Key

To use SDK to access Advertiser Reporting endpoints of TUNE Management API, it requires a MobileAppTracking API Key: [Generate API Key](http://developers.mobileapptracking.com/generate-api-key/).

<a id="sdk_install_choices" name="sdk_install_choices"></a>
#### Installation Choices

You can install this either via **composer** or by downloading the **ZIP** source.

<a id="sdk_install_method_composer" name="sdk_install_method_composer"></a>
##### Via Composer:

Add the following script within your `composer.json` file to include `tune-reporting-php`:

```json
{
    "require": {
        "mobileapptracking/tune-reporting-php": "dev-master"
    },
    "require-dev": {
        "mobileapptracking/tune-reporting-php": "dev-master"
    },
    "repositories": [
        {
            "type": "vcs",
            "url": "git@github.com:MobileAppTracking/tune-reporting-php.git"
        }
    ],
}
```

<a id="sdk_install_method_zip" name="sdk_install_method_zip"></a>
##### Via ZIP file:

[Click here to download the source code
(.zip)](https://github.com/MobileAppTracking/tune-reporting-php/archive/master.zip) for `tune-reporting-php`.

<a id="sdk_install_library" name="sdk_install_library"></a>
#### Include Library:

Once you download the library, move the tune-reporting-php folder to your project
directory and then include the library file:

```php
    require '/path/to/tune-reporting-php/lib/TuneReporting.php';
```

<a id="sdk_install_config" name="sdk_install_config"></a>
#### Configuration

In the root folder, the TUNE Reporting SDK configuration is set within file ```./tune_reporting_sdk.config```.

With generated API_KEY from TUNE MobileAppTracking Platform account, replace `API_KEY`.

```
[TUNE_REPORTING]
; Tune MobileAppTracking Platform generated API Key.
tune_reporting_api_key_string=API_KEY
; Validate use Tune Management API fields used within action parameters.
tune_reporting_verify_fields_boolean=false
; Tune reporting export status sleep (seconds).
tune_reporting_export_status_sleep_seconds=10
; Tune reporting export fetch timeout (seconds).
tune_reporting_export_status_timeout_seconds=240
```

and you're good to go!

<p>
<a href="#TOP">
<img alt="Return to Top" src="https://raw.githubusercontent.com/MobileAppTracking/tune-reporting-php/master/docs/images/b_top.gif" border="0">
</a>
</p>

<!-- Generated Documentation -->

<a id="sdk_gendoc" name="sdk_gendoc"></a>
### SDK Generated Documentation

SDK code is well commented and to see full documentation of its source using the provided Makefile commands that initiate code documentation generators.
<a id="sdk_gendoc_doxygen" name="sdk_gen_doc_doxygen"></a>
#### Doxygen

The following will generate <a href="http://en.wikipedia.org/wiki/Doxygen" title="Doxygen" target="_blank">Doxygen</a> from PHP codebase:

This code documentation generation requires installation of [Doxygen](http://www.stack.nl/~dimitri/doxygen/index.html).

```bash
    $ make docs-doxygen
```

<a href="https://raw.githubusercontent.com/MobileAppTracking/tune-reporting-php/master/docs/images/TuneReporting_PHP_Doxygen.png">
<img src="https://raw.githubusercontent.com/MobileAppTracking/tune-reporting-php/master/docs/images/TuneReporting_PHP_Doxygen-400x235.png" alt="TUNE-Reporting PHP Doxygen Generated" width="400" height="235">
</a>

<a id="sdk_gendoc_phpdoc" name="sdk_gen_doc_phpdoc"></a>
#### phpDocumentor

The following will generate <a href="http://en.wikipedia.org/wiki/PhpDocumentor" title="PhpDocumentor" target="_blank">PhpDocumentor</a> from PHP codebase:

This code documentation generation requires installation of [phpDocumentatior](http://www.phpdoc.org/).

<pre lang="bash">
    $ make docs-phpdoc
</pre>

<a href="https://raw.githubusercontent.com/MobileAppTracking/tune-reporting-php/master/docs/images/TuneReporting_PHP_phpDoc.png">
<img src="https://raw.githubusercontent.com/MobileAppTracking/tune-reporting-php/master/docs/images/TuneReporting_PHP_phpDoc-400x239.png" alt="TUNE-Reporting PHP phpDocumentor Generated" width="400" height="239">
</a>

<p>
<a href="#TOP">
<img alt="Return to Top" src="https://raw.githubusercontent.com/MobileAppTracking/tune-reporting-php/master/docs/images/b_top.gif" border="0">
</a>
</p>

<p>&nbsp;</p>
<a id="sdk_advertiser_reporting_overview" name="sdk_advertiser_reporting_overview"></a>
### Advertiser Reporting Overview

The utility focus of the SDKs is upon the <a href="/advertiser-reporting-endpoints/">Advertiser Reporting endpoints</a>. Even though the the breadth of the Management API goes beyond just reports, it is these endpoints that our customers primarily access. The second goal of the SDKs is to assure that our customers' developers are using best practices in gathering reports in the most optimal way.

<a href="https://raw.githubusercontent.com/MobileAppTracking/tune-reporting-php/master/docs/images/tune_advertiser_reporting_classes.png">
<img src="https://raw.githubusercontent.com/MobileAppTracking/tune-reporting-php/master/docs/images/tune_advertiser_reporting_classes.png" alt="TUNE Advertiser Reporting Classes" width="500" height="350" /></a>

The endpoints interfaced by TUNE API SDKs provide access in gathering four types of reports:

<dl>
<dt>Log Reports</dt>
<dd>
Log reports provide measurement records for each Click, Install, Event, Event Item and Postback. Instead of being aggregated, the data is on a per transaction / request basis. MobileAppTracking&trade; (MAT) uses these logs to generate the aggregated data for the Actuals and Cohort reports. Note that we don’t provide Log reports for Impressions and Opens currently.

Advertiser Reporting classes that perform Log Reports are:
<ul>
    <li><code>AdvertiserReportLogClicks</code>: <a href="http://developers.mobileapptracking.com/management-api/explorer/root/endpoint/#/advertiser__stats__clicks/">/advertiser/stats/clicks/</a></li>
    <li><code>AdvertiserReportLogEventItems</code>:<a href="http://developers.mobileapptracking.com/management-api/explorer/root/endpoint/#/advertiser__stats__event__items/">/advertiser/stats/event/items/</a></li>
    <li><code>AdvertiserReportLogEvents</code>:<a href="http://developers.mobileapptracking.com/management-api/explorer/root/endpoint/#/advertiser__stats__events/">/advertiser/stats/events/</a></li>
    <li><code>AdvertiserReportLogInstalls</code>:<a href="http://developers.mobileapptracking.com/management-api/explorer/root/endpoint/#/advertiser__stats__installs/">/advertiser/stats/installs/</a></li>
    <li><code>AdvertiserReportLogPostbacks</code>:<a href="http://developers.mobileapptracking.com/management-api/explorer/root/endpoint/#/advertiser__stats__postbacks/">/advertiser/stats/postbacks/</a></li>
</ul>

</dd>
<dt>Actuals Report</dt>
<dd>
The Actuals report gives you quick insight into the performance of your apps and advertising partners (publishers). Use this report for reconciliation, testing, debugging, and ensuring that all measurement and attribution continues to operate smoothly. MAT generates this report by aggregating all the logs of each request (MAT updates the report every 5 minutes).

Actuals report endpoint include: <a href="http://developers.mobileapptracking.com/management-api/explorer/root/endpoint/#/advertiser__stats/">/advertiser/stats/</a>: Reports' class <a href="#sdk-advertiser-report-actuals"><code>AdvertiserReportActuals</code></a>

Advertiser Reporting class that perform Actuals Reports is:
<ul>
    <li><code>AdvertiserReportActuals</code>: <a href="http://developers.mobileapptracking.com/management-api/explorer/root/endpoint/#/advertiser__stats/">/advertiser/stats/</a></li>
</ul>
</dd>
<dt>Cohort Report</dt>
<dd>
The Cohort report analyzes user behavior back to click date time (Cohort by Click) or to install date time (Cohort by Install). Depending on whether you view the results based on click or install, the data in the report is vastly different.

Advertiser Reporting class that perform Cohort Reports is:
<ul>
    <li><code>AdvertiserReportCohortValue</code>: <a href="http://developers.mobileapptracking.com/management-api/explorer/root/endpoint/#/advertiser__stats__ltv">/advertiser/stats/ltv</a></li>
</ul>
</dd>
<dt>Retention Report</dt>
<dd>
The Retention report shows you how many of your installed users open or engage with your app over time (how users continue to get value from the app). AdvertiserReportCohortRetention reports are particularly good for evaluating the quality of users as opposed to the quantity of users (as in the case of user acquisition campaigns). For more information about retention reports, please visit <a href="http://support.mobileapptracking.com/entries/42179044-Running-AdvertiserReportCohortRetention-Reports">Running AdvertiserReportCohortRetention Reports</a>.

Advertiser Reporting class that perform Retention Reports are:
<ul>
    <li><code>AdvertiserReportCohortRetention</code>: <a href="http://developers.mobileapptracking.com/management-api/explorer/root/endpoint/#/advertiser__stats__retention">/advertiser/stats/retention</a></li>
</ul>
</dd>
</dl>

<a href="https://raw.githubusercontent.com/MobileAppTracking/tune-reporting-php/master/docs/images/tune_management_service_reporting_endpoints.png">
<img src="https://raw.githubusercontent.com/MobileAppTracking/tune-reporting-php/master/docs/images/tune_management_service_reporting_endpoints.png" alt="Management API Advertiser Reports covered by TUNE Reporting SDKs." width="592" height="292" /></a>

<p>
<a href="#TOP">
<img alt="Return to Top" src="https://raw.githubusercontent.com/MobileAppTracking/tune-reporting-php/master/docs/images/b_top.gif" border="0">
</a>
</p>

<a id="sdk_exporting_reports" name="sdk_exporting_reports"></a>
### Exporting Advertiser Reports
Currently, there are two different ways of handling advertiser report exports. Both approaches require (A) an action to request that a report be exported and (B) another action to request the report status (if ready to be exported), and if ready, then provide a URL to download the completed report.

Logs and Actuals reports all request an export using action <code>find_export_queue.json</code>, which returns a <code>job_id</code>. You then pass the <code>job_id</code> onto another endpoint <code>Export::download.json</code>, which performs the status checking and report URL retrieval.

<a href="https://raw.githubusercontent.com/MobileAppTracking/tune-reporting-php/master/docs/images/management-api-report-exports1.png">
<img src="https://raw.githubusercontent.com/MobileAppTracking/tune-reporting-php/master/docs/images/management-api-report-exports1-600x569.png" alt="Exporting logs and actuals reports." width="600" height="569" /></a>

Cohort and AdvertiserReportCohortRetention reports all request an export using action <code>export.json</code>, which also returns a <code>job_id</code>. You then pass the <code>job_id</code> onto another action <code>status.json</code>, which performs the status checking and report URL retrieval.

<a href="https://raw.githubusercontent.com/MobileAppTracking/tune-reporting-php/master/docs/images/management-api-insight-report-exports.png">
<img src="https://raw.githubusercontent.com/MobileAppTracking/tune-reporting-php/master/docs/images/management-api-insight-report-exports-600x459.png" alt="Export cohort and retention reports." width="600" height="459" /></a>

<p>
<a href="#TOP">

<img alt="Return to Top" src="https://raw.githubusercontent.com/MobileAppTracking/tune-reporting-php/master/docs/images/b_top.gif" border="0">
</a>
</p>

<!-- Sources -->

<a id="sdk_sources" name="sdk_sources"></a>
### SDK Sources

The key contents of SDK is **src**, which contains the library; followed by the **examples**, and **tests**.

File **Makefile** provides shortcuts for executing examples and tests.

```
├── AUTHORS.md
├── CHANGES.md
├── composer.json
├── docs
├── examples
├── LICENSE.md
├── Makefile
├── README
├── README.md
├── src
├── tests
└── tune_reporting_sdk.config
```

<a id="sdk_sources_lib" name="sdk_sources_lib"></a>
#### Library

File **TuneReporting.php** is the root of this Library.

Library folder **src** contains the key functionality related to **Advertiser Reporting classes** are defined within folder **/src/TuneReporting/Api/**.

Client classes that connect with the **TUNE Management API Service** are defined within folder **/src/TuneReporting/Base/Service/**.

Helper class for both the Library and Examples are defined within folder **/src/TuneReporting/Helpers/**.
```
src/
├── TuneReporting
│   ├── Api
│   │   ├── AdvertiserReportActuals.php
│   │   ├── AdvertiserReportLogClicks.php
│   │   ├── AdvertiserReportCohortValue.php
│   │   ├── AdvertiserReportLogEventItems.php
│   │   ├── AdvertiserReportLogEvents.php
│   │   ├── AdvertiserReportLogInstalls.php
│   │   ├── AdvertiserReportLogPostbacks.php
│   │   ├── AdvertiserReportCohortRetention.php
│   │   └── Export.php
│   ├── Base
│   │   ├── Endpoints
│   │   │   ├── AdvertiserReportActualsBase.php
│   │   │   ├── AdvertiserReportBase.php
│   │   │   ├── AdvertiserReportCohortBase.php
│   │   │   ├── AdvertiserReportLogBase.php
│   │   │   └── EndpointBase.php
│   │   └── Service
│   │       ├── Constants.php
│   │       ├── QueryStringBuilder.php
│   │       ├── TuneManagementClient.php
│   │       ├── TuneManagementProxy.php
│   │       ├── TuneManagementRequest.php
│   │       └── TuneManagementResponse.php
│   ├── Helpers
│   │   ├── ReportExportWorker.php
│   │   ├── ReportReaderBase.php
│   │   ├── ReportReaderCSV.php
│   │   ├── ReportReaderJSON.php
│   │   ├── String.php
│   │   ├── TuneSdkException.php
│   │   ├── TuneServiceException.php
│   │   └── Utils.php
│   └── Version.php
└── TuneReporting.php
```

<a id="sdk_sources_examples" name="sdk_sources_examples"></a>
#### SDK Examples

Run the following script to view execution of all examples:
```bash
    $ make examples
```

Each Advertiser Report class defined in **/src/TuneReporting/Api/** has an example:

```
examples/
├── ExampleAdvertiserReportActuals.php
├── ExampleAdvertiserReportLogClicks.php
├── ExampleAdvertiserReportLogEventItems.php
├── ExampleAdvertiserReportLogEvents.php
├── ExampleAdvertiserReportLogInstalls.php
├── ExampleAdvertiserReportLogPostbacks.php
├── ExampleAdvertiserReportCohortRetention.php
├── ExampleAdvertiserReportCohortValue.php
├── ExampleTuneManagementClient.php
├── TuneReportingExamplesAutoloader.php
└── TuneReportingExamples.php
```

<a id="sdk_sources_tests" name="sdk_sources_tests"></a>
#### SDK Tests


Run the following script to view execution of all unittests:
```bash
    $ make tests
```

Each Advertiser Report class defined in **/src/TuneReporting/Api/** has a test:

```
tests/
├── bootstrap.php
├── phpunit.xml
├── TestAdvertiserReportActuals.php
├── TestAdvertiserReportLogClicks.php
├── TestAdvertiserReportLogEventItems.php
├── TestAdvertiserReportLogEvents.php
├── TestAdvertiserReportLogInstalls.php
├── TestAdvertiserReportLogPostbacks.php
├── TestAdvertiserReportCohortRetention.php
├── TestAdvertiserReportCohortValue.php
└── TestTuneManagementClient.php
```

<p>
<a href="#TOP">
<img alt="Return to Top" src="https://raw.githubusercontent.com/MobileAppTracking/tune-reporting-php/master/docs/images/b_top.gif" border="0">
</a>
</p>

<!-- Classes -->
<a id="sdk_classes" name="sdk_classes"></a>
### SDK Classes

<!-- TUNE Management API Service -->
<a id="sdk_classes_service" name="sdk_classes_service"></a>
#### TUNE Management API Service Classes

<ul>
    <li><code>TuneManagementClient</code> - Connects with <a href="http://developers.mobileapptracking.com/management-api/" target="_blank">TUNE Management API Service</a></li>
    <li><code>TuneManagementRequest</code> - Defines request to TUNE Management API Service containing:
        <ul>
            <li>Controller / Endpoint</li>
            <li>Action</li>
            <li>Query String Parameters
                <ul>
                    <li>API Key</li>
                </ul>
            </li>
        </ul>
    </li>
    <li><code>TuneManagementResponse</code> - Complete response from TUNE Management API Service containing:
        <ul>
            <li>Status Code</li>
            <li>Data</li>
            <li>Errors</li>
        </ul>
    </li>
</ul>

<a href="https://raw.githubusercontent.com/MobileAppTracking/tune-reporting-php/master/docs/images/tune_reporting_service_classes.png">
<img src="https://raw.githubusercontent.com/MobileAppTracking/tune-reporting-php/master/docs/images/tune_reporting_service_classes.png" alt="TUNE Management Service Classes" width="217" height="163" /></a>

<!-- Example Helpers -->
<a id="sdk_report_readers" name="sdk_report_readers"></a>
#### Report Readers Classes

<ul>
    <li><code>ReportReaderCSV</code> - Reads exported CSV using downloaded URL.</li>
    <li><code>ReportReaderJSON</code> - Reads exported JSON using downloaded URL.</li>
</ul>

<a href="https://raw.githubusercontent.com/MobileAppTracking/tune-reporting-php/master/docs/images/tune_reporting_reader_classes.png">
<img src="https://raw.githubusercontent.com/MobileAppTracking/tune-reporting-php/master/docs/images/tune_reporting_reader_classes.png" alt="Report Reader Helper Classes." width="217" height="163" title="Click to Expand" /></a>

<!-- Exceptions -->
<a id="sdk_classes_exceptions" name="sdk_classes_exceptions"></a>
#### Custom Exceptions Classes

<ul>
    <li><code>TuneSdkException</code> - Exception thrown if error occurs within TUNE Reporting SDK.</li>
    <li><code>TuneServiceException</code> - Exception thrown if error condition is returned from TUNE Management Service.</li>
</ul>

<a href="https://raw.githubusercontent.com/MobileAppTracking/tune-reporting-php/master/docs/images/tune_reporting_exceptions.png">
<img src="https://raw.githubusercontent.com/MobileAppTracking/tune-reporting-php/master/docs/images/tune_reporting_exceptions.png" alt="Custom Exceptions." width="217" height="163" title="Click to Expand" /></a>


<a id="sdk_methods" name="sdk_methods"></a>
### Advertiser Reporting Methods

<strong>Important to note on Sample Code:</strong> The example provided pertain to only Advertiser Reports class <code>AdvertiserReportLogClicks</code>. The fields used theses sample primarily pertain to the available fields for the record and related records for the the associated endpoint <a href="http://developers.mobileapptracking.com/management-api/explorer/root/endpoint/#/advertiser__stats__clicks/">/advertiser/stats/clicks</a> of this class. Do not expect that these fields will be available if used with other Advertiser Records classes.

The benefit of using TUNE API SDKs is it provides the same interface across all advertiser reports. The following class diagram lists what are all the expected functions. The signature of the expected parameters for each function will be consistent with the action it is interfacing.

<a href="https://raw.githubusercontent.com/MobileAppTracking/tune-reporting-php/master/docs/images/management-sdk-class.png">
<img src="https://raw.githubusercontent.com/MobileAppTracking/tune-reporting-php/master/docs/images/management-sdk-class-600x262.png" alt="Report classes available methods." width="600" height="262" title="Click to Expand" /></a>

<a id="sdk_method_count" name="sdk_method_count"></a>
##### Method <code>count()</code>

Finds all existing records matching provided filter criteria and returns total count. It returns a populated instance of <code>class Response</code>, class of TUNE API SDK, with <strong>record count</strong>.

<a href="https://raw.githubusercontent.com/MobileAppTracking/tune-reporting-php/master/docs/images/management-api-action-count.png">
<img src="https://raw.githubusercontent.com/MobileAppTracking/tune-reporting-php/master/docs/images/management-api-action-count-700x247.png" alt="Function count()" width="700" height="247" title="Click to Expand" /></a>

<!-- PHP -->
```php
    $advertiser_report = new AdvertiserReportLogClicks();
    $response = $advertiser_report->count(
        $start_date,
        $end_date,
        $filter              = null,
        $response_timezone   = "America/Los_Angeles"
    );

    if (($response->getHttpCode() != 200) || ($response->getErrors() != null)) {
        throw new \Exception(
            sprintf("Failed: %d: %s", $response->getHttpCode(), print_r($response, true))
        );
    }

    echo " TuneManagementResponse:" . PHP_EOL;
    echo print_r($response, true) . PHP_EOL;

    echo " Count:" . $response->getData() . PHP_EOL;
```

<a id="sdk_method_find" name="sdk_method_find"></a>
##### Method <code>find()</code>

Gathers all existing records that match filter criteria and returns an array of found model data. Even though calling the action <code>find.json</code> is commonly used for gathering data, however it is not the preferred way of gathering full reports. It returns a populated instance of <code>class Response</code> with <strong>records</strong>.

<a href="https://raw.githubusercontent.com/MobileAppTracking/tune-reporting-php/master/docs/images/management-api-action-find.png">
<img src="https://raw.githubusercontent.com/MobileAppTracking/tune-reporting-php/master/docs/images/management-api-action-find-700x511.png" alt="Function find()" width="700" height="511" title="Click to Expand" /></a>

<!-- PHP -->
```php
    $advertiser_report = new AdvertiserReportLogClicks();
    $response = $advertiser_report->find(
        $start_date,
        $end_date,
        $fields              = $advertiser_report->fields(AdvertiserReportLogClicks::TUNE_FIELDS_RECOMMENDED),
        $filter              = null,
        $limit               = 5,
        $page                = null,
        $sort                = array("created" => "DESC"),
        $response_timezone   = "America/Los_Angeles"
    );

    if (($response->getHttpCode() != 200) || ($response->getErrors() != null)) {
        throw new \Exception(
            sprintf("Failed: %d: %s", $response->getHttpCode(), print_r($response, true))
        );
    }

    echo " TuneManagementResponse:" . PHP_EOL;
    echo print_r($response, true) . PHP_EOL;
```

<a id="sdk_method_export" name="sdk_method_export"></a>
##### Method <code>export()</code>

Provides the same signature as function find(), accept parameters <code>limit</code> and <code>page</code>, because this function's intent is to request export of a full report. It returns a populated instance of <code>class Response</code> with <strong>job identifier</strong> of report in queue and ready to be processed. Format of content can be requested to be either CSV or JSON.

<a href="https://raw.githubusercontent.com/MobileAppTracking/tune-reporting-php/master/docs/images/management-api-action-export.png">
<img src="https://raw.githubusercontent.com/MobileAppTracking/tune-reporting-php/master/docs/images/management-api-action-export-700x338.png" alt="Function export()" width="700" height="338" title="Click to Expand" /></a>

<!-- PHP -->
```php
    $advertiser_report = new AdvertiserReportLogClicks();
    $response = $advertiser_report->export(
        $start_date,
        $end_date,
        $fields              = $advertiser_report->fields(AdvertiserReportLogClicks::TUNE_FIELDS_RECOMMENDED),
        $filter              = null,
        $format              = "csv",
        $response_timezone   = "America/Los_Angeles"
    );

    if (($response->getHttpCode() != 200) || ($response->getErrors() != null)) {
        throw new \Exception(
            sprintf("Failed: %d: %s", $response->getHttpCode(), print_r($response, true))
        );
    }

    echo " TuneManagementResponse:" . PHP_EOL;
    echo print_r($response, true) . PHP_EOL;

    $job_id = AdvertiserReportLogClicks::parseResponseReportJobId($response);
    echo " CSV Job ID: {$job_id}" . PHP_EOL;
```

<a id="sdk_method_status" name="sdk_method_status"></a>
##### Method <code>status()</code>

As discussed in <a href="#exporting-reports">Exporting Advertise Reports</a>, for gathering report export status records' classes <strong>Cohort (AdvertiserReportCohorts)</strong> and <strong>AdvertiserReportCohortRetention</strong> uses it own method <code>status()</code>. Its purpose is the same as method <code>Export::download()</code>.

<a id="sdk_method_fetch" name="sdk_method_fetch"></a>
##### Method <code>fetch()</code>

A helper function that creates a threaded worker that handles the status request appropriate to it class. This function handles the polling of the service waiting for status of "complete" and its "report url". Upon completion, fetch downloads data into a reader that parses the contents that is appropriate requested content format type, CSV or JSON.

<a href="https://raw.githubusercontent.com/MobileAppTracking/tune-reporting-php/master/docs/images/management-api-action-fetch.png">
<img src="https://raw.githubusercontent.com/MobileAppTracking/tune-reporting-php/master/docs/images/management-api-action-fetch-700x337.png" alt="Function fetch()" width="700" height="337" title="Click to Expand" /></a>

<!-- PHP -->
```php
    $response = $advertiser_report->fetch(
        $job_id,
        $verbose = true
    );

    $report_url = AdvertiserReportLogClicks::parseResponseReportUrl($response);
    echo " CSV Report URL: {$report_url}" . PHP_EOL;
```

<a id="sdk_method_fields" name="sdk_method_fields"></a>
##### Method <code>fields()</code>

Method <strong>fields()</strong> returns a listing of all the fields that can be used that can be used by that report endpoint, which will include all the field of its immediate record and all its related records.

<!-- PHP -->
```php
    $advertiser_report = new AdvertiserReportLogClicks();
    $response = $advertiser_report->getFields();
    echo print_r($response, true) . PHP_EOL;
```

<a id="sdk_method_define" name="sdk_method_define"></a>
##### Method <code>define()</code>

Method <strong>define()</strong> returns the complete meta-data of an endpoint. Available actions, associated record it is bound and its fields, related records and its fields.

<!-- PHP -->
```php
    $advertiser_report = new AdvertiserReportLogClicks();
    $response = $advertiser_report->getDefine();
    echo print_r($response, true) . PHP_EOL;
```

<p>
<a href="#TOP">
<img alt="Return to Top" src="https://raw.githubusercontent.com/MobileAppTracking/tune-reporting-php/master/docs/images/b_top.gif" border="0">
</a>
</p>

<a id="sdk_reporting_fields" name="sdk_reporting_fields"></a>
### Advertiser Reporting Fields

It is important to understand that every endpoint has its own unique set of fields based upon the model its data is associated with, and the model's related entities.

Making a request with a field name that does not exist with the endpoint's set of available fields can cause a service error. So it is important to make sure that the field names used within method parameter type <code>fields</code>, <code>filter</code>, <code>sort</code>, and <code>group</code> are appropriate to the endpoint it is calling.

Two helpful functions that come with every report class are <code>define()</code> and <code>fields()</code>. Every advertiser reports endpoint has a different data model associated with it. With that, what fields are available are not consistent across records. So use these functions to understand available field choices.

Function <code>define()</code> returns a complete metadata mapping of the endpoint, and function <code>fields()</code> returns a complete listing of all field names of the model associated with the endpoint function was called through, and endpoint's related entities' field names.

In addition, the constructor for every advertiser records' class has a bool parameter <code>validate</code> which checks that the field names used within parameter values are valid.

Another tool is to pre-build your request using <a href="/management-api/explorer/root/">Management API Root Endpoints Explorer</a>.

<a href="https://raw.githubusercontent.com/MobileAppTracking/tune-reporting-php/master/docs/images/api_explorer_record_fields.png">
<img src="https://raw.githubusercontent.com/MobileAppTracking/tune-reporting-php/master/docs/images/api_explorer_record_fields-600x436.png" alt="API Explorer -- Record and Related Record&#039;s Fields for a specific endpoint." width="600" height="436" title="Click to Expand" /></a>

<ul>
    <li>AdvertiserReportLogClicks' fields: <a href="http://developers.mobileapptracking.com/management-api/explorer/root/endpoint/#/advertiser__stats__clicks/">/advertiser/stats/clicks fields</a></li>
    <li>AdvertiserReportLogEventItems' fields: <a href="http://developers.mobileapptracking.com/management-api/explorer/root/endpoint/#/advertiser__stats__event__items/">/advertiser/stats/event/items fields</a></li>
    <li>AdvertiserReportLogEvents' fields: <a href="http://developers.mobileapptracking.com/management-api/explorer/root/endpoint/#/advertiser__stats__events/">/advertiser/stats/events fields</a></li>
    <li>AdvertiserReportLogInstalls' fields: <a href="http://developers.mobileapptracking.com/management-api/explorer/root/endpoint/#/advertiser__stats__installs/">/advertiser/stats/installs fields</a></li>
    <li>AdvertiserReportLogPostbacks' fields: <a href="http://developers.mobileapptracking.com/management-api/explorer/root/endpoint/#/advertiser__stats__postbacks/">/advertiser/stats/postbacks fields</a></li>
    <li>AdvertiserReportActuals' fields: <a href="http://developers.mobileapptracking.com/management-api/explorer/root/endpoint/#/advertiser__stats/">/advertiser/stats fields</a></li>
    <li>AdvertiserReportCohorts' fields: <a href="http://developers.mobileapptracking.com/management-api/explorer/root/endpoint/#/advertiser__stats__ltv/">/advertiser/stats/ltv fields</a></li>
    <li>AdvertiserReportCohortRetention' fields: <a href="http://developers.mobileapptracking.com/management-api/explorer/root/endpoint/#/advertiser__stats__retention/">/advertiser/stats/retention fields</a></li>
</ul>

<p>
<a href="#TOP">
<img alt="Return to Top" src="https://raw.githubusercontent.com/MobileAppTracking/tune-reporting-php/master/docs/images/b_top.gif" border="0">
</a>
</p>


<a id="sdk_parameters" name="sdk_parameters"></a>
### Advertiser Reporting Parameters

Most of the functions provided for the record classes have the following parameters in common. All of these parameters are based upon knowledge of available fields for that endpoint, which are discovered using endpoint's functions <code>define()</code> and <code>fields()</code>.

<p>&nbsp;</p>
<a id="sdk_parameter_fields" name="sdk_parameter_fields"></a>
##### Parameter <code>fields</code>

Parameter <strong>fields</strong> can either be an array of field names, or a string containing comma-delimited field named:

<a href="https://raw.githubusercontent.com/MobileAppTracking/tune-reporting-php/master/docs/images/parameter-fields.png">
<img src="https://raw.githubusercontent.com/MobileAppTracking/tune-reporting-php/master/docs/images/parameter-fields.png" alt="Parameter fields" width="655" height="121" /></a>

<p>&nbsp;</p>
<a id="sdk_parameter_group" name="sdk_parameter_group"></a>
##### Parameter <code>group</code>

Parameter <strong>group</strong> is the same as parameter <code>fields</code> can either be an array of field names, or a string containing comma-delimited field named:

<a href="https://raw.githubusercontent.com/MobileAppTracking/tune-reporting-php/master/docs/images/parameter-group.png">
<img src="https://raw.githubusercontent.com/MobileAppTracking/tune-reporting-php/master/docs/images/parameter-group.png" alt="Parameter group" width="655" height="121" /></a>

<p>&nbsp;</p>
<a id="sdk_parameter_sort" name="sdk_parameter_sort"></a>
##### Parameter <code>sort</code>

Parameter <strong>sort</strong> is a dictionary (associative array), where the key is the field name and value is the expected sort direction of either <code>"DESC"</code> or <code>"ASC"</code>.

<a href="https://raw.githubusercontent.com/MobileAppTracking/tune-reporting-php/master/docs/images/parameter-sort.png">
<img src="https://raw.githubusercontent.com/MobileAppTracking/tune-reporting-php/master/docs/images/parameter-sort-700x107.png" alt="Parameter sort" width="700" height="107" /></a>

<p>&nbsp;</p>
<a id="sdk_parameter_filter" name="sdk_parameter_filter"></a>
##### Parameter <code>filter</code>

Parameter <strong>filter</strong> is a string that contains a set of query expressions based upon matching conditions to endpoint's fields. It is especially important to to provide an invalid field name because that will cause request to fail.

<a href="https://raw.githubusercontent.com/MobileAppTracking/tune-reporting-php/master/docs/images/parameter-filter.png">
<img src="https://raw.githubusercontent.com/MobileAppTracking/tune-reporting-php/master/docs/images/parameter-filter-700x350.png" alt="Parameter filter" width="700" height="350" /></a>

<p>
<a href="#TOP">
<img alt="Return to Top" src="https://raw.githubusercontent.com/MobileAppTracking/tune-reporting-php/master/docs/images/b_top.gif" border="0">
</a>
</p>

<!-- Licenses -->

<a id="sdk_license" name="sdk_license"></a>
### License

[MIT License](http://opensource.org/licenses/MIT)

<a id="sdk_issues" name="sdk_issues"></a>
### Reporting Issues

Report issues using the [Github Issue Tracker](https://github.com/MobileAppTracking/tune-reporting-php/issues) or Email [sdk@tune.com](mailto:sdk@tune.com).
