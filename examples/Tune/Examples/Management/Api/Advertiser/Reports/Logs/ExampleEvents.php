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
 * @version   0.9.1
 * @link      https://developers.mobileapptracking.com Tune Developer Community @endlink
 *
 */

namespace Tune\Examples\Management\Api\Advertiser\Reports\Logs;

use Tune\Management\Api\Advertiser\Stats\Events;
use Tune\Management\Api\Export;

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

            $yesterday      = date('Y-m-d', strtotime("-1 days"));
            $start_date     = "{$yesterday} 00:00:00";
            $end_date       = "{$yesterday} 23:59:59";

            $events = new Events($api_key, $validate = true);

            echo "======================================================" . PHP_EOL;
            echo "= advertiser/stats/events all fields =" . PHP_EOL;
            $response = $events->getFields();
            echo print_r($response, true) . PHP_EOL;

            echo "======================================================" . PHP_EOL;
            echo "= advertiser/stats/events/count.json request =" . PHP_EOL;
            $response = $events->count(
                $start_date,
                $end_date,
                $filter              = null,
                $response_timezone   = "America/Los_Angeles"
            );

            echo "= advertiser/stats/events/count.json response:" . PHP_EOL;
            echo print_r($response, true) . PHP_EOL;

            if ($response->getHttpCode() != 200) {
                throw new \Exception(
                    sprintf("Failed: %d: %s", $response->getHttpCode(), print_r($response->getErrors()))
                );
            }
            echo "= Count:" . $response->getData() . PHP_EOL;

            echo "======================================================" . PHP_EOL;
            echo "= advertiser/stats/events/find.json request =" . PHP_EOL;
            $response = $events->find(
                $start_date,
                $end_date,
                $filter              = "(status = 'approved') AND (publisher_id > 0)",
                $fields              = "created,site.name,campaign.name,install_publisher.name"
                . ",publisher.name,site_event.name,event_type,payout,revenue_usd,sdk"
                . ",sdk_version,package_name,app_name,app_version,country.name"
                . ",campaign_id,install_publisher_id,publisher_id,site_event_id"
                . ",country_id,id,currency_code,revenue,site_event_items_count",
                $limit               = 5,
                $page                = null,
                $sort                = array("created" => "DESC"),
                $response_timezone   = "America/Los_Angeles"
            );

            echo "= advertiser/stats/events/find.json response:" . PHP_EOL;
            echo print_r($response, true) . PHP_EOL;

            if ($response->getHttpCode() != 200) {
                throw new \Exception(
                    sprintf("Failed: %d: %s", $response->getHttpCode(), print_r($response->getErrors()))
                );
            }

            echo "======================================================" . PHP_EOL;
            echo "= advertiser/stats/events/find_export_queue.json request =" . PHP_EOL;
            $response = $events->export(
                $start_date,
                $end_date,
                $filter              = "(status = 'approved') AND (publisher_id > 0)",
                $fields              = "created,site.name,campaign.name,install_publisher.name"
                . ",publisher.name,site_event.name,event_type,payout,revenue_usd,sdk"
                . ",sdk_version,package_name,app_name,app_version,country.name"
                . ",campaign_id,install_publisher_id,publisher_id,site_event_id"
                . ",country_id,id,currency_code,revenue,site_event_items_count",
                $format              = "csv",
                $response_timezone   = "America/Los_Angeles"
            );

            echo "= advertiser/stats/events/find_export_queue.json response:" . PHP_EOL;
            echo print_r($response, true) . PHP_EOL;

            if ($response->getHttpCode() != 200) {
                throw new \Exception(
                    sprintf("Failed: %d: %s", $response->getHttpCode(), print_r($response->getErrors()))
                );
            }
            echo "= Job ID: " . print_r($response->getData(), true) . PHP_EOL;

            $job_id = $response->getData();

            echo "======================================================" . PHP_EOL;

            $export = new Export($api_key);

            $csv_report_reader = $export->fetch(
                $job_id,
                $report_format = "csv",
                $verbose = true,
                $sleep = 10
            );

            $csv_report_reader->read();
            $csv_report_reader->prettyPrint($limit = 5);

            echo "======================================================" . PHP_EOL;
            echo "= advertiser/stats/events/find_export_queue.json request =" . PHP_EOL;
            $response = $events->export(
                $start_date,
                $end_date,
                $filter              = null,
                $fields              = null,
                $format              = "json",
                $response_timezone   = "America/Los_Angeles"
            );

            echo "= advertiser/stats/events/find_export_queue.json response:" . PHP_EOL;
            echo print_r($response, true) . PHP_EOL;

            if ($response->getHttpCode() != 200) {
                throw new \Exception(
                    sprintf("Failed: %d: %s", $response->getHttpCode(), print_r($response->getErrors()))
                );
            }
            echo "= Job ID: " . print_r($response->getData(), true) . PHP_EOL;

            $job_id = $response->getData();

            echo "======================================================" . PHP_EOL;

            $export = new Export($api_key);

            $json_report_reader = $export->fetch(
                $job_id,
                $report_format = "json",
                $verbose = true,
                $sleep = 10
            );

            $json_report_reader->read();
            $json_report_reader->prettyPrint($limit = 5);

        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}
