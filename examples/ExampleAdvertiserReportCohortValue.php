<?php
/**
 * ExampleAdvertiserReportCohortValue.php
 *
 * Copyright (c) 2015 TUNE, Inc.
 * All rights reserved.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NON-INFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * PHP Version 5.3
 *
 * @category  TUNE_Reporting
 *
 * @author  Jeff Tanner <jefft@tune.com>
 * @copyright 2015 TUNE, Inc. (http://www.tune.com)
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version   $Date: 2015-04-16 15:41:32 $
 * @link    https://developers.mobileapptracking.com/tune-reporting-sdks @endlink
 *
 */

require_once dirname(__FILE__) . "/../src/TuneReporting.php";

use TuneReporting\Api\AdvertiserReportCohortValue;
use TuneReporting\Api\Export;
use TuneReporting\Helpers\ReportReaderCSV;
use TuneReporting\Helpers\ReportReaderJSON;
use TuneReporting\Helpers\SdkConfig;

global $argc, $argv;

/**
 * Class ExampleAdvertiserReportCohortValue
 *
 * Using TuneReporting\Api\AdvertiserReportCohortValue
 */
class ExampleAdvertiserReportCohortValue
{
  /**
   * Constructor that prevents a default instance of this class from being created.
   */
  private function __construct()
  {

  }

