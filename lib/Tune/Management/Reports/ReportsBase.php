<?php
/**
 * ReportBase.php
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

namespace Tune\Management\Reports;

use Tune\Management\Endpoints\TuneManagementBase;
use Tune\Common\TuneSdkException;
use Tune\Common\TuneServiceException;

/**
 * Base class for handling all endpoints that pertain to reports.
 *
 * @package Tune\Management\Reports
 */
abstract class ReportsBase extends TuneManagementBase
{
    /**
     * Remove debug mode information from results.
     * @var bool
     */
    protected $filter_debug_mode = false;
    /**
     * Remove test profile information from results.
     * @var bool
     */
    protected $filter_test_profile_id = false;

    /**
     * Constructor
     *
     * @param string $controller                Tune Management API endpoint name.
     * @param string $api_key                   MobileAppTracking API Key.
     * @param bool   $filter_debug_mode         Remove debug mode information from results.
     * @param bool   $filter_test_profile_id    Remove test profile information from results.
     * @param bool   $validate                  Validate fields used by actions' parameters.
     */
    public function __construct(
        $controller,
        $api_key,
        $filter_debug_mode,
        $filter_test_profile_id,
        $validate = false
    ) {
        // controller
        if (!is_string($controller) || empty($controller)) {
            throw new \InvalidArgumentException("Parameter 'controller' is not defined.");
        }
        // api_key
        if (!is_string($api_key) || empty($api_key)) {
            throw new \InvalidArgumentException("Parameter 'api_key' is not defined.");
        }
        // filter_debug_mode
        if (!is_bool($validate)) {
            throw new \InvalidArgumentException("Parameter 'validate' is not defined as a bool.");
        }
        // filter_debug_mode
        if (!is_bool($filter_debug_mode)) {
            throw new \InvalidArgumentException("Parameter 'filter_debug_mode' is not defined as a bool.");
        }
        // filter_test_profile_id
        if (!is_bool($filter_test_profile_id)) {
            throw new \InvalidArgumentException("Parameter 'filter_test_profile_id' is not defined as a bool.");
        }

        $this->filter_debug_mode = $filter_debug_mode;
        $this->filter_test_profile_id = $filter_test_profile_id;

        parent::__construct($controller, $api_key, $validate);
    }

    /**
     * Prepare action with provided query string parameters, then call
     * Management API service.
     *
     * @param string    $action Endpoint action to be called.
     * @param dict      $query_string_dict Query string parameters for this action.
     *
     * @throws \InvalidArgumentException
     */
    protected function callRecords(
        $action,
        $query_string_dict
    ) {
        // action
        if (!is_string($action) || empty($action)) {
            throw new \InvalidArgumentException("Parameter 'action' is not defined.");
        }
        if (is_null($query_string_dict) && !is_array($query_string_dict)) {
            throw new \InvalidArgumentException("Parameter 'query_string_dict' is not defined as associative array.");
        }

        $sdk_filter = null;

        if ($this->filter_debug_mode or $this->filter_test_profile_id) {
            $sdk_filter = null;
            if ($this->filter_debug_mode) {
                $sdk_filter = "(debug_mode=0 OR debug_mode is NULL)";
            }

            if ($this->filter_test_profile_id) {
                if (!is_null($sdk_filter) && is_string($sdk_filter) && !empty($sdk_filter)) {
                    $sdk_filter .= " AND ";
                }

                $sdk_filter = "(test_profile_id=0 OR test_profile_id IS NULL)";
            }
        }

        if (!is_null($sdk_filter) && is_string($sdk_filter) && !empty($sdk_filter)) {
            if (array_key_exists('filter', $query_string_dict)) {
                if (!is_null($query_string_dict['filter'])
                    && is_string($query_string_dict['filter'])
                    && !empty($query_string_dict['filter'])) {
                    $query_string_dict['filter'] = "(" . $query_string_dict['filter'] . ") AND " . $sdk_filter;
                } else {
                    $query_string_dict['filter'] = $sdk_filter;
                }
            } else {
                $query_string_dict['filter'] = $sdk_filter;
            }
        }

        if (array_key_exists('filter', $query_string_dict)) {
            if (!is_null($query_string_dict['filter'])
                && is_string($query_string_dict['filter'])
                && !empty($query_string_dict['filter'])) {
                $query_string_dict['filter'] = "(" . $query_string_dict['filter'] . ")";
            }
        }

        return parent::call($action, $query_string_dict);
    }

