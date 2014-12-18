<?php
/**
 * ExampleAdvertiserReportRetention.php
 *
 * Copyright (c) 2014 TUNE, Inc.
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
 * @author    Jeff Tanner <jefft@tune.com>
 * @copyright 2014 TUNE, Inc. (http://www.tune.com)
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version   $Date: 2014-12-18 04:47:37 $
 * @link      https://developers.mobileapptracking.com/tune-reporting-sdks @endlink
 *
 */

require_once dirname(__FILE__) . "/../src/TuneReporting.php";

use TuneReporting\Api\AdvertiserReportRetention;
use TuneReporting\Helpers\ReportReaderCSV;
use TuneReporting\Helpers\ReportReaderJSON;
use TuneReporting\Helpers\SdkConfig;

/**
 * Class ExampleAdvertiserReportRetention
 *
 * Using TuneReporting\Api\AdvertiserReportRetention
 */
class ExampleAdvertiserReportRetention
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
     * @param string $api_key MobileAppTracking API Key
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws Exception
     */
    public static function run()
    {
        $tune_reporting_config_file = dirname(__FILE__) . "/../tune_reporting_sdk.config";
        $sdk_config = SdkConfig::getInstance($tune_reporting_config_file);

        $api_key = $sdk_config->getConfigValue("tune_reporting_api_key_string");

        // api_key
        if (!is_string($api_key) || empty($api_key)) {
            throw new \InvalidArgumentException("SDK Configuration 'api_key' is not defined.");
        }

        $default_date_timezone = ini_get('date.timezone');
        if (is_string($default_date_timezone) && !empty($default_date_timezone)) {
            echo "======================================================" . PHP_EOL;
            echo " Default timezone used: '{$default_date_timezone}'." . PHP_EOL;
            echo "======================================================" . PHP_EOL;
        } else {
            throw new \RuntimeException(
                "It is not safe to rely on the system's timezone settings. "
                . "You are *required* to use the date.timezone setting or "
                . "the date_default_timezone_set() function."
            );
        }

        echo "\033[34m" . "=========================================================" . "\033[0m" . PHP_EOL;
        echo "\033[34m" . "= Begin TUNE Advertiser Report Retention        =" . "\033[0m" . PHP_EOL;
        echo "\033[34m" . "=========================================================" . "\033[0m" . PHP_EOL;

        try {
            $week_ago       = date('Y-m-d', strtotime("-8 days"));
            $yesterday      = date('Y-m-d', strtotime("-1 days"));
            $start_date     = "{$week_ago} 00:00:00";
            $end_date       = "{$yesterday} 23:59:59";

            $advertiser_report = new AdvertiserReportRetention();

            echo "======================================================" . PHP_EOL;
            echo " Fields of Advertiser Report Retention Default.       " . PHP_EOL;
            echo "======================================================" . PHP_EOL;
            $response = $advertiser_report->fields(AdvertiserReportRetention::TUNE_FIELDS_DEFAULT);
            echo print_r($response, true) . PHP_EOL;

            echo "======================================================" . PHP_EOL;
            echo " Fields of Advertiser Report Retention Recommended.   " . PHP_EOL;
            echo "======================================================" . PHP_EOL;
            $response = $advertiser_report->fields(AdvertiserReportRetention::TUNE_FIELDS_RECOMMENDED);
            echo print_r($response, true) . PHP_EOL;

            echo "======================================================" . PHP_EOL;
            echo " Count Advertiser Report Retention records.           " . PHP_EOL;
            echo "======================================================" . PHP_EOL;
            $response = $advertiser_report->count(
                $start_date,
                $end_date,
                $cohort_type         = "click",
                $cohort_interval     = "year_day",
                $group               = "site_id,install_publisher_id",
                $filter              = "(install_publisher_id > 0)",
                $response_timezone   = "America/Los_Angeles"
            );

            if (($response->getHttpCode() != 200) || ($response->getErrors() != null)) {
                throw new \Exception(
                    sprintf("Failed: %d: %s", $response->getHttpCode(), print_r($response, true))
                );
            }

            echo "= TuneManagementResponse:" . PHP_EOL;
            echo print_r($response, true) . PHP_EOL;

            echo "= Count:" . $response->getData() . PHP_EOL;

            echo "======================================================" . PHP_EOL;
            echo " Find Advertiser Report Retention records.                   " . PHP_EOL;
            echo "======================================================" . PHP_EOL;
            $response = $advertiser_report->find(
                $start_date,
                $end_date,
                $cohort_type         = "click",
                $cohort_interval     = "year_day",
                $fields              = $advertiser_report->fields(AdvertiserReportRetention::TUNE_FIELDS_RECOMMENDED),
                $group               = "site_id,install_publisher_id",
                $filter              = "(install_publisher_id > 0)",
                $limit               = 5,
                $page                = null,
                $sort                = array("year_day" => "ASC", "install_publisher_id" => "ASC"),
                $response_timezone   = "America/Los_Angeles"
            );

            if (($response->getHttpCode() != 200) || ($response->getErrors() != null)) {
                throw new \Exception(
                    sprintf("Failed: %d: %s", $response->getHttpCode(), print_r($response, true))
                );
            }

            echo "= advertiser/stats/retention/find.json csv response:" . PHP_EOL;
            echo print_r($response, true) . PHP_EOL;

            echo "======================================================" . PHP_EOL;
            echo " Advertiser Report Retention CSV report for export.          " . PHP_EOL;
            echo "======================================================" . PHP_EOL;

            $response = $advertiser_report->export(
                $start_date,
                $end_date,
                $cohort_type         = "click",
                $cohort_interval     = "year_day",
                $fields              = $advertiser_report->fields(AdvertiserReportRetention::TUNE_FIELDS_RECOMMENDED),
                $group               = "site_id,install_publisher_id",
                $filter              = "(install_publisher_id > 0)",
                $response_timezone   = "America/Los_Angeles"
            );

            if (($response->getHttpCode() != 200) || ($response->getErrors() != null)) {
                throw new \Exception(
                    sprintf("Failed: %d: %s", $response->getHttpCode(), print_r($response, true))
                );
            }

            echo "= TuneManagementResponse:" . PHP_EOL;
            echo print_r($response, true) . PHP_EOL;

            $job_id = AdvertiserReportRetention::parseResponseReportJobId($response);
            echo "= CSV Job ID: {$job_id}" . PHP_EOL;

            echo "========================================================" . PHP_EOL;
            echo "Fetching Advertiser Report Retention CSV report                " . PHP_EOL;
            echo "========================================================" . PHP_EOL;

            $response = $advertiser_report->fetch(
                $job_id,
                $verbose = true
            );

            $report_url = AdvertiserReportRetention::parseResponseReportUrl($response);
            echo "= CSV Report URL: {$report_url}" . PHP_EOL;

            echo "========================================================" . PHP_EOL;
            echo " Read Advertiser Report Retention CSV report    " . PHP_EOL;
            echo "========================================================" . PHP_EOL;

            $csv_report_reader = new ReportReaderCSV(
                $report_url
            );

            $csv_report_reader->read();
            $csv_report_reader->prettyPrint($limit = 5);

            echo "\033[32m" . "======================================================" . "\033[0m" . PHP_EOL;
            echo "\033[32m" . "= End Example                                        =" . "\033[0m" . PHP_EOL;
            echo "\033[32m" . "======================================================" . "\033[0m" . PHP_EOL;
            echo PHP_EOL;

        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}

ExampleAdvertiserReportRetention::run();
