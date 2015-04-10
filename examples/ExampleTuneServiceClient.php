<?php
/**
 * ExampleTuneServiceClient.php, TUNE Reporting SDK PHP Example
 *
 * Copyright (c) 2015 TUNE, Inc.
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
 * @author  Jeff Tanner <jefft@tune.com>
 * @copyright 2015 TUNE, Inc. (http://www.tune.com)
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version   $Date: 2015-04-09 17:36:25 $
 * @link    https://developers.mobileapptracking.com/tune-reporting-sdks @endlink
 *
 */

require_once dirname(__FILE__) . "/../src/TuneReporting.php";

use TuneReporting\Base\Service\TuneServiceClient;
use TuneReporting\Helpers\SdkConfig;

global $argc, $argv;

/**
 * Class ExampleTuneServiceClient
 *
 * Using TuneServiceClient to connect with 'account/users'
 */
class ExampleTuneServiceClient
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
   * @param string $auth_key  MobileAppTracking API Key or Session Token.
   * @param string $auth_type TUNE Reporting Authentication Type.
   *
   * @throws InvalidArgumentException
   * @throws RuntimeException
   * @throws Exception
   */
  public static function run(
    $auth_key,
    $auth_type
  ) {
    $tune_reporting_config_file
      = dirname(__FILE__) . "/../config/tune_reporting_sdk.config";

    if (!file_exists($tune_reporting_config_file)) {
      throw new \InvalidArgumentException(
        "TUNE Reporting Config '$tune_reporting_config_file' does not exist."
      );
    }

    // Get instance of TUNE Reporting SDK configuration.
    $sdk_config = SdkConfig::getInstance($tune_reporting_config_file);

    // Override Authentication setting of TUNE Reporting SDK configuration.
    if (is_string($auth_key) && !empty($auth_key)) {
      if (("api_key" == $auth_type)) {
        $sdk_config->setApiKey($auth_key);
      } elseif ("session_token" == $auth_type) {
        $sdk_config->setSessionToken($auth_key);
      } else {
        throw new \InvalidArgumentException(
          "Parameter 'auth_type' is invalid authentication type: '$auth_type'."
        );
      }
    }

    $sdk_config->setValidateFields(true);

    try {
      echo PHP_EOL;
      echo "\033[34m" . "============================================" . "\033[0m" . PHP_EOL;
      echo "\033[34m" . " Begin TuneServiceClient '{$auth_type}'     " . "\033[0m" . PHP_EOL;
      echo "\033[34m" . "============================================" . "\033[0m" . PHP_EOL;

      $client = new TuneServiceClient(
        $controller = 'account/users',
        $action = 'find.json',
        $auth_key,
        $auth_type,
        $map_query_string = array(
          "fields" => "first_name,last_name,email",
          "limit" => 5
        )
      );

      $client->call();

      $response = $client->getResponse();

      echo " TuneServiceResponse:" . PHP_EOL;
      echo $response->toString() . PHP_EOL;

      echo " JSON:" . PHP_EOL;
      echo print_r($response->toJson(), true) . PHP_EOL;

      echo "\033[32m" . "==========================" . "\033[0m" . PHP_EOL;
      echo "\033[32m" . " End Example              " . "\033[0m" . PHP_EOL;
      echo "\033[32m" . "==========================" . "\033[0m" . PHP_EOL;
      echo PHP_EOL;

    } catch (\Exception $ex) {
      throw $ex;
    }
  }
}