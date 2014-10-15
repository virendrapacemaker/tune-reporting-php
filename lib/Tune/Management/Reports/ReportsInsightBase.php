<?php
/**
 * ReportsInsightBase.php
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

use Tune\Common\TuneSdkException;
use Tune\Common\TuneServiceException;
use Tune\Management\Endpoints\TuneManagementBase;

abstract class ReportsInsightBase extends ReportsBase
{
    /**
     * @var array
     */
    protected static $cohort_intervals
        = array(
            "year_day",
            "year_week",
            "year_month",
            "year"
        );

    /**
     * @var array
     */
    protected static $cohort_types
        = array(
            "click",
            "install"
        );

    /**
     * @var array
     */
    protected static $aggregation_types
        = array(
            "incremental",
            "cumulative"
        );

    /**
     * Constructor
     *
     * @param string $controller                Tune Management API endpoint name.
     * @param string $api_key                   Tune MobileAppTracking API Key.
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
        parent::__construct(
            $controller,
            $api_key,
            $filter_debug_mode,
            $filter_test_profile_id,
            $validate
        );
    }

    /**
     * Counts all existing records that match filter criteria
     * and returns an array of found model data.
     *
     * @param string $start_date        YYYY-MM-DD HH:MM:SS
     * @param string $end_date          YYYY-MM-DD HH:MM:SS
     * @param string $cohort_type       Cohort types: click, install
     * @param string $group             Group results using this endpoint's fields.
     * @param string $cohort_interval          Cohort intervals: year_day, year_week, year_month, year
     * @param string $filter            Apply constraints based upon values associated with
     *                                  this endpoint's fields.
     * @param string $response_timezone Setting expected timezone for results,
     *                                  default is set in account.
     *
     * @return object
     */
    public function count(
        $start_date,
        $end_date,
        $cohort_type,
        $group,
        $cohort_interval = null,
        $filter = null,
        $response_timezone = null
    ) {
        TuneManagementBase::validateDateTime('start_date', $start_date);
        TuneManagementBase::validateDateTime('end_date', $end_date);

        if (!is_string($cohort_type) || empty($cohort_type) || !in_array($cohort_type, self::$cohort_types)) {
            throw new \InvalidArgumentException("Parameter 'cohort_type' is invalid: '{$cohort_type}'.");
        }
        if (is_string($cohort_interval) && !in_array($cohort_interval, self::$cohort_intervals)) {
            throw new \InvalidArgumentException("Parameter 'interval' is invalid: '{$cohort_interval}'.");
        }

        $group = $this->validateGroup($group);
        $filter = $this->validateFilter($filter);

        return parent::callRecords(
            $action = "count",
            $query_string_dict = array (
                'start_date' => $start_date,
                'end_date' => $end_date,
                'cohort_type' => $cohort_type,
                'group' => $group,
                'interval' => $cohort_interval,
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
     * @param string $cohort_type       Cohort types: click, install
     * @param string $aggregation_type  Aggregation types: cumulative, incremental
     * @param string $group             Group results using this endpoint's fields.
     * @param string $cohort_interval          Cohort intervals: year_day, year_week, year_month, year
     * @param string $filter            Apply constraints based upon values associated with
     *                                  this endpoint's fields.
     * @param string $fields            Present results using these endpoint's fields.
     * @param int    $limit             Limit number of results, default 10, 0 shows all
     * @param int    $page              Pagination, default 1.
     * @param string $sort              Sort results using this endpoint's fields. Directions: DESC, ASC
     * @param string $format
     * @param string $response_timezone Setting expected timezone for results,
     *                                  default is set in account.
     *
     * @return object
     */
    public function find(
        $start_date,
        $end_date,
        $cohort_type,
        $aggregation_type,
        $group,
        $cohort_interval = null,
        $filter = null,
        $fields = null,
        $limit = null,
        $page = null,
        $sort = null,
        $format = null,
        $response_timezone = null
    ) {
        TuneManagementBase::validateDateTime('start_date', $start_date);
        TuneManagementBase::validateDateTime('end_date', $end_date);

        if (!is_string($cohort_type) || empty($cohort_type) || !in_array($cohort_type, self::$cohort_types)) {
            throw new \InvalidArgumentException("Parameter 'cohort_type' is invalid: '{$cohort_type}'.");
        }
        if (is_string($cohort_interval) && !in_array($cohort_interval, self::$cohort_intervals)) {
            throw new \InvalidArgumentException("Parameter 'interval' is invalid: '{$cohort_interval}'.");
        }
        if (!is_string($aggregation_type)
            || empty($aggregation_type)
            || !in_array($aggregation_type, self::$aggregation_types)
        ) {
            throw new \InvalidArgumentException("Parameter 'aggregation_type' is invalid: '{aggregation_type}'.");
        }

        if (!is_null($fields)) {
            $fields = $this->validateFields($fields);
        }
        if (!is_null($filter)) {
            $filter = $this->validateFilter($filter);
        }
        if (!is_null($sort)) {
            $sort = $this->validateSort($sort);
        }
        if (!is_null($group)) {
            $group = $this->validateGroup($group);
        }

        if (!is_null($cohort_interval)) {
            if (!is_string($cohort_interval)
                || empty($cohort_interval)
                || !in_array($cohort_interval, self::$cohort_intervals)
            ) {
                throw new \InvalidArgumentException("Parameter 'interval' is invalid: '{$cohort_interval}'.");
            }
        }

        return parent::callRecords(
            $action = "find",
            $query_string_dict = array (
                'start_date' => $start_date,
                'end_date' => $end_date,
                'cohort_type' => $cohort_type,
                'aggregation_type' => $aggregation_type,
                'group' => $group,
                'interval' => $cohort_interval,
                'filter' => $filter,
                'fields' => $fields,
                'limit' => $limit,
                'page' => $page,
                'sort' => $sort,
                'format' => $format,
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
     * @param string $cohort_type       Cohort types: click, install
     * @param string $aggregation_type  Aggregation types: cumulative, incremental
     * @param string $group             Group results using this endpoint's fields.
     * @param string $cohort_interval   Cohort intervals: year_day, year_week, year_month, year
     * @param string $fields            Present results using these endpoint's fields.
     * @param string $filter            Apply constraints based upon values associated with
     *                                  this endpoint's fields.
     * @param string $response_timezone Setting expected timezone for results,
     *                                  default is set in account.
     *
     * @return object
     * @throws \InvalidArgumentException
     */
    public function export(
        $start_date,
        $end_date,
        $cohort_type,
        $aggregation_type,
        $group,
        $fields,
        $cohort_interval = null,
        $filter = null,
        $response_timezone = null
    ) {
        TuneManagementBase::validateDateTime('start_date', $start_date);
        TuneManagementBase::validateDateTime('end_date', $end_date);

        if (!is_string($cohort_type) || empty($cohort_type) || !in_array($cohort_type, self::$cohort_types)) {
            throw new \InvalidArgumentException("Parameter 'cohort_type' is invalid: '{$cohort_type}'.");
        }
        if (!is_string($aggregation_type)
            || empty($aggregation_type)
            || !in_array($aggregation_type, self::$aggregation_types)
        ) {
            throw new \InvalidArgumentException("Parameter 'aggregation_type' is invalid: '{aggregation_type}'.");
        }

        if (!is_null($fields)) {
            $fields = $this->validateFields($fields);
        }
        if (!is_null($filter)) {
            $filter = $this->validateFilter($filter);
        }
        if (!is_null($group)) {
            $group = $this->validateGroup($group);
        }

        if (!is_null($cohort_interval)) {
            if (!is_string($cohort_interval) || empty($cohort_interval) || !in_array($cohort_interval, self::$cohort_intervals)) {
                throw new \InvalidArgumentException("Parameter 'interval' is invalid: '{$cohort_interval}'.");
            }
        }

        return parent::callRecords(
            $action = "export",
            $query_string_dict = array (
                'start_date' => $start_date,
                'end_date' => $end_date,
                'cohort_type' => $cohort_type,
                'aggregation_type' => $aggregation_type,
                'group' => $group,
                'interval' => $cohort_interval,
                'filter' => $filter,
                'fields' => $fields,
                'response_timezone' => $response_timezone
            )
        );
    }

    /**
     * Query status of insight reports. Upon completion will
     * return url to download requested report.
     *
     * @param string $job_id    Provided Job Identifier to reference requested report on export queue.
     */
    public function status(
        $job_id
    ) {
        if (!is_string($job_id) || empty($job_id)) {
            throw new \InvalidArgumentException("Parameter 'job_id' is not defined.");
        }

        return parent::call(
            $action = "status",
            $query_string_dict = array (
                'job_id' => $job_id
            )
        );
    }

    /**
     * Helper function for fetching report upon completion.
     * Starts worker thread for polling export queue.
     *
     * @param string $mod_export_class      Requesting report class for this export.
     * @param string $job_id                Provided Job Identifier to reference requested report on export queue.
     * @param string $report_format         Expected content format of exported report.
     * @param bool   $verbose               Debug purposes only to view progress of job on export queue.
     * @param int    $sleep                 Polling delay between querying job status on export queue.
     *
     * @return null
     */
    protected function fetchRecordsInsight(
        $mod_export_class,
        $job_id,
        $report_format = "csv",
        $verbose = false,
        $sleep = 10
    ) {
        return parent::fetchRecords(
            $mod_export_class,
            $mod_export_function = "status",
            $job_id,
            $report_format,
            $verbose,
            $sleep
        );
    }

    /**
     * Helper function for parsing export status response to gather report url.
     *
     * @param Response $response
     *
     * @return mixed
     * @throws \InvalidArgumentException
     * @throws \Tune\Common\TuneServiceException
     */
    public static function parseResponseUrl(
        $response
    ) {
        if (is_null($response)) {
            throw new \InvalidArgumentException("Parameter 'response' is not defined.");
        }

        if (is_null($response->getData())) {
            throw new TuneServiceException("Report request failed to get export data.");
        }

        if (!array_key_exists("url", $response->getData())) {
            throw new TuneSdkException("Export data does not contain report url for download.");
        }

        $report_url = $response->getData()["url"];

        return $report_url;
    }
}
