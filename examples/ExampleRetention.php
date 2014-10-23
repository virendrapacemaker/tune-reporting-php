<?php
/**
 * ExampleRetention.php
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
 * @package   Tune_PHP_SDK
 * @author    Jeff Tanner <jefft@tune.com>
 * @copyright 2014 Tune (http://www.tune.com)
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version   0.9.6
 * @link      https://developers.mobileapptracking.com Tune Developer Community @endlink
 *
 */

require_once dirname(__FILE__) . "/../src/TuneApi.php";

use Tune\Management\Api\Advertiser\Stats\Retention;
use Tune\Management\Api\Export;
use Tune\Management\Shared\Reports\ReportReaderCSV;
use Tune\Management\Shared\Reports\ReportReaderJSON;

global $argc, $argv;

/**
 * Example calling 'advertiser/stats/retention'
 *
 * @package Tune\Examples\Management\Api\Advertiser\Reports
 */
class ExampleRetention
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

        echo "=========================================================" . PHP_EOL;
        echo "= Tune Management API Advertiser Reports Logs Retention =" . PHP_EOL;
        echo "=========================================================" . PHP_EOL;

        try {
            date_default_timezone_set('UTC');

            $week_ago       = date('Y-m-d', strtotime("-8 days"));
            $yesterday      = date('Y-m-d', strtotime("-1 days"));
            $start_date     = "{$week_ago} 00:00:00";
            $end_date       = "{$yesterday} 23:59:59";

            $retention = new Retention($api_key, $validate = true);

            echo "======================================================" . PHP_EOL;
            echo " Fields of Advertiser Retention records.              " . PHP_EOL;
            echo "======================================================" . PHP_EOL;
            $response = $retention->getFields();
            echo print_r($response, true) . PHP_EOL;

            echo "======================================================" . PHP_EOL;
            echo " Count Advertiser Retention records.                  " . PHP_EOL;
            echo "======================================================" . PHP_EOL;
            $response = $retention->count(
                $start_date,
                $end_date,
                $cohort_type         = "click",
                $group               = "site_id,publisher_id",
                $cohort_interval     = null,
                $filter              = "(publisher_id > 0)",
                $response_timezone   = "America/Los_Angeles"
            );

            echo "= Response:" . PHP_EOL;
            echo print_r($response, true) . PHP_EOL;

            if ($response->getHttpCode() != 200) {
                throw new \Exception(
                    sprintf("Failed: %d: %s", $response->getHttpCode(), print_r($response->getErrors()))
                );
            }
            echo "= Count:" . $response->getData() . PHP_EOL;

            echo "======================================================" . PHP_EOL;
            echo " Find Advertiser Retention records.                   " . PHP_EOL;
            echo "======================================================" . PHP_EOL;
            $response = $retention->find(
                $start_date,
                $end_date,
                $cohort_type         = "install",
                $aggregation_type    = "cumulative",
                $group               = "site_id,publisher_id",
                $fields              = "site_id"
                . ",site.name"
                . ",install_publisher_id"
                . ",install_publisher.name"
                . ",installs"
                . ",opens",
                $cohort_interval     = null,
                $filter              = "(publisher_id > 0)",
                $limit               = 10,
                $page                = null,
                $sort                = array("year_day" => "ASC", "install_publisher_id" => "ASC"),
                $format              = "csv",
                $response_timezone   = "America/Los_Angeles"
            );

            echo "= advertiser/stats/retention/find.json csv response:" . PHP_EOL;
            echo print_r($response, true) . PHP_EOL;

            if ($response->getHttpCode() != 200) {
                throw new \Exception(
                    sprintf("Failed: %d: %s", $response->getHttpCode(), print_r($response->getErrors()))
                );
            }

            echo "======================================================" . PHP_EOL;
            echo " Find Advertiser Retention records.                   " . PHP_EOL;
            echo "======================================================" . PHP_EOL;
            $response = $retention->find(
                $start_date,
                $end_date,
                $cohort_type         = "install",
                $aggregation_type    = "cumulative",
                $group               = "site_id,publisher_id",
                $fields              = "site_id"
                . ",site.name"
                . ",install_publisher_id"
                . ",install_publisher.name"
                . ",installs"
                . ",opens",
                $cohort_interval     = null,
                $filter              = "(publisher_id > 0)",
                $limit               = 10,
                $page                = null,
                $sort                = array("year_day" => "ASC", "install_publisher_id" => "ASC"),
                $format              = "json",
                $response_timezone   = "America/Los_Angeles"
            );

            echo "= Response:" . PHP_EOL;
            echo print_r($response, true) . PHP_EOL;

            if ($response->getHttpCode() != 200) {
                throw new \Exception(
                    sprintf("Failed: %d: %s", $response->getHttpCode(), print_r($response->getErrors()))
                );
            }

            echo "==========================================================" . PHP_EOL;
            echo " Request Advertiser Retention CSV report for export.      " . PHP_EOL;
            echo "==========================================================" . PHP_EOL;
            $response = $retention->export(
                $start_date,
                $end_date,
                $cohort_type         = "install",
                $aggregation_type    = "cumulative",
                $group               = "site_id,publisher_id",
                $fields              = "site_id"
                . ",site.name"
                . ",install_publisher_id"
                . ",install_publisher.name"
                . ",installs"
                . ",opens",
                $cohort_interval     = null,
                $filter              = "(publisher_id > 0)",
                $response_timezone   = "America/Los_Angeles"
            );

            echo "= Response:" . PHP_EOL;
            echo print_r($response, true) . PHP_EOL;

            if ($response->getHttpCode() != 200) {
                throw new \Exception(
                    sprintf("Failed: %d: %s", $response->getHttpCode(), print_r($response->getErrors()))
                );
            }

            $data = $response->getData();
            if (is_null($data)) {
                throw new \Exception(
                    "Failed to return data: " . print_r($response, true)
                );
            }

            $job_id = $data;
            if (!is_string($job_id) || empty($job_id)) {
                throw new \Exception(
                    "Failed to return job_id: " . print_r($response, true)
                );
            }

            echo "= CSV Job ID: {$job_id}" . PHP_EOL;

            echo "=======================================================" . PHP_EOL;
            echo "Fetching Advertiser Retention report polling           " . PHP_EOL;
            echo "=======================================================" . PHP_EOL;

            $status = null;
            $response = null;
            $attempt = 0;

            while (true) {

                $response = $retention->status($job_id);

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

            $data = $response->getData();
            if (is_null($data)) {
                throw new \Exception(
                    "Failed to return data: " . print_r($response, true)
                );
            }

            $report_url = Retention::parseResponseUrl($response);
            echo "= CSV Report URL: {$report_url}" . PHP_EOL;

            echo "======================================================" . PHP_EOL;
            echo " Read Retention CSV report and pretty print 5 lines.  " . PHP_EOL;
            echo "======================================================" . PHP_EOL;
            $csv_report_reader = new ReportReaderCSV(
                $report_url
            );

            $csv_report_reader->read();
            $csv_report_reader->prettyPrint($limit = 5);

            echo "======================================================" . PHP_EOL;
            echo " Request Advertiser Retention JSON report for export. " . PHP_EOL;
            echo "======================================================" . PHP_EOL;

            $response = $retention->export(
                $start_date,
                $end_date,
                $cohort_type         = "install",
                $aggregation_type    = "cumulative",
                $group               = "site_id,publisher_id",
                $fields              = "site_id"
                . ",site.name"
                . ",install_publisher_id"
                . ",install_publisher.name"
                . ",installs"
                . ",opens",
                $cohort_interval     = null,
                $filter              = "(publisher_id > 0)",
                $response_timezone   = "America/Los_Angeles"
            );

            echo "= Response:" . PHP_EOL;
            echo print_r($response, true) . PHP_EOL;

            if ($response->getHttpCode() != 200) {
                throw new \Exception(
                    sprintf("Failed: %d: %s", $response->getHttpCode(), print_r($response->getErrors()))
                );
            }

            $data = $response->getData();
            if (is_null($data)) {
                throw new \Exception(
                    "Failed to return data: " . print_r($response, true)
                );
            }

            $job_id = $data;
            if (!is_string($job_id) || empty($job_id)) {
                throw new \Exception(
                    "Failed to return job_id: " . print_r($response, true)
                );
            }

            echo "= CSV Job ID: {$job_id}" . PHP_EOL;

            echo "========================================================" . PHP_EOL;
            echo "Fetching Advertiser Retention report threaded.          " . PHP_EOL;
            echo "========================================================" . PHP_EOL;

            $response = $retention->fetch(
                $job_id,
                $verbose = true,
                $sleep = 10
            );

            $report_url = Export::parseResponseUrl($response);
            echo "= CSV Report URL: {$report_url}" . PHP_EOL;

            echo "========================================================" . PHP_EOL;
            echo " Read Retention JSON report and pretty print 5 lines.   " . PHP_EOL;
            echo "========================================================" . PHP_EOL;

            $csv_report_reader = new ReportReaderCSV(
                $report_url
            );

            $csv_report_reader->read();
            $csv_report_reader->prettyPrint($limit = 5);

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

ExampleRetention::run($api_key);
