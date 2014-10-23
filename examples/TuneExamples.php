<?php
/**
 * TuneSDK_Examples.php
 * Examples using SDK which accesses service of Tune Management API.
 *
 */

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
 * @version   0.9.5
 * @link      https://developers.mobileapptracking.com Tune Developer Community @endlink
 *
 */

namespace Tune\Examples;

require_once dirname(__FILE__) . "/../lib/TuneApi.php";
require_once dirname(__FILE__) . "/TuneExamplesAutoloader.php";

global $argc, $argv;

/**
 *
 * Examples using SDK which accesses service of Tune Management API.
 *
 */
class TuneExamples
{
    /**
     *
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

        echo "\n==============================\n";
        echo   "= Tune PHP SDK Example =\n";
        echo   "= SDK version: " . \Tune\Management\Shared\Service\TuneManagementClient::Version() . " =\n";
        echo   "==============================\n";
        echo "\n";

        try {
            \Tune\Examples\Management\Service\ExampleClient::run($api_key);

            \Tune\Examples\Management\Api\Advertiser\Reports\Logs\ExampleClicks::run($api_key);
            \Tune\Examples\Management\Api\Advertiser\Reports\Logs\ExampleEventItems::run($api_key);
            \Tune\Examples\Management\Api\Advertiser\Reports\Logs\ExampleEvents::run($api_key);
            \Tune\Examples\Management\Api\Advertiser\Reports\Logs\ExampleInstalls::run($api_key);
            \Tune\Examples\Management\Api\Advertiser\Reports\Logs\ExamplePostbacks::run($api_key);

            \Tune\Examples\Management\Api\Advertiser\Reports\ExampleActuals::run($api_key);
            \Tune\Examples\Management\Api\Advertiser\Reports\ExampleCohort::run($api_key);
            \Tune\Examples\Management\Api\Advertiser\Reports\ExampleRetention::run($api_key);

        } catch (\Tune\Shared\TuneServiceException $ex) {
            echo 'TuneServiceException: ' . $ex->getMessage() . "\n";
        } catch (\Tune\Shared\TuneSdkException $ex) {
            echo 'TuneSdkException: ' . $ex->getMessage() . "\n";
        } catch (\InvalidArgumentException $ex) {
            echo 'Invalid arguments: ' . $ex->getMessage() . "\n";
            echo $ex->getTraceAsString();
        } catch (\UnexpectedValueException $ex){
            echo 'Unexpected Value: ' . $ex->getMessage() . "\n";
            echo $ex->getTraceAsString();
        } catch (\RuntimeException $ex) {
            echo 'Runtime Exception: ' . $ex->getMessage() . "\n";
            echo $ex->getTraceAsString();
        } catch (\Exception $ex) {
            echo 'Exception: ' . $ex->getMessage() . "\n";
            echo $ex->getTraceAsString();
        }

        echo "\n==============================\n";
        echo   "=   The End                  =\n";
        echo   "==============================\n";
    }
}

/**
 * Examples request API_KEY
 */
if (count($argv) == 1) {
    echo sprintf("%s [api_key]", $argv[0]) . PHP_EOL;
    exit;
}

$api_key = $argv[1];

TuneExamples::run($api_key);
