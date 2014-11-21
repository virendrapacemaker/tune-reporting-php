<?php
/**
 * ExampleItemsAccountUsers.php
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
 * @version   $Date: 2014-11-19 21:21:08 $
 * @link      https://developers.mobileapptracking.com @endlink
 *
 */

require_once dirname(__FILE__) . "/../src/TuneApi.php";

use Tune\Management\Api\Account\Users;
use Tune\Management\Shared\Endpoints\EndpointBase;
use Tune\Shared\ReportReaderCSV;
use Tune\Shared\ReportReaderJSON;

global $argc, $argv;

/**
 * Class ExampleItemsAccountUsers
 *
 * Using Tune\Management\Api\Advertiser\Stats\Users
 */
class ExampleItemsAccountUsers
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
        echo "= Tune Management API Items Account Users               =" . PHP_EOL;
        echo "=========================================================" . PHP_EOL;

        try {
            $account_users = new Users($api_key, $validate_fields = true);

            echo "======================================================" . PHP_EOL;
            echo " Fields of Items Account Users records.               " . PHP_EOL;
            echo "======================================================" . PHP_EOL;
            $response = $account_users->fields();
            echo print_r($response, true) . PHP_EOL;

            echo "======================================================" . PHP_EOL;
            echo " Count Items Account Users records.                   " . PHP_EOL;
            echo "======================================================" . PHP_EOL;
            $response = $account_users->count(
                $filter              = null
            );

            echo "= TuneManagementResponse:" . PHP_EOL;
            echo print_r($response, true) . PHP_EOL;

            if (($response->getHttpCode() != 200) || ($response->getErrors() != null)) {
                throw new \Exception(
                    sprintf("Failed: %d: %s", $response->getHttpCode(), print_r($response, true))
                );
            }

            echo "= Count:" . $response->getData() . PHP_EOL;

            echo "======================================================" . PHP_EOL;
            echo " Find Items Account Users records.                    " . PHP_EOL;
            echo "======================================================" . PHP_EOL;

            $response = $account_users->find(
                $fields              = $account_users->fields(),
                $filter              = null,
                $limit               = 5,
                $page                = null,
                $sort                = array("created" => "DESC")
            );

            if (($response->getHttpCode() != 200) || ($response->getErrors() != null)) {
                throw new \Exception(
                    sprintf("Failed: %d: %s", $response->getHttpCode(), print_r($response, true))
                );
            }

            echo "= TuneManagementResponse:" . PHP_EOL;
            echo print_r($response, true) . PHP_EOL;

            echo "==========================================================" . PHP_EOL;
            echo " Export Account Users CSV report.                         " . PHP_EOL;
            echo "==========================================================" . PHP_EOL;

            $response = $account_users->export(
                $fields              = $account_users->fields(),
                $filter              = null,    // filter
                $format              = "csv"
            );

            if (($response->getHttpCode() != 200) || ($response->getErrors() != null)) {
                throw new \Exception(
                    sprintf("Failed: %d: %s", $response->getHttpCode(), print_r($response, true))
                );
            }

            echo "= TuneManagementResponse:" . PHP_EOL;
            echo print_r($response, true) . PHP_EOL;

            $csv_job_id = Users::parseResponseReportJobId($response);
            echo "= CSV Job ID: {$csv_job_id}" . PHP_EOL;

            echo "=======================================================" . PHP_EOL;
            echo " Fetching Account Users CSV report.                    " . PHP_EOL;
            echo "=======================================================" . PHP_EOL;

            $response = $account_users->fetch(
                $csv_job_id,
                $verbose = true,
                $sleep = 10
            );

            $report_url = Users::parseResponseReportUrl($response);
            echo "= CSV Report URL: {$report_url}" . PHP_EOL;

            echo "=============================================================" . PHP_EOL;
            echo " Print Account Users CSV report.                             " . PHP_EOL;
            echo "=============================================================" . PHP_EOL;
            $csv_report_reader = new ReportReaderCSV(
                $report_url
            );

            $csv_report_reader->read();
            $csv_report_reader->prettyPrint($limit = 5);

            echo "======================================================" . PHP_EOL;
            echo " Export Account Users JSON report.                    " . PHP_EOL;
            echo "======================================================" . PHP_EOL;

            $response = $account_users->export(
                $fields              = $account_users->fields(),
                $filter              = null,    // filter
                $format              = "json"
            );

            if (($response->getHttpCode() != 200) || ($response->getErrors() != null)) {
                throw new \Exception(
                    sprintf("Failed: %d: %s", $response->getHttpCode(), print_r($response, true))
                );
            }

            echo "= TuneManagementResponse:" . PHP_EOL;
            echo print_r($response, true) . PHP_EOL;

            $json_job_id = Users::parseResponseReportJobId($response);
            echo "= JSON Job ID: {$json_job_id}" . PHP_EOL;

            echo "========================================================" . PHP_EOL;
            echo " Fetching Account Users JSON report.                    " . PHP_EOL;
            echo "========================================================" . PHP_EOL;

            $response = $account_users->fetch(
                $json_job_id,
                $verbose = true,
                $sleep = 10
            );

            $report_url = Users::parseResponseReportUrl($response);
            echo "= JSON Report URL: {$report_url}" . PHP_EOL;

            echo "===========================================================" . PHP_EOL;
            echo " Print Account Users JSON report.                          " . PHP_EOL;
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

ExampleItemsAccountUsers::run($api_key);
