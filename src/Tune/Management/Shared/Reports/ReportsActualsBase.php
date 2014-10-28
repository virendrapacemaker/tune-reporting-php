<?php
/**
 * ReportsActualsBase.php
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
 * @package   Tune_API_PHP
 * @author    Jeff Tanner <jefft@tune.com>
 * @copyright 2014 Tune (http://www.tune.com)
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version   0.9.8
 * @link      https://developers.mobileapptracking.com Tune Developer Community @endlink
 *
 */

namespace Tune\Management\Shared\Reports;

use Tune\Management\Shared\Service\TuneManagementBase;
use Tune\Management\Api\Export;

/**
 * Base class intended for gathering from Advertiser Stats actuals.
 *
 * @package Tune\Management\Shared\Reports
 */
abstract class ReportsActualsBase extends ReportsBase
{
    /**
     * @var array
     */
    protected static $timestamps
        = array(
            "hour",
            "datehour",
            "date",
            "week",
            "month"
        );

    /**
     * Constructor
     *
     * @param string $controller                Tune Management API endpoint name.
     * @param string $api_key                   Tune MobileAppTracking API Key.
     * @param bool   $filter_debug_mode         Remove debug mode information from results.
     * @param bool   $filter_test_profile_id    Remove test profile information from results.
     * @param bool   $validate_fields                  Validate fields used by actions' parameters.
     */
    public function __construct(
        $controller,
        $api_key,
        $filter_debug_mode,
        $filter_test_profile_id,
        $validate_fields = false
    ) {
        parent::__construct(
            $controller,
            $api_key,
            $filter_debug_mode,
            $filter_test_profile_id,
            $validate_fields
        );
    }

    /**
     * Counts all existing records that match filter criteria
     * and returns an array of found model data.
     *
     * @param string $start_date        YYYY-MM-DD HH:MM:SS
     * @param string $end_date          YYYY-MM-DD HH:MM:SS
     * @param string $group             Group by one of more field names
     * @param string $filter            Filter the results and apply conditions
     *                                  that must be met for records to be
     *                                  included in data.
     * @param string $response_timezone Setting expected time for data
     */
    public function count(
        $start_date = null,
        $end_date = null,
        $group = null,
        $filter = null,
        $response_timezone = null
    ) {
        if (!is_null($start_date)) {
            TuneManagementBase::validateDateTime('start_date', $start_date);
        }
        if (!is_null($end_date)) {
            TuneManagementBase::validateDateTime('end_date', $end_date);
        }
        if (!is_null($group)) {
            $group = $this->validateGroup($group);
        }
        if (!is_null($filter)) {
            $filter = $this->validateFilter($filter);
        }

        return parent::callRecords(
            $action = "count",
            $query_string_dict = array (
                'start_date' => $start_date,
                'end_date' => $end_date,
                'group' => $group,
                'filter' => $filter,
                'response_timezone' => $response_timezone
            )
        );
    }

    /**
     * Finds all existing records that match filter criteria
     * and returns an array of found model data.
     *
     * @param string $start_date        YYYY-MM-DD HH:MM:SS
     * @param string $end_date          YYYY-MM-DD HH:MM:SS
     * @param string $filter            Filter the results and apply conditions that
     *                                  must be met for records to be included in data.
     * @param string $fields            No value returns default fields, "*" returns all
     *                                  available fields, or provide specific fields.
     * @param integer $limit            Limit number of results, default 10, 0 shows all.
     * @param integer $page             Pagination, default 1.
     * @param array $sort               Sort by field name, ASC (default) or DESC
     * @param string $timestamp         Set to breakdown stats by timestamp choices: hour, datehour, date, week, month.
     * @param string $response_timezone Setting expected timezone for data. Default is set by account.
     *
     * @return object
     */
    public function find(
        $start_date = null,
        $end_date = null,
        $group = null,
        $filter = null,
        $fields = null,
        $limit = null,
        $page = null,
        $sort = null,
        $timestamp = null,
        $response_timezone = null
    ) {
        if (!is_null($start_date)) {
            TuneManagementBase::validateDateTime('start_date', $start_date);
        }
        if (!is_null($end_date)) {
            TuneManagementBase::validateDateTime('end_date', $end_date);
        }
        if (!is_null($group)) {
            $group = $this->validateGroup($group);
        }
        if (!is_null($filter)) {
            $filter = $this->validateFilter($filter);
        }
        if (!is_null($sort)) {
            $sort = $this->validateSort($sort);
        }
        if (!is_null($fields)) {
            $fields = $this->validateFields($fields);
        }

        // timestamp
        if (is_string($timestamp) && !in_array($timestamp, self::$timestamps)) {
            throw new \InvalidArgumentException("Parameter 'timestamp' is invalid: '{$timestamp}'.");
        }

        return parent::callRecords(
            $action = "find",
            $query_string_dict = array (
                'start_date' => $start_date,
                'end_date' => $end_date,
                'group' => $group,
                'filter' => $filter,
                'fields' => $fields,
                'limit' => $limit,
                'page' => $page,
                'sort' => $sort,
                'timestamp' => $timestamp,
                'response_timezone' => $response_timezone
            )
        );
    }

