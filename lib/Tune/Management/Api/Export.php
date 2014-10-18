<?php
/**
 * Export.php
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
 * @version   0.9.2
 * @link      https://developers.mobileapptracking.com Tune Developer Community @endlink
 *
 */

namespace Tune\Management\Api;

use Tune\Management\Shared\Reports\ReportsBase;

use Tune\Shared\TuneSdkException;
use Tune\Shared\TuneServiceException;

/**
 * Provides status of report export request, and upon completion provides
 * download url.
 *
 * @package Tune\Management\Api
 */
class Export extends ReportsBase
{
    /**
     * Constructor
     *
     * @param string $api_key MobileAppTracking API Key
     */
    public function __construct(
        $api_key
    ) {
        // api key
        if (!is_string($api_key) || empty($api_key)) {
            throw new \InvalidArgumentException("Parameter 'api_key' is not defined.");
        }

        parent::__construct(
            $controller = "export",
            $api_key,
            $filter_debug_mode = false,
            $filter_test_profile_id = false
        );
    }

    /**
     * Action 'download' for polling export queue for status information on request report to be exported.
     *
     * @param string $job_id Job identifier assigned for report export.
     *
     * @return object
     * @throws \InvalidArgumentException
     */
    public function download(
        $job_id
    ) {
        if (!is_string($job_id) || empty($job_id)) {
            throw new \InvalidArgumentException("Parameter 'job_id' is not defined.");
        }

        return parent::call(
            $action = "download",
            $query_string_dict = array (
                'job_id' => $job_id
            )
        );
    }

    /**
     * Helper function for fetching report upon completion.
     * Starts worker thread for polling export queue.
     *
     * @param string $job_id        Job identifier assigned for report export.
     * @param string $report_format Report export content format expectation: csv, json
     * @param bool   $verbose       For debug purposes to monitor job export completion status.
     * @param int    $sleep         Polling delay for checking job completion status.
     *
     * @return null
     */
    public function fetch(
        $job_id,
        $report_format,
        $verbose = false,
        $sleep = 10
    ) {
        if (!is_string($job_id) || empty($job_id)) {
            throw new \InvalidArgumentException("Parameter 'job_id' is not defined.");
        }

        return parent::fetchRecords(
            $mod_export_class       = __CLASS__,
            $mod_export_function    = "download",
            $job_id,
            $report_format,
            $verbose,
            $sleep
        );
    }

    /**
     * Helper function for parsing export status response to gather report url.
     *
     * @param $response
     *
     * @return mixed
     * @throws \InvalidArgumentException
     * @throws \Tune\Shared\TuneServiceException
     */
    public static function parseResponseUrl(
        $response
    ) {
        if (is_null($response)) {
            throw new \InvalidArgumentException("Parameter 'response' is not defined.");
        }

        $data = $response->getData();
        if (is_null($data)) {
            throw new TuneServiceException("Report request failed to get export data.");
        }
        if (!array_key_exists("data", $data)) {
            throw new TuneSdkException(
                "Export data does not contain report 'data' for download: " . print_r($data, true) . PHP_EOL
            );
        }
        if (!array_key_exists("data", $data)) {
            throw new TuneServiceException(
                "Export response does not contain 'data': " . print_r($data, true) . PHP_EOL
            );
        }
        if (is_null($data["data"])) {
            throw new TuneServiceException(
                "Export data response does not contain 'data': " . print_r($data, true) . PHP_EOL
            );
        }
        if (!array_key_exists("url", $data["data"])) {
            throw new TuneServiceException(
                "Export response 'data' does not contain 'url': " . print_r($data, true) . PHP_EOL
            );
        }

        $report_url = $data["data"]["url"];

        return $report_url;
    }
}
