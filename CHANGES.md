## tune-reporting-php Changelog
=======================

Here you can see the full list of changes between each tune-reporting-php release.

Version 0.9.0
--------------

Internal release on October 06, 2014
* Tune Management API Client for PHP 5.3

Version 0.9.1
--------------

Internal release on October 12, 2014
* Tune Management API Advertise Reports

Version 0.9.2
--------------

Internal release on October 14, 2014
* Unittests and Examples

Version 0.9.3
--------------

Internal release on October 18, 2014
* Revised packaging

Version 0.9.5
--------------

Initial public release on October 22, 2014
* Field validation for parameter fields, sort, filter, group

Version 0.9.6
--------------

Beta public release on October 23, 2014
* Refactored layout of setup, examples, and tests.

Version 0.9.7
--------------

Beta public release on October 27, 2014
* Removed threading from fetch()
* Provided recommended values for exports()

Version 0.9.8
--------------

* Added methods fetch() and status() for all classes.

Version 0.9.9
--------------

Beta public release on October 30, 2014
* Created endpoint base classes.
* Removed bash scripts and provided Makefile instead.

Version 0.9.10
--------------

Beta public release on October 31, 2014
* Better handling of report worker.

Version 0.9.11
--------------

Beta public release on October 31, 2014
* Refactor: Renamed service classes.

ng of report worker.

Version 0.9.12
--------------

Beta public release on November 03, 2014
* Documentation ready to use phpDocumentor.

Version 0.9.13
--------------

Beta public release on November 05, 2014
* Sort changes for all Advertiser Reports.

Version 0.9.14
--------------

Beta public release on November 06, 2014
* Cohort endpoint changes.
* Actuals endpoint changes.

Version 0.9.16
--------------

Beta public release on Novenber 19, 2014
* Fixes to Cohort and Retention reports

Version 0.9.20
--------------

Beta public release on December 10, 2014
* Refactor to reflect new naming from 'tune-api-php' to 'tune-reporting-php'

Version 0.9.23
--------------

Beta public release on December 19, 2014
* SDK Configuration

Version 0.9.24
--------------

Beta public release on December 19, 2014
* /advertiser/stats/ltv is now considered as Value reporting.

Version 0.9.25
--------------

Beta public release on December 24, 2014
* Refactor Log and Cohort classes
* TUNE Management API request contains SDK type and version.

Version 0.9.26
--------------

Beta public release on December 30, 2014
* TUNE Management API request contains 'sdk' and 'ver'.

Version 0.9.27
--------------

Beta public release on December 31, 2014
* TUNE Reporting SDK set API_KEY.

Version 0.9.28
--------------

Beta public release on January 03, 2015
* Provide toJson() within response.
* Created 'config' directory to hold TUNE Reporting SDK configuration.

Version 0.9.29
--------------

Beta public release on January 05, 2015
* Provide both authentication approaches: api_key and session_token.
* New class ```SessionAuthentication``` for generating session_token.

Version 1.0.0
--------------

First production release on April 09, 2015


Version 1.0.4
--------------

Modification of TUNE Reporting endpoint /advertiser/stats/retention.
AdvertiserReportCohortRetention has new parameter retention_measure.