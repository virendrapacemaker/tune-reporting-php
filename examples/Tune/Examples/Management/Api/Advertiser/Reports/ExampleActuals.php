<?php
/**
 * ExampleActuals.php
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
 * @version   0.9.4
 * @link      https://developers.mobileapptracking.com Tune Developer Community @endlink
 *
 */

namespace Tune\Examples\Management\Api\Advertiser\Reports;

require_once dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))))) . "/../lib/TuneApi.php";

use Tune\Management\Api\Advertiser\Stats;
use Tune\Management\Api\Export;
use Tune\Management\Shared\Reports\ReportReaderCSV;

global $argc, $argv;

/**
 * Class ExampleActuals
 *
 * @package Tune\Examples\Management\Api\Advertiser\Reports
 */
class ExampleActuals
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
        echo "= Tune Management API Advertiser Reports Actuals        =" . PHP_EOL;
        echo "=========================================================" . PHP_EOL;

        try {
            date_default_timezone_set('UTC');

            $week_ago       = date('Y-m-d', strtotime("-8 days"));
            $yesterday      = date('Y-m-d', strtotime("-1 days"));
            $start_date     = "{$week_ago} 00:00:00";
            $end_date       = "{$yesterday} 23:59:59";

            $stats = new Stats($api_key, $validate = true);

            echo "======================================================" . PHP_EOL;
            echo " Fields of Advertiser Actuals records.                " . PHP_EOL;
            echo "======================================================" . PHP_EOL;
            $response = $stats->getFields();
            echo print_r($response, true) . PHP_EOL;

            echo "======================================================" . PHP_EOL;
            echo " Count Advertiser Actuals records.                    " . PHP_EOL;
            echo "======================================================" . PHP_EOL;
            $response = $stats->count(
                $start_date,
                $end_date,
                $group               = "site_id"
                . ",publisher_id",
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
            echo " Find Advertiser Actuals records.                     " . PHP_EOL;
            echo "======================================================" . PHP_EOL;
            $response = $stats->find(
                $start_date,
                $end_date,
                $group               = "site_id"
                . ",publisher_id",
                $filter              = "(publisher_id > 0)",
                $fields              = "site_id"
                . ",site.name"
                . ",publisher_id"
                . ",publisher.name"
                . ",ad_impressions"
                . ",ad_impressions_unique"
                . ",ad_clicks"
                . ",ad_clicks_unique"
                . ",paid_installs"
                . ",paid_installs_assists"
                . ",non_installs_assists"
                . ",paid_events"
                . ",paid_events_assists"
                . ",non_events_assists"
                . ",paid_opens"
                . ",paid_opens_assists"
                . ",non_opens_assists",
                $limit               = 5,
                $page                = null,
                $sort                = array("installs" => "DESC"),
                $timestamp           = null,
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
            echo " Request Advertiser Actuals CSV report for export.        " . PHP_EOL;
            echo "==========================================================" . PHP_EOL;
            $response = $stats->export(
                $start_date,
                $end_date,
                $group               = "site_id"
                . ",publisher_id",
                $filter              = "(publisher_id > 0)",
                $fields              = "site_id"
                . ",site.name"
                . ",publisher_id"
                . ",publisher.name"
                . ",ad_impressions"
                . ",ad_impressions_unique"
                . ",ad_clicks"
                . ",ad_clicks_unique"
                . ",paid_installs"
                . ",paid_installs_assists"
                . ",non_installs_assists"
                . ",paid_events"
                . ",paid_events_assists"
                . ",non_events_assists"
                . ",paid_opens"
                . ",paid_opens_assists"
                . ",non_opens_assists",
                $timestamp           = null,
                $format              = "csv",
                $response_timezone   = "America/Los_Angeles"
            );

            echo "= Response:" . PHP_EOL;
            echo print_r($response, true) . PHP_EOL;

            if ($response->getHttpCode() != 200) {
                throw new \Exception(
                    sprintf("Failed: %d: %s", $response->getHttpCode(), print_r($response->getErrors()))
                );
            }
            echo "= Job ID: " . print_r($response->getData(), true) . PHP_EOL;

            $job_id = $response->getData();

            echo "=======================================================" . PHP_EOL;
            echo "Fetching Advertiser Actuals report polling             " . PHP_EOL;
            echo "=======================================================" . PHP_EOL;

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

            echo "======================================================" . PHP_EOL;
            echo " Read Actuals CSV report and pretty print 5 lines.    " . PHP_EOL;
            echo "======================================================" . PHP_EOL;
            $csv_report_reader = new ReportReaderCSV(
                $report_url
            );

            $csv_report_reader->read();
            $csv_report_reader->prettyPrint($limit = 5);

            echo "======================================================" . PHP_EOL;
            echo " Request Advertiser Actuals JSON report for export.   " . PHP_EOL;
            echo "======================================================" . PHP_EOL;

            $response = $stats->export(
                $start_date,
                $end_date,
                $group               = "site_id"
                . ",publisher_id",
                $filter              = "(publisher_id > 0)",
                $fields              = "site_id"
                . ",site.name"
                . ",publisher_id"
                . ",publisher.name"
                . ",ad_impressions"
                . ",ad_impressions_unique"
                . ",ad_clicks"
                . ",ad_clicks_unique"
                . ",paid_installs"
                . ",paid_installs_assists"
                . ",non_installs_assists"
                . ",paid_events"
                . ",paid_events_assists"
                . ",non_events_assists"
                . ",paid_opens"
                . ",paid_opens_assists"
                . ",non_opens_assists",
                $timestamp           = null,
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
            echo "= Job ID: " . print_r($response->getData(), true) . PHP_EOL;

            $job_id = $response->getData();

            echo "========================================================" . PHP_EOL;
            echo "Fetching Advertiser Actuals report threaded.            " . PHP_EOL;
            echo "========================================================" . PHP_EOL;

            $export = new \Tune\Management\Api\Export($api_key);

            $json_report_reader = $export->fetch(
                $job_id,
                $report_format = "json",
                $verbose = true,
                $sleep = 10
            );

            echo "========================================================" . PHP_EOL;
            echo " Read Actuals JSON report and pretty print 5 lines.     " . PHP_EOL;
            echo "========================================================" . PHP_EOL;
            $json_report_reader->read();
            $json_report_reader->prettyPrint($limit = 5);

            echo "======================================================" . PHP_EOL;

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

ExampleActuals::run($api_key);
