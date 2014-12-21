<?php
/**
 * ExampleTuneManagementClient.php, TUNE Reporting SDK PHP Example
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
 * @version   $Date: 2014-12-21 09:06:23 $
 * @link      https://developers.mobileapptracking.com/tune-reporting-sdks @endlink
 *
 */

require_once dirname(__FILE__) . "/../src/TuneReporting.php";

use TuneReporting\Base\Service\TuneManagementClient;
use TuneReporting\Helpers\SdkConfig;

/**
 * Class ExampleTuneManagementClient
 *
 * Using TuneManagementClient to connect with 'account/users'
 */
class ExampleTuneManagementClient
{
    /**
     * Constructor that prevents a default instance of this class from being created.
     */
    private function __construct()
    {

    }

    /**
     * Execute example.
     *
     * @param string $api_key MobileAppTracking API Key
     *
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public static function run()
    {
        $tune_reporting_config_file = dirname(__FILE__) . "/../tune_reporting_sdk.config";
        $sdk_config = SdkConfig::getInstance($tune_reporting_config_file);
        $api_key = $sdk_config->api_key();

        // api_key
        if (!is_string($api_key) || empty($api_key)) {
            throw new \InvalidArgumentException("Parameter 'api_key' is not defined.");
        }

        try {
            echo "\033[34m" . "============================================" . "\033[0m" . PHP_EOL;
            echo "\033[34m" . " Begin Example TUNE Reporting API Client               " . "\033[0m" . PHP_EOL;
            echo "\033[34m" . "============================================" . "\033[0m" . PHP_EOL;

            $client = new TuneManagementClient(
                $controller = 'account/users',
                $action = 'find.json',
                $api_key,
                $query_string_dict = array(
                    "fields" => "first_name,last_name,email",
                    "limit" => 5
                )
            );

            $client->call();

            echo " TuneManagementResponse:" . PHP_EOL;
            echo print_r($client->getResponse(), true) . PHP_EOL;

            echo "\033[32m" . "==========================" . "\033[0m" . PHP_EOL;
            echo "\033[32m" . " End Example              " . "\033[0m" . PHP_EOL;
            echo "\033[32m" . "==========================" . "\033[0m" . PHP_EOL;
            echo PHP_EOL;

        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}

ExampleTuneManagementClient::run();