    /**
     * Places a job into a queue to generate a report that will contain
     * records that match provided filter criteria, and it returns a job
     * identifier to be provided to action /export/download.json to download
     * completed report.
     *
     * @param string $start_date        YYYY-MM-DD HH:MM:SS
     * @param string $end_date          YYYY-MM-DD HH:MM:SS
     * @param string $filter            Filter the results and apply conditions that
     *                                  must be met for records to be included in data.
     * @param string $fields            No value returns default fields, "*" returns all
     *                                  available fields, or provide specific fields.
     * @param string $timestamp         Set to breakdown stats by timestamp choices: hour, datehour, date, week, month.
     * @param string $format            Export format for downloaded report: json, csv.
     * @param string $response_timezone Setting expected timezone for data. Default is set by account.
     *
     * @return object
     */
    public function export(
        $start_date = null,
        $end_date = null,
        $group = null,
        $filter = null,
        $fields = null,
        $timestamp = null,
        $format = null,
        $response_timezone = null
    ) {
        if (!is_null($start_date)) {
            TuneManagementBase::validateDateTime('start_date', $start_date);
        }
        if (!is_null($end_date)) {
            TuneManagementBase::validateDateTime('end_date', $end_date);
        }
        if (!is_null($group)) {
            $group = $this->validateGroup($group);
        }
        if (!is_null($filter)) {
            $filter = $this->validateFilter($filter);
        }
        if (!is_null($fields)) {
            $fields = $this->validateFields($fields);
        }

        // timestamp
        if (is_string($timestamp) && !in_array($timestamp, self::$timestamps)) {
            throw new \InvalidArgumentException("Parameter 'timestamp' is invalid: '{$timestamp}'.");
        }

        if (!in_array($format, self::$report_export_formats)) {
            throw new \InvalidArgumentException("Parameter 'format' is invalid: '{$format}'.");
        }

        if (("csv" == $format) && (is_null($fields) || empty($fields))) {
            throw new \InvalidArgumentException("Parameter 'fields' needs to be defined if report format is 'csv'.");
        }

        return parent::callRecords(
            $action = "find_export_queue",
            $query_string_dict = array (
                'start_date' => $start_date,
                'end_date' => $end_date,
                'group' => $group,
                'filter' => $filter,
                'fields' => $fields,
                'timestamp' => $timestamp,
                'format' => $format,
                'response_timezone' => $response_timezone
            )
        );
    }

    /**
     * Query status of insight reports. Upon completion will
     * return url to download requested report.
     *
     * @param string $job_id    Provided Job Identifier to reference
     *                          requested report on export queue.
     */
    public function status(
        $job_id
    ) {
        if (!is_string($job_id) || empty($job_id)) {
            throw new \InvalidArgumentException(
                "Parameter 'job_id' is not defined."
            );
        }

        $export = new Export($this->getApiKey());
        return $export->download(
            $job_id
        );
    }

    /**
     * Helper function for fetching report upon completion.
     *
     * @param string $job_id        Job identifier assigned for report export.
     * @param bool   $verbose       For debug purposes to monitor job export completion status.
     * @param int    $sleep         Polling delay for checking job completion status.
     *
     * @return null
     */
    public function fetch(
        $job_id,
        $verbose = false,
        $sleep = 10
    ) {
        if (!is_string($job_id) || empty($job_id)) {
            throw new \InvalidArgumentException(
                "Parameter 'job_id' is not defined."
            );
        }

        $export = new Export($this->getApiKey());
        return $export->fetch(
            $job_id,
            $verbose,
            $sleep
        );
    }

    /**
     * Helper function for parsing export status response to gather report job_id.
     *
     * @param $response
     *
     * @return mixed
     * @throws \InvalidArgumentException
     * @throws \Tune\Shared\TuneServiceException
     */
    public static function parseResponseReportJobId(
        $response
    ) {
        if (is_null($response)) {
            throw new \InvalidArgumentException("Parameter 'response' is not defined.");
        }

        $data = $response->getData();
        if (is_null($data)) {
            throw new TuneServiceException("Report request failed to get export data.");
        }

        $job_id = $data;

        if (!is_string($job_id) || empty($job_id)) {
            throw new \Exception(
                "Failed to return job_id: " . print_r($response, true)
            );
        }

        return $job_id;
    }

    /**
     * Helper function for parsing export status response to gather report url.
     *
     * @param Response $response
     *
     * @return mixed
     * @throws \InvalidArgumentException
     * @throws \Tune\Shared\TuneServiceException
     */
    public static function parseResponseReportUrl(
        $response
    ) {
        return Export::parseResponseReportUrl($response);
    }
}
