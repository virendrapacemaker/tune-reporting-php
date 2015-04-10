<?php
/**
 * SdkConfig.php
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
 * @package   tune_reporting_helpers
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version   $Date: 2015-04-09 17:36:25 $
 * @link    https://developers.mobileapptracking.com/tune-reporting-sdks @endlink
 *
 */

namespace TuneReporting\Helpers;

use TuneReporting\Helpers\TuneSdkException;

/**
 * Config reads configuration file tune_reporting_sdk.config
 */
class SdkConfig
{
  /**
   * SDK Config file path
   * @var string
   */
  private $tune_reporting_config_file = null;
  /**
   *
   * Parsed SDK config file's contents
   * @var array
   */
  private $tune_reporting_config = null;

  /**
   * Constructs by reading the SDK configuration settings from tune_reporting_sdk.config.
   *
   * @throws TuneSdkException
   */
  private function __construct($tune_reporting_config_file)
  {
    $this->tune_reporting_config_file = $tune_reporting_config_file;
    if (!file_exists($this->tune_reporting_config_file)) {
      throw new TuneSdkException(
        'The tune_reporting_sdk.config file is required: ' . $this->tune_reporting_config_file
      );
    }

    try {
      $this->tune_reporting_config
        = parse_ini_file($this->tune_reporting_config_file, 'TUNE_REPORTING');
    } catch (Exception $e) {
      throw new TuneSdkException("Error reading tune_reporting_sdk.config", 0, $e);
    }

    if (null == $this->tune_reporting_config) {
      throw new TuneSdkException("Reference to 'tune_reporting_config' is null.");
    }
  }

  /**
   * Reads configuration setting for provided key.
   *
   * @param string $key
   * @return string SDK Configuration value.
   *
   * @throws Exception
   */
  public function getConfigValue($key)
  {
    if (null == $this->tune_reporting_config) {
      throw new Exception("Reference to 'tune_reporting_config' is null.");
    }
    if (is_null($key) || (is_string($key) === false) || (trim($key) === '')) {
      throw new Exception("Parameter 'key' is not defined: '{$key}");
    }
    return $this->tune_reporting_config["TUNE_REPORTING"][$key];
  }

  /**
   * Returns a singleton instance.
   *
   * @return SdkConfig
   * @throws InvalidArgumentException
   */
  public static function &getInstance($tune_reporting_config_file = null)
  {
    static $instance;

    if (!isset($instance)) {
      // tune_reporting_config_file
      if (!is_string($tune_reporting_config_file)
        || empty($tune_reporting_config_file)
      ) {
        throw new \InvalidArgumentException(
          "Parameter 'tune_reporting_config_file' is not defined."
        );
      }
      $c = __CLASS__;
      $instance = new $c($tune_reporting_config_file);
    } // if

    return $instance;
  } // getInstance

  /**
   * Get TUNE Reporting Authentication Key.
   *
   * @return string
   */
  public function getAuthKey()
  {
    if (!array_key_exists(
      "tune_reporting_auth_key_string",
      $this->tune_reporting_config["TUNE_REPORTING"]
    )) {
      return false;
    }

    return $this->tune_reporting_config["TUNE_REPORTING"]["tune_reporting_auth_key_string"];
  }

  /**
   * Get TUNE Reporting Authentication Type.
   *
   * @return string
   */
  public function getAuthType()
  {
    if (!array_key_exists(
      "tune_reporting_auth_type_string",
      $this->tune_reporting_config["TUNE_REPORTING"]
    )) {
      return false;
    }

    return $this->tune_reporting_config["TUNE_REPORTING"]["tune_reporting_auth_type_string"];
  }

  /**
   * Set TUNE Reporting API Key.
   *
   * @return string
   */
  public function setApiKey($api_key)
  {
    $this->tune_reporting_config["TUNE_REPORTING"]["tune_reporting_auth_key_string"]
      = $api_key;
    $this->tune_reporting_config["TUNE_REPORTING"]["tune_reporting_auth_type_string"]
      = 'api_key';
  }

  /**
   * Set TUNE Reporting Session Token.
   *
   * @return string
   */
  public function setSessionToken($session_token)
  {
    $this->tune_reporting_config["TUNE_REPORTING"]["tune_reporting_auth_key_string"]
      = $session_token;
    $this->tune_reporting_config["TUNE_REPORTING"]["tune_reporting_auth_type_string"]
      = 'session_token';
  }

  /**
   * Get boolean flag to validate fields from SDK Configuration File.
   *
   * @return boolean
   */
  public function getValidateFields()
  {
    if (!array_key_exists(
      "tune_reporting_validate_fields_boolean",
      $this->tune_reporting_config["TUNE_REPORTING"]
    )) {
      return false;
    }

    $validate_fields = $this->tune_reporting_config["TUNE_REPORTING"]["tune_reporting_validate_fields_boolean"];

    return ("true" == $validate_fields || "1" == $validate_fields);
  }

  /**
   * Set boolean flag to validate fields from SDK Configuration File.
   *
   * @param boolean
   */
  public function setValidateFields($validate_fields)
  {
    $this->tune_reporting_config["TUNE_REPORTING"]["tune_reporting_validate_fields_boolean"]
      = $validate_fields;
  }

  /**
   * Get number of seconds to sleep between status requests from SDK Configuration File.
   * Default: 10 Seconds.
   *
   * @return integer
   */
  public function getStatusSleep()
  {
    if (!array_key_exists(
      "tune_reporting_export_status_sleep_seconds",
      $this->tune_reporting_config["TUNE_REPORTING"]
    )) {
      return 10;
    }

    $status_sleep = $this->tune_reporting_config["TUNE_REPORTING"]["tune_reporting_export_status_sleep_seconds"];

    return intval($status_sleep);
  }

  /**
   * Get number of seconds to timeout status requests from SDK Configuration File.
   * Default: 600 Seconds.
   *
   * @return integer
   */
  public function getStatusTimeout()
  {
    if (!array_key_exists(
      "tune_reporting_export_status_timeout_seconds",
      $this->tune_reporting_config["TUNE_REPORTING"]
    )) {
      return 240;
    }

    $status_sleep = $this->tune_reporting_config["TUNE_REPORTING"]["tune_reporting_export_status_timeout_seconds"];

    return intval($status_sleep);
  }
}
