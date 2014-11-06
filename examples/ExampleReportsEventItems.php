<?php
/**
 * ExampleReportsEventItems.php
 *
 * Copyright (c) 2014 Tune, Inc
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
 * @category  Tune
 *
 * @author    Jeff Tanner <jefft@tune.com>
 * @copyright 2014 Tune (http://www.tune.com)
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version   $Date: 2014-11-03 15:03:06 $
 * @link      https://developers.mobileapptracking.com @endlink
 *
 */

require_once dirname(__FILE__) . "/../src/TuneApi.php";

use Tune\Management\Api\Advertiser\Stats\EventItems;
use Tune\Shared\ReportReaderCSV;
use Tune\Shared\ReportReaderJSON;

global $argc, $argv;

/**
 * Class ExampleReportsEventItems
 *
 * Using Tune\Management\Api\Advertiser\Stats\EventItems
 */
class ExampleReportsEventItems
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
    public static function run($api_key)
    {
        // api_key
        if (!is_string($api_key) || empty($api_key)) {
            throw new \InvalidArgumentException("Parameter 'api_key' is not defined.");
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

        echo "==========================================================" . PHP_EOL;
        echo "= Tune Management API Advertiser Reports Logs EventItems =" . PHP_EOL;
        echo "==========================================================" . PHP_EOL;

        try {
            $yesterday      = date('Y-m-d', strtotime("-1 days"));
            $start_date     = "{$yesterday} 00:00:00";
            $end_date       = "{$yesterday} 23:59:59";

            $event_items = new EventItems($api_key, $validate_fields = true);

            echo "======================================================" . PHP_EOL;
            echo " Fields of Advertiser Logs Event Items records.       " . PHP_EOL;
            echo "======================================================" . PHP_EOL;
            $response = $event_items->fields(EventItems::TUNE_FIELDS_RECOMMENDED);
            echo print_r($response, true) . PHP_EOL;

            echo "======================================================" . PHP_EOL;
            echo " Count Advertiser Logs Event Items records.           " . PHP_EOL;
            echo "======================================================" . PHP_EOL;
            $response = $event_items->count(
                $start_date,
                $end_date,
                $filter              = null,
                $response_timezone   = "America/Los_Angeles"
            );

            echo "= TuneManagementResponse:" . PHP_EOL;
            echo print_r($response, true) . PHP_EOL;

            if ($response->getHttpCode() != 200) {
                throw new \Exception(
                    sprintf("Failed: %d: %s", $response->getHttpCode(), print_r($response->getErrors()))
                );
            }
            echo "= Count:" . $response->getData() . PHP_EOL;

            echo "======================================================" . PHP_EOL;
            echo " Find Advertiser Logs Event Items records.            " . PHP_EOL;
            echo "======================================================" . PHP_EOL;
            $response = $event_items->find(
                $start_date,
                $end_date,
                $filter              = null,
                $fields              = $event_items->fields(EventItems::TUNE_FIELDS_DEFAULT | EventItems::TUNE_FIELDS_MINIMAL),
                $limit               = 5,
                $page                = null,
                $sort                = array("created" => "DESC"),
                $response_timezone   = "America/Los_Angeles"
            );

            echo "= TuneManagementResponse:" . PHP_EOL;
            echo print_r($response, true) . PHP_EOL;

            if ($response->getHttpCode() != 200) {
                throw new \Exception(
                    sprintf("Failed: %d: %s", $response->getHttpCode(), print_r($response->getErrors()))
                );
            }

            echo "============================================================" . PHP_EOL;
            echo " Advertiser Logs Event Items CSV report for export. " . PHP_EOL;
            echo "============================================================" . PHP_EOL;
            $response = $event_items->export(
                $start_date,
                $end_date,
                $filter              = null,
                $fields              = $event_items->fields(EventItems::TUNE_FIELDS_RECOMMENDED),
                $format              = "csv",
                $response_timezone   = "America/Los_Angeles"
            );

            echo "= TuneManagementResponse:" . PHP_EOL;
            echo print_r($response, true) . PHP_EOL;

            if ($response->getHttpCode() != 200) {
                throw new \Exception(
                    sprintf("Failed: %d: %s", $response->getHttpCode(), print_r($response->getErrors()))
                );
            }

            $job_id = EventItems::parseResponseReportJobId($response);
            echo "= CSV Job ID: {$job_id}" . PHP_EOL;

            echo "=======================================================" . PHP_EOL;
            echo " Fetching Advertiser Logs Event Items CSV report.      " . PHP_EOL;
            echo "=======================================================" . PHP_EOL;

            $response = $event_items->fetch(
                $job_id,
                $verbose = true,
                $sleep = 10
            );

            $report_url = EventItems::parseResponseReportUrl($response);
            echo "= CSV Report URL: {$report_url}" . PHP_EOL;

            echo "===========================================================" . PHP_EOL;
            echo " Read Event Items CSV report and pretty print 5 lines.     " . PHP_EOL;
            echo "===========================================================" . PHP_EOL;
            $csv_report_reader = new ReportReaderCSV(
                $report_url
            );

            $csv_report_reader->read();
            $csv_report_reader->prettyPrint($limit = 5);

            echo "======================================================" . PHP_EOL;
            echo " Advertiser Event Items JSON report for export.     " . PHP_EOL;
            echo "======================================================" . PHP_EOL;

            $response = $event_items->export(
                $start_date,
                $end_date,
                $filter              = null,
                $fields              = $event_items->fields(EventItems::TUNE_FIELDS_RECOMMENDED),
                $format              = "json",
                $response_timezone   = "America/Los_Angeles"
            );

            echo "= TuneManagementResponse:" . PHP_EOL;
            echo print_r($response, true) . PHP_EOL;

            if ($response->getHttpCode() != 200) {
                throw new \Exception(
                    sprintf("Failed: %d: %s", $response->getHttpCode(), print_r($response->getErrors()))
                );
            }

            $job_id = EventItems::parseResponseReportJobId($response);
            echo "= JSON Job ID: {$job_id}" . PHP_EOL;

            echo "========================================================" . PHP_EOL;
            echo " Fetching Advertiser Logs Event Items JSON report.      " . PHP_EOL;
            echo "========================================================" . PHP_EOL;

            $response = $event_items->fetch(
                $job_id,
                $verbose = true,
                $sleep = 10
            );

            $report_url = EventItems::parseResponseReportUrl($response);
            echo "= JSON Report URL: {$report_url}" . PHP_EOL;

            echo "===========================================================" . PHP_EOL;
            echo " Read Event Items JSON report and pretty print 5 lines.    " . PHP_EOL;
            echo "===========================================================" . PHP_EOL;

            $json_report_reader = new ReportReaderJSON(
                $report_url
            );

            $json_report_reader->read();
            $json_report_reader->prettyPrint($limit = 5);

            echo "======================================================" . PHP_EOL;
            echo " End Example                                          " . PHP_EOL;
            echo "======================================================" . PHP_EOL;
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

ExampleReportsEventItems::run($api_key);
