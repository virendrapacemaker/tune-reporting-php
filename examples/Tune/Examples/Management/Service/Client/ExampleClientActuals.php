<?php
/**
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

namespace Tune\Examples\Management\Service\Client;

/**
 * Class ExampleClientActuals
 *
 * @package Tune\Examples\Management\Service\Client
 */
class ExampleClientActuals
{

    /**
     * Constructor that prevents a default instance of this class from being created.
     */
    private function __construct()
    {

    }

    /**
     *
     * Example of running successful requests to Tune MobileAppTracking Management API
     * through Tune PHP SDK.
     */
    public static function run($api_key)
    {
        // api_key
        if (!is_string($api_key) || empty($api_key)) {
            throw new \InvalidArgumentException("Parameter 'api_key' is not defined.");
        }

        date_default_timezone_set('UTC');

        $week_ago       = date('Y-m-d', strtotime("-8 days"));
        $yesterday      = date('Y-m-d', strtotime("-1 days"));
        $start_date     = "{$week_ago} 00:00:00";
        $end_date       = "{$yesterday} 23:59:59";

        $client = new \Tune\Management\Service\TuneManagementClient(
            $controller = 'advertiser/stats',
            $action = 'find.json',
            $api_key,
            $query_string_dict = array(
                "start_date" => $start_date,
                "end_date" => $end_date,
                "fields" => "ad_clicks,ad_clicks_unique,ad_impressions,ad_impressions_unique",
                "limit" => 5
            )
        );

        $client->call();

        echo $client->getResponse()->toString() . PHP_EOL;
    }
}
