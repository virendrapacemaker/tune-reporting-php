<?php
/**
 * SdkConfig.php
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
 * @package   tune_reporting_helpers
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version   $Date: 2014-12-18 04:47:37 $
 * @link      https://developers.mobileapptracking.com/tune-reporting-sdks @endlink
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
     *
     * SDK Config file path
     * @var string
     */
    private $_tune_reporting_config_file = null;
    /**
     *
     * Parsed SDK config file's contents
     * @var array
     */
    private $_tune_reporting_config = null;

    /**
     *
     * Constructs by reading the SDK configuration settings from tune_reporting_sdk.config.
     *
     * @throws TuneSdkException
     * @throws Exception
     *
     * @access private
     */
    private function __construct($tune_reporting_config_file)
    {
        $this->_tune_reporting_config_file = $tune_reporting_config_file;
        if (!file_exists($this->_tune_reporting_config_file)) {
            throw new TuneSdkException('The tune_reporting_sdk.config file is required: ' . $this->_tune_reporting_config_file );
        }

        try {
            $this->_tune_reporting_config = parse_ini_file($this->_tune_reporting_config_file, 'TANGOCARD');
        } catch (Exception $e) {
            throw new Exception("Error reading tune_reporting_sdk.config", 0, $e);
        }

        if (null == $this->_tune_reporting_config ) {
            throw new Exception( "Reference to '_tune_reporting_config' is null.");
        }
    }

    /**
     *
     * Reads configuration setting for provided key.
     *
     * @param string $key
     *
     * @throws Exception
     */
    public function getConfigValue($key)
    {
        if (null == $this->_tune_reporting_config ) {
            throw new Exception( "Reference to '_tune_reporting_config' is null.");
        }
        if (is_null($key) || (is_string($key) === false) || (trim($key) === '')) {
            throw new Exception( "Parameter 'key' is not defined: '{$key}");
        }
        return $this->_tune_reporting_config['TUNE_REPORTING'][$key];
    }

    /**
     *
     * Returns a singleton instance.
     *
     * @return \TangoCard\Sdk\Common\SdkConfig
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
}