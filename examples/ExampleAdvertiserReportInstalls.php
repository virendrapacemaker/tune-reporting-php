<?php
/**
 * ExampleAdvertiserReportInstalls.php
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
 * @category  TUNE
 *
 * @author    Jeff Tanner <jefft@tune.com>
 * @copyright 2014 TUNE (http://www.tune.com)
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version   $Date: 2014-12-17 13:40:16 $
 * @link      https://developers.mobileapptracking.com/tune-reporting-sdks @endlink
 *
 */

require_once dirname(__FILE__) . "/../src/TuneReporting.php";

use TuneReporting\Api\AdvertiserReportInstalls;
use TuneReporting\Helpers\ReportReaderCSV;
use TuneReporting\Helpers\ReportReaderJSON;

global $argc, $argv;

/**
 * Class ExampleAdvertiserReportInstalls
 *
 * Using TuneReporting\Api\AdvertiserReportInstalls
 */
class ExampleAdvertiserReportInstalls
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
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    public static function run($api_key = null)
    {
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

        echo "\033[34m" . "========================================================" . "\033[0m" . PHP_EOL;
        echo "\033[34m" . "= Begin TUNE Advertiser Report Logs Installs        =" . "\033[0m" . PHP_EOL;
        echo "\033[34m" . "========================================================" . "\033[0m" . PHP_EOL;

        try {
            $yesterday      = date('Y-m-d', strtotime("-1 days"));
            $start_date     = "{$yesterday} 00:00:00";
            $end_date       = "{$yesterday} 23:59:59";

            $advertiser_report_installs = new AdvertiserReportInstalls($api_key, $validate_fields = true);

            echo "======================================================" . PHP_EOL;
            echo " Fields of Advertiser Logs Installs Default.          " . PHP_EOL;
            echo "======================================================" . PHP_EOL;
            $response = $advertiser_report_installs->fields(AdvertiserReportInstalls::TUNE_FIELDS_DEFAULT);
            echo print_r($response, true) . PHP_EOL;

            echo "======================================================" . PHP_EOL;
            echo " Fields of Advertiser Logs Installs Recommended.      " . PHP_EOL;
            echo "======================================================" . PHP_EOL;
            $response = $advertiser_report_installs->fields(AdvertiserReportInstalls::TUNE_FIELDS_RECOMMENDED);
            echo print_r($response, true) . PHP_EOL;

            echo "======================================================" . PHP_EOL;
            echo " Count Advertiser Logs Installs records.              " . PHP_EOL;
            echo "======================================================" . PHP_EOL;
            $response = $advertiser_report_installs->count(
                $start_date,
                $end_date,
                $filter              = "(status = 'approved')",
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
            echo " Find Advertiser Logs Installs records.               " . PHP_EOL;
            echo "======================================================" . PHP_EOL;
            $response = $advertiser_report_installs->find(
                $start_date,
                $end_date,
                $fields              = $advertiser_report_installs->fields(AdvertiserReportInstalls::TUNE_FIELDS_RECOMMENDED),
                $filter              = "(status = 'approved')",
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

            echo "= TuneManagementResponse:" . PHP_EOL;
            echo print_r($response, true) . PHP_EOL;

            echo "=========================================================" . PHP_EOL;
            echo " Advertiser Logs Installs CSV report for export. " . PHP_EOL;
            echo "=========================================================" . PHP_EOL;
            $response = $advertiser_report_installs->export(
                $start_date,
                $end_date,
                $fields              = $advertiser_report_installs->fields(AdvertiserReportInstalls::TUNE_FIELDS_RECOMMENDED),
                $filter              = "(status = 'approved')",
                $format              = "csv",
                $response_timezone   = "America/Los_Angeles"
            );

            if (($response->getHttpCode() != 200) || ($response->getErrors() != null)) {
                throw new \Exception(
                    sprintf("Failed: %d: %s", $response->getHttpCode(), print_r($response, true))
                );
            }

            echo "= TuneManagementResponse:" . PHP_EOL;
            echo print_r($response, true) . PHP_EOL;

            $job_id = AdvertiserReportInstalls::parseResponseReportJobId($response);
            echo "= CSV Job ID: {$job_id}" . PHP_EOL;

            echo "=======================================================" . PHP_EOL;
            echo " Fetching Advertiser Logs Installs CSV report.         " . PHP_EOL;
            echo "=======================================================" . PHP_EOL;

            $response = $advertiser_report_installs->fetch(
                $job_id,
                $verbose = true
            );

            $report_url = AdvertiserReportInstalls::parseResponseReportUrl($response);
            echo "= CSV Report URL: {$report_url}" . PHP_EOL;

            echo "======================================================" . PHP_EOL;
            echo " Read AdvertiserReportInstalls CSV report and pretty print 5 lines.   " . PHP_EOL;
            echo "======================================================" . PHP_EOL;
            $csv_report_reader = new ReportReaderCSV(
                $report_url
            );

            $csv_report_reader->read();
            $csv_report_reader->prettyPrint($limit = 5);

            echo "======================================================" . PHP_EOL;
            echo " Advertiser AdvertiserReportInstalls JSON report for export.  " . PHP_EOL;
            echo "======================================================" . PHP_EOL;

            $response = $advertiser_report_installs->export(
                $start_date,
                $end_date,
                $fields              = $advertiser_report_installs->fields(AdvertiserReportInstalls::TUNE_FIELDS_RECOMMENDED),
                $filter              = "(status = 'approved')",
                $format              = "json",
                $response_timezone   = "America/Los_Angeles"
            );

            if (($response->getHttpCode() != 200) || ($response->getErrors() != null)) {
                throw new \Exception(
                    sprintf("Failed: %d: %s", $response->getHttpCode(), print_r($response, true))
                );
            }

            echo "= TuneManagementResponse:" . PHP_EOL;
            echo print_r($response, true) . PHP_EOL;

            $job_id = AdvertiserReportInstalls::parseResponseReportJobId($response);
            echo "= JSON Job ID: {$job_id}" . PHP_EOL;

            echo "========================================================" . PHP_EOL;
            echo " Fetching Advertiser Logs Installs JSON report          " . PHP_EOL;
            echo "========================================================" . PHP_EOL;

            $response = $advertiser_report_installs->fetch(
                $job_id,
                $verbose = true
            );

            $report_url = AdvertiserReportInstalls::parseResponseReportUrl($response);
            echo "= JSON Report URL: {$report_url}" . PHP_EOL;

            echo "======================================================" . PHP_EOL;
            echo " Read AdvertiserReportInstalls JSON report and pretty print 5 lines.  " . PHP_EOL;
            echo "======================================================" . PHP_EOL;

            $json_report_reader = new ReportReaderJSON(
                $report_url
            );

            $json_report_reader->read();
            $json_report_reader->prettyPrint($limit = 5);

            echo "\033[32m" . "======================================================" . "\033[0m" . PHP_EOL;
            echo "\033[32m" . "= End Example                                        =" . "\033[0m" . PHP_EOL;
            echo "\033[32m" . "======================================================" . "\033[0m" . PHP_EOL;
            echo PHP_EOL;

        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}

/**
 * Example request API_KEY
 */
if (count($argv) == 1) {
    echo sprintf("%s [api_key]", $argv[0]) . PHP_EOL;
    exit;
}

$api_key = $argv[1];

ExampleAdvertiserReportInstalls::run($api_key);
