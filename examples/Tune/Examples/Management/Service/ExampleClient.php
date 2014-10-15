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

namespace Tune\Examples\Management\Service;

global $argc, $argv;


/**
 * Class ExampleClient
 *
 * @package Tune\Examples\Management\Service
 *
 *
 */
class ExampleClient
{
    /**
     *
     * Constructor that prevents a default instance of this class from being created.
     */
    private function __construct()
    {

    }

    /**
     * Example of running successful requests to Tune MobileAppTracking Management API
     * through Tune PHP SDK.
     */
    public static function run($api_key)
    {
        try {
            \Tune\Examples\Management\Service\Client\ExampleClientAccount::run($api_key);
            \Tune\Examples\Management\Service\Client\ExampleClientActuals::run($api_key);
            \Tune\Examples\Management\Service\Client\ExampleClientLogs::run($api_key);
        } catch (\Tune\Common\TuneServiceException $ex) {
            echo 'TuneServiceException: ' . $ex->getMessage() . "\n";
        } catch (\Tune\Common\TuneSdkException $ex) {
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
    }
}

/**
 * Run Tune SDK successful request example
 */
if (count($argv) == 0) {
    exit;
}

$api_key = $argv[1];

ExampleClient::run($api_key);