    /**
     * Helper function for fetching report document given provided job identifier.
     *
     * Requesting for report url is not the same for all report endpoints.
     *
     * @param string    $mod_export_class           Report class.
     * @param string    $mod_export_function        Report function performing status request.
     * @param string    $job_id                     Job Identifier of report on queue.
     * @param string    $report_format              Requested document format: csv, json
     * @param bool      $verbose                    For debugging purposes only.
     * @param int       $sleep                      How long thread should sleep before
     *                                              next status request.
     *
     * @return object                       Report reader
     * @throws \InvalidArgumentException
     * @throws TuneServiceException
     */
    protected function fetchRecords(
        $mod_export_class,
        $mod_export_function,
        $job_id,
        $report_format,
        $verbose = false,
        $sleep = 60
    ) {
        if (!is_string($mod_export_class) || empty($mod_export_class)) {
            throw new \InvalidArgumentException("Parameter 'mod_export_class' is not defined.");
        }
        if (!is_string($mod_export_function) || empty($mod_export_function)) {
            throw new \InvalidArgumentException("Parameter 'mod_export_function' is not defined.");
        }
        if (!is_string($job_id) || empty($job_id)) {
            throw new \InvalidArgumentException("Parameter 'job_id' is not defined.");
        }
        if (!is_string($report_format) || empty($report_format)) {
            throw new \InvalidArgumentException("Parameter 'job_id' is not defined.");
        }
        if (!is_string(parent::getApiKey()) || empty(parent::getApiKey())) {
            throw new \InvalidArgumentException("Parameter 'api_key' is not defined.");
        }

        $export_worker = new ReportExportWorker(
            $mod_export_class,
            $mod_export_function,
            $this->api_key,
            $job_id,
            $verbose,
            $sleep
        );

        if ($verbose) {
            echo "Starting thread..." . PHP_EOL;
        }
        $export_worker->start();
        if ($verbose) {
            echo "Waiting..." . PHP_EOL;
        }
        // Calling context to wait for the referenced Thread to finish executing
        $success = $export_worker->join();

        if ($verbose && $success) {
            echo "Thread completed successfully." . PHP_EOL;
        }

        $response = $export_worker->getResponse();

        if (!$success) {
            throw new TuneSdkException("Thread failed to complete successfully: " + print_r($response, true) . PHP_EOL);
        }

        if (is_null($response)
            || $response->getHttpCode() != 200
            || $response->getData()["status"] == "fail"
        ) {
            throw new TuneServiceException("Report request failed: " + print_r($response, true) . PHP_EOL);
        }

        $report_url = $mod_export_class::parseResponseUrl($response);

        if (!is_string($report_url) || empty($report_url)) {
            throw new TuneSdkException("Failed to get report Url from response.");
        }

        if ($verbose) {
            echo "Completed report request: " + print_r($response, true) . PHP_EOL;
        }

        return $this->parseReportStatus($report_url, $report_format);
    }

    /**
     * Helper function for fetching report document given provided job identifier.
     *
     * Response is not the same for all report endpoints.
     *
     * @param string $report_url    Report url provided upon completing of export processing.
     * @param string $report_format Expected format of exported report.
     *
     * @return null
     * @throws TuneSdkException
     */
    public function parseReportStatus(
        $report_url,
        $report_format
    ) {
        $report_reader = null;
        try {
            if ($report_format == "csv") {
                $report_reader = new ReportReaderCSV(
                    $report_url
                );
            } elseif ($report_format == "json") {
                $report_reader = new ReportReaderJSON(
                    $report_url
                );
            }
        } catch (Exception $ex) {
            throw new TuneSdkException(
                "Failed to create reader provided by url {$report_url}: " + print_r($ex, true) . PHP_EOL
            );
        }

        return $report_reader;
    }
}