  /**
   * Execute example
   *
   * @param string $auth_key  MobileAppTracking API Key or Session Token.
   * @param string $auth_type TUNE Reporting Authentication Type.
   *
   * @throws InvalidArgumentException
   * @throws RuntimeException
   * @throws Exception
   */
  public static function run(
    $auth_key,
    $auth_type
  ) {
    $tune_reporting_config_file
      = dirname(__FILE__) . "/../config/tune_reporting_sdk.config";

    if (!file_exists($tune_reporting_config_file)) {
      throw new \InvalidArgumentException(
        "TUNE Reporting Config '$tune_reporting_config_file' does not exist."
      );
    }

    // Get instance of TUNE Reporting SDK configuration.
    $sdk_config = SdkConfig::getInstance($tune_reporting_config_file);

    // Override Authentication setting of TUNE Reporting SDK configuration.
    $sdk_config->setAuthKey($auth_key);
    $sdk_config->setAuthType($auth_type);

    $default_date_timezone = ini_get('date.timezone');
    if (is_string($default_date_timezone) && !empty($default_date_timezone)) {
      echo "========================================================" . PHP_EOL;
      echo " Default timezone used: '{$default_date_timezone}'.   " . PHP_EOL;
      echo "========================================================" . PHP_EOL;
    } else {
      throw new \RuntimeException(
        "It is not safe to rely on the system's timezone settings. "
        . "You are *required* to use the date.timezone setting or "
        . "the date_default_timezone_set() function."
      );
    }

    echo "\033[34m" . "============================================" . "\033[0m" . PHP_EOL;
    echo "\033[34m" . " Begin TUNE Advertiser Report Cohort Value  " . "\033[0m" . PHP_EOL;
    echo "\033[34m" . "============================================" . "\033[0m" . PHP_EOL;

    try {
      $week_ago     = date('Y-m-d', strtotime("-8 days"));
      $yesterday    = date('Y-m-d', strtotime("-1 days"));
      $start_date   = "{$week_ago} 00:00:00";
      $end_date     = "{$yesterday} 23:59:59";

      $advertiser_report = new AdvertiserReportCohortValue();

      echo "========================================================" . PHP_EOL;
      echo " Fields of Advertiser Report Cohort Value: Default.   " . PHP_EOL;
      echo "========================================================" . PHP_EOL;
      $response = $advertiser_report->getFields(AdvertiserReportCohortValue::TUNE_FIELDS_DEFAULT);
      echo print_r($response, true) . PHP_EOL;

      echo "========================================================" . PHP_EOL;
      echo " Fields of Advertiser Report Cohort Value: Recommended. " . PHP_EOL;
      echo "========================================================" . PHP_EOL;
      $response = $advertiser_report->getFields(AdvertiserReportCohortValue::TUNE_FIELDS_RECOMMENDED);
      echo print_r($response, true) . PHP_EOL;

      echo "========================================================" . PHP_EOL;
      echo " Count Advertiser Report Cohort Value records.          " . PHP_EOL;
      echo "========================================================" . PHP_EOL;

      $map_params = array(
        "start_date"        => $start_date,
        "end_date"          => $end_date,
        "cohort_type"       => "click",
        "cohort_interval"   => "year_day",
        "group"             => "site_id,publisher_id",
        "filter"            => "(publisher_id > 0)",
        "response_timezone" => "America/Los_Angeles"
      );

      $response = $advertiser_report->count(
        $map_params
      );

      if (($response->getHttpCode() != 200) || ($response->getErrors() != null)) {
        throw new \Exception(
          sprintf("Failed: %d: %s", $response->getHttpCode(), print_r($response, true))
        );
      }

      echo " TuneServiceResponse:" . PHP_EOL;
      echo $response->toString() . PHP_EOL;

      echo " JSON:" . PHP_EOL;
      echo print_r($response->toJson(), true) . PHP_EOL;

      echo " Count:" . $response->getData() . PHP_EOL;

      echo "=========================================================" . PHP_EOL;
      echo " Find Advertiser Report Cohort Value records.            " . PHP_EOL;
      echo "=========================================================" . PHP_EOL;

      $map_params = array(
        "start_date"            => $start_date,
        "end_date"              => $end_date,
        "cohort_type"           => "click",
        "cohort_interval"       => "year_day",
        "aggregation_type"      => "cumulative",
        "fields"                => "site_id,site.name,publisher_id,publisher.name,rpi,epi",
        "group"                 => "site_id,publisher_id",
        "filter"                => "(publisher_id > 0)",
        "limit"                 => 5,
        "page"                  => null,
        "sort"                  => null,
        "response_timezone"     => "America/Los_Angeles"
      );

      $response = $advertiser_report->find(
        $map_params
      );

      if (($response->getHttpCode() != 200) || ($response->getErrors() != null)) {
        throw new \Exception(
          sprintf("Failed: %d: %s", $response->getHttpCode(), print_r($response, true))
        );
      }

      echo " TuneServiceResponse:" . PHP_EOL;
      echo $response->toString() . PHP_EOL;

      echo "=========================================================" . PHP_EOL;
      echo " Find Advertiser Report Cohort Value records Recommended " . PHP_EOL;
      echo "=========================================================" . PHP_EOL;

      $map_params = array(
        "start_date"            => $start_date,
        "end_date"              => $end_date,
        "cohort_type"           => "click",
        "cohort_interval"       => "year_day",
        "aggregation_type"      => "cumulative",
        "fields"                => $advertiser_report->getFields(AdvertiserReportCohortValue::TUNE_FIELDS_RECOMMENDED),
        "group"                 => "site_id,publisher_id",
        "filter"                => "(publisher_id > 0)",
        "limit"                 => 5,
        "page"                  => null,
        "sort"                  => null,
        "response_timezone"     => "America/Los_Angeles"
      );

      $response = $advertiser_report->find(
        $map_params
      );

      if (($response->getHttpCode() != 200) || ($response->getErrors() != null)) {
        throw new \Exception(
          sprintf("Failed: %d: %s", $response->getHttpCode(), print_r($response, true))
        );
      }

      echo " TuneServiceResponse:" . PHP_EOL;
      echo $response->toString() . PHP_EOL;

      echo " JSON:" . PHP_EOL;
      echo print_r($response->toJson(), true) . PHP_EOL;

      echo "========================================================" . PHP_EOL;
      echo " Export Advertiser Report Cohort Value CSV              " . PHP_EOL;
      echo "========================================================" . PHP_EOL;

      $map_params = array(
        "start_date"            => $start_date,
        "end_date"              => $end_date,
        "cohort_type"           => "click",
        "cohort_interval"       => "year_day",
        "aggregation_type"      => "cumulative",
        "fields"                => $advertiser_report->getFields(AdvertiserReportCohortValue::TUNE_FIELDS_RECOMMENDED),
        "group"                 => "site_id,publisher_id",
        "filter"                => "(publisher_id > 0)",
        "response_timezone"     => "America/Los_Angeles"
      );

      $response = $advertiser_report->export(
        $map_params
      );

      if (($response->getHttpCode() != 200) || ($response->getErrors() != null)) {
        throw new \Exception(
          sprintf("Failed: %d: %s", $response->getHttpCode(), print_r($response, true))
        );
      }

      echo " TuneServiceResponse:" . PHP_EOL;
      echo $response->toString() . PHP_EOL;

      echo " JSON:" . PHP_EOL;
      echo print_r($response->toJson(), true) . PHP_EOL;

      $job_id = AdvertiserReportCohortValue::parseResponseReportJobId($response);
      echo " CSV Job ID: {$job_id}" . PHP_EOL;

      echo "========================================================" . PHP_EOL;
      echo " Fetching Advertiser Report Cohort Value CSV           " . PHP_EOL;
      echo "========================================================" . PHP_EOL;

      $response = $advertiser_report->fetch(
        $job_id,
        $verbose = true
      );

      $report_url = AdvertiserReportCohortValue::parseResponseReportUrl($response);
      echo " CSV Report URL: {$report_url}" . PHP_EOL;

      echo "========================================================" . PHP_EOL;
      echo " Read Advertiser Report Cohort Value CSV             " . PHP_EOL;
      echo "========================================================" . PHP_EOL;

      $csv_report_reader = new ReportReaderCSV(
        $report_url
      );

      $csv_report_reader->read();
      $csv_report_reader->prettyPrint($limit = 5);

      echo "\033[32m" . "==========================" . "\033[0m" . PHP_EOL;
      echo "\033[32m" . " End Example        " . "\033[0m" . PHP_EOL;
      echo "\033[32m" . "==========================" . "\033[0m" . PHP_EOL;
      echo PHP_EOL;

    } catch (\Exception $ex) {
      throw $ex;
    }
  }
}