<?php
/**
 * TuneReportingExamples.php
 * Examples using SDK which accesses service of TUNE Reporting API.
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
 * @version   $Date: 2014-12-24 10:43:56 $
 * @link      https://developers.mobileapptracking.com/tune-reporting-sdks @endlink
 *
 */

require_once dirname(__FILE__) . "/../src/TuneReporting.php";
require_once dirname(__FILE__) . "/TuneReportingExamplesAutoloader.php";

use TuneReporting\Helpers\TuneSdkException;
use TuneReporting\Helpers\TuneServiceException;
use TuneReporting\Helpers\SdkConfig;

/**
 *
 * Examples using SDK which accesses service of TUNE Reporting API.
 *
 */
class TuneReportingExamples
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
     * Example of running successful requests to TUNE MobileAppTracking Management API
     * through TUNE PHP SDK.
     */
    public static function run()
    {
        $tune_reporting_config_file = dirname(__FILE__) . "/../tune_reporting_sdk.config";
        $sdk_config = SdkConfig::getInstance($tune_reporting_config_file);

        $api_key = $sdk_config->getApiKey();

        // api_key
        if (!is_string($api_key) || empty($api_key)) {
            throw new \InvalidArgumentException("SDK Configuration 'api_key' is not defined.");
        }

        echo "\n============================================\n";
        echo   " TUNE Reporting API SDK for PHP Examples    \n";
        echo   " SDK version: " . \TuneReporting\Base\Service\TuneManagementClient::Version() . " \n";
        echo   "============================================\n";
        echo "\n";

        try {
            ExampleTuneManagementClient::run($api_key);

            ExampleAdvertiserReportActuals::run();
            ExampleAdvertiserReportCohortValue::run();
            ExampleAdvertiserReportCohortRetention::run();

            ExampleAdvertiserReportLogClicks::run();
            ExampleAdvertiserReportLogEventItems::run();
            ExampleAdvertiserReportLogEvents::run();
            ExampleAdvertiserReportLogInstalls::run();
            ExampleAdvertiserReportLogPostbacks::run();
        } catch (\TuneReporting\Helpers\TuneServiceException $ex) {
            echo 'TuneServiceException: ' . $ex->getMessage() . "\n";
        } catch (\TuneReporting\Helpers\TuneSdkException $ex) {
            echo 'TuneSdkException: ' . $ex->getMessage() . "\n";
        } catch (\InvalidArgumentException $ex) {
            echo 'Invalid arguments: ' . $ex->getMessage() . "\n";
            echo $ex->getTraceAsString();
        } catch (\UnexpectedValueException $ex) {
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
        echo   "   The End                  \n";
        echo   "==============================\n";
    }
}

TuneReportingExamples::run();
