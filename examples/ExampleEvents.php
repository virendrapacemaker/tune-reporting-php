<?php
/**
 * ExampleEvents.php
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

use Tune\Management\Api\Advertiser\Stats\Events;
use Tune\Management\Api\Export;
use Tune\Management\Shared\Reports\ReportReaderCSV;
use Tune\Management\Shared\Reports\ReportReaderJSON;

global $argc, $argv;

/**
 * Class ExampleEvents
 *
 * @package Tune\Examples\Management\Api\Advertiser\Reports\Logs
 */
class ExampleEvents
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

        echo "======================================================" . PHP_EOL;
        echo "= Tune Management API Advertiser Reports Logs Events =" . PHP_EOL;
        echo "======================================================" . PHP_EOL;

        try {
            date_default_timezone_set('UTC');

            $yesterday      = date('Y-m-d', strtotime("-1 days"));
            $start_date     = "{$yesterday} 00:00:00";
            $end_date       = "{$yesterday} 23:59:59";

            $events = new Events($api_key, $validate = true);

            echo "======================================================" . PHP_EOL;
            echo " Fields of Advertiser Logs Events records.           " . PHP_EOL;
            echo "======================================================" . PHP_EOL;
            $response = $events->getFields();
            echo print_r($response, true) . PHP_EOL;

            echo "======================================================" . PHP_EOL;
            echo " Count Advertiser Logs Events records.               " . PHP_EOL;
            echo "======================================================" . PHP_EOL;
            $response = $events->count(
                $start_date,
                $end_date,
                $filter              = "(status = 'approved')",
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
            echo " Find Advertiser Logs Events records.                 " . PHP_EOL;
            echo "======================================================" . PHP_EOL;
            $response = $events->find(
                $start_date,
                $end_date,
                $filter              = "(status = 'approved')",
                $fields              = "id"
                . ",stat_install_id"
                . ",created"
                . ",status"
                . ",site_id"
                . ",site.name"
                . ",site_event_id"
                . ",site_event.name"
                . ",site_event.type"
                . ",publisher_id"
                . ",publisher.name"
                . ",advertiser_ref_id"
                . ",advertiser_sub_campaign_id"
                . ",advertiser_sub_campaign.ref"
                . ",publisher_sub_campaign_id"
                . ",publisher_sub_campaign.ref"
                . ",user_id"
                . ",device_id"
                . ",os_id"
                . ",google_aid"
                . ",ios_ifa"
                . ",ios_ifv"
                . ",windows_aid"
                . ",referral_url"
                . ",is_view_through"
                . ",is_reengagement",
                $limit               = 5,
                $page                = null,
                $sort                = array("created" => "DESC"),
                $response_timezone   = "America/Los_Angeles"
            );

            echo "= Response:" . PHP_EOL;
            echo print_r($response, true) . PHP_EOL;

            if ($response->getHttpCode() != 200) {
                throw new \Exception(
                    sprintf("Failed: %d: %s", $response->getHttpCode(), print_r($response->getErrors()))
                );
            }

            echo "============================================================" . PHP_EOL;
            echo " Request Advertiser Logs Events CSV report for export.      " . PHP_EOL;
            echo "============================================================" . PHP_EOL;
            $response = $events->export(
                $start_date,
                $end_date,
                $filter              = "(status = 'approved')",
                $fields              = "id"
                . ",stat_install_id"
                . ",created"
                . ",status"
                . ",site_id"
                . ",site.name"
                . ",site_event_id"
                . ",site_event.name"
                . ",site_event.type"
                . ",publisher_id"
                . ",publisher.name"
                . ",advertiser_ref_id"
                . ",advertiser_sub_campaign_id"
                . ",advertiser_sub_campaign.ref"
                . ",publisher_sub_campaign_id"
                . ",publisher_sub_campaign.ref"
                . ",user_id"
                . ",device_id"
                . ",os_id"
                . ",google_aid"
                . ",ios_ifa"
                . ",ios_ifv"
                . ",windows_aid"
                . ",referral_url"
                . ",is_view_through"
                . ",is_reengagement",
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

            echo "======================================================" . PHP_EOL;
            echo "Fetching Advertiser Logs Events report polling         " . PHP_EOL;
            echo "======================================================" . PHP_EOL;

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

            $data = $response->getData();
            if (is_null($data)) {
                throw new \Exception(
                    "Failed to return data: " . print_r($response, true)
                );
            }

            $report_url = Export::parseResponseUrl($response);
            echo "= CSV Report URL: {$report_url}" . PHP_EOL;

            echo "======================================================" . PHP_EOL;
            echo " Read Events CSV report and pretty print 5 lines.     " . PHP_EOL;
            echo "======================================================" . PHP_EOL;
            $csv_report_reader = new ReportReaderCSV(
                $report_url
            );

            $csv_report_reader->read();
            $csv_report_reader->prettyPrint($limit = 5);

            echo "======================================================" . PHP_EOL;
            echo " Request Advertiser Clicks JSON report for export.    " . PHP_EOL;
            echo "======================================================" . PHP_EOL;
            $response = $events->export(
                $start_date,
                $end_date,
                $filter              = "(status = 'approved')",
                $fields              = "id"
                . ",stat_install_id"
                . ",created"
                . ",status"
                . ",site_id"
                . ",site.name"
                . ",site_event_id"
                . ",site_event.name"
                . ",site_event.type"
                . ",publisher_id"
                . ",publisher.name"
                . ",advertiser_ref_id"
                . ",advertiser_sub_campaign_id"
                . ",advertiser_sub_campaign.ref"
                . ",publisher_sub_campaign_id"
                . ",publisher_sub_campaign.ref"
                . ",user_id"
                . ",device_id"
                . ",os_id"
                . ",google_aid"
                . ",ios_ifa"
                . ",ios_ifv"
                . ",windows_aid"
                . ",referral_url"
                . ",is_view_through"
                . ",is_reengagement",
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

            echo "= JSON Job ID: {$job_id}" . PHP_EOL;

            echo "======================================================" . PHP_EOL;
            echo "Fetching Advertiser Logs Events report threaded       " . PHP_EOL;
            echo "======================================================" . PHP_EOL;
            $export = new Export($api_key);

            $response = $export->fetch(
                $job_id,
                $verbose = true,
                $sleep = 10
            );

            $report_url = Export::parseResponseUrl($response);
            echo "= JSON Report URL: {$report_url}" . PHP_EOL;

            echo "======================================================" . PHP_EOL;
            echo " Read Clicks JSON report and pretty print 5 lines.    " . PHP_EOL;
            echo "======================================================" . PHP_EOL;

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
 * Example require API_KEY
 */
if (count($argv) == 1) {
    echo sprintf("%s [api_key]", $argv[0]) . PHP_EOL;
    exit;
}

$api_key = $argv[1];

ExampleEvents::run($api_key);