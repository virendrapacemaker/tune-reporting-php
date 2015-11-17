<?php
/**
 * AdvertiserReportCohortRetention.php
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
 * @author    Jeff Tanner <jefft@tune.com>
 * @copyright 2015 TUNE, Inc. (http://www.tune.com)
 * @package   tune_reporting_api
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version   $Date: 2015-11-17 09:18:01 $
 * @link      https://developers.mobileapptracking.com/tune-reporting-sdks @endlink
 *
 */

namespace TuneReporting\Api;

use TuneReporting\Base\Endpoints\AdvertiserReportCohortBase;

/**
 * TUNE Reporting API controller 'advertiser/stats/retention'
 *
 * @example ExampleAdvertiserReportCohortRetention.php
 */
class AdvertiserReportCohortRetention extends AdvertiserReportCohortBase
{
    /**
     * @var array Available choices for Cohort types.
     */
    private static $cohort_types
        = array(
            "install"
        );

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(
            "advertiser/stats/retention/reduced",
            $filter_debug_mode = false,
            $filter_test_profile_id = false
        );

        /*
         * Fields recommended in suggested order.
         */
        $this->fields_recommended = array(
            "site_id"
           ,"site.name"
           ,"install_publisher_id"
           ,"install_publisher.name"
           ,"installs"
           ,"rolling_opens"
        );
    }

    /**
     * Counts all existing records that match filter criteria
     * and returns an array of found model data.
     *
     * @param mapParams    Mapping of: <p><dl>
     * <dt>start_date</dt><dd>YYYY-MM-DD HH:MM:SS</dd>
     * <dt>end_date</dt><dd>YYYY-MM-DD HH:MM:SS</dd>
     * <dt>cohort_type</dt><dd>Cohort types: install</dd>
     * <dt>cohort_interval</dt><dd>Cohort intervals:
     *                        year_day, year_week, year_month, year</dd>
     * <dt>aggregation_type</dt> <dd>Aggregation types:
     *                        cumulative, incremental.</dd>
     * <dt>group</dt><dd>Group results using this endpoint's fields.</dd>
     * <dt>filter</dt><dd>Apply constraints based upon values associated with
     *                    this endpoint's fields.</dd>
     * <dt>response_timezone</dt><dd>Setting expected timezone for results,
     *                          default is set in account.</dd>
     * </dl><p>
     *
     * @return object @see TuneServiceResponse
     */
    public function count(
        $map_params
    ) {
        $map_query_string = array();
        /* Required parameters */
        $map_query_string = self::validateDateTime($map_params, 'start_date', $map_query_string);
        $map_query_string = self::validateDateTime($map_params, 'end_date', $map_query_string);

        $map_query_string = self::validateCohortType($map_params, $map_query_string);
        $map_query_string = self::validateCohortInterval($map_params, $map_query_string);

        if (array_key_exists('aggregation_type', $map_params)) {
            $map_query_string = self::validateAggregationType($map_params, $map_query_string);
        }

        $map_query_string = $this->validateGroup($map_params, $map_query_string);

        if (array_key_exists('filter', $map_params) && !is_null($map_params['filter'])) {
            $map_query_string = $this->validateFilter($map_params, $map_query_string);
        }
        if (array_key_exists('response_timezone', $map_params)) {
            $map_query_string = self::validateResponseTimezone($map_params, $map_query_string);
        }

        return parent::callRecords(
            $action = "count",
            $map_query_string
        );
    }

    /**
     * Finds all existing records that match filter criteria
     * and returns an array of found model data.
     *
     * @param dict $map_params    Mapping of: <p><dl>
     * <dt>start_date</dt><dd>YYYY-MM-DD HH:MM:SS</dd>
     * <dt>end_date</dt><dd>YYYY-MM-DD HH:MM:SS</dd>
     * <dt>cohort_type</dt><dd>Cohort types: click, install</dd>
     * <dt>cohort_interval</dt><dd>Cohort intervals:
     *                        year_day, year_week, year_month, year</dd>
     * <dt>aggregation_type</dt> <dd>Aggregation types:
     *                        cumulative, incremental.</dd>
     * <dt>fields</dt><dd>Present results using these endpoint's fields.</dd>
     * <dt>group</dt><dd>Group results using this endpoint's fields.
     * <dt>filter</dt><dd>Apply constraints based upon values associated with
     *                    this endpoint's fields.</dd>
     * <dt>limit</dt><dd>Limit number of results, default 10, 0 shows all</dd>
     * <dt>page</dt><dd>Pagination, default 1.</dd>
     * <dt>sort</dt><dd>Sort results using this endpoint's fields.
     *                    Directions: DESC, ASC</dd>
     * <dt>response_timezone</dt><dd>Setting expected timezone for results,
     *                          default is set in account.</dd>
     * </dl><p>
     *
     * @return object @see TuneServiceResponse
     */
    public function find (
        $map_params
    ) {
        $map_query_string = array();
        /* Required parameters */
        $map_query_string = self::validateDateTime($map_params, 'start_date', $map_query_string);
        $map_query_string = self::validateDateTime($map_params, 'end_date', $map_query_string);

        $map_query_string = self::validateCohortType($map_params, $map_query_string);
        $map_query_string = self::validateCohortInterval($map_params, $map_query_string);

        if (array_key_exists('aggregation_type', $map_params)) {
            $map_query_string = self::validateAggregationType($map_params, $map_query_string);
        }

        $map_query_string = $this->validateGroup($map_params, $map_query_string);

        if (array_key_exists('fields', $map_params) && !is_null($map_params['fields'])) {
            $map_query_string = $this->validateFields($map_params, $map_query_string);
        }

        if (array_key_exists('filter', $map_params) && !is_null($map_params['filter'])) {
            $map_query_string = $this->validateFilter($map_params, $map_query_string);
        }

        if (array_key_exists('limit', $map_params) && !is_null($map_params['limit'])) {
            $map_query_string = self::validateLimit($map_params, $map_query_string);
        } else {
            $map_query_string['limit'] = 0;
        }

        if (array_key_exists('page', $map_params) && !is_null($map_params['page'])) {
            $map_query_string = self::validatePage($map_params, $map_query_string);
        } else {
            $map_query_string['page'] = 0;
        }

        if (array_key_exists('sort', $map_params) && !is_null($map_params['sort'])) {
            $map_query_string = $this->validateSort($map_params, $map_query_string);
        }

        if (array_key_exists('response_timezone', $map_params)) {
            $map_query_string = self::validateResponseTimezone($map_params, $map_query_string);
        }

        return parent::callRecords(
            $action = "find",
            $map_query_string
        );
    }

    /**
     * Places a job into a queue to generate a report that will contain
     * records that match provided filter criteria, and it returns a job
     * identifier to be provided to action /export/download.json to download
     * completed report.
     *
     * @param dict mapParams    Mapping of: <p><dl>
     * <dt>start_date</dt><dd>YYYY-MM-DD HH:MM:SS</dd>
     * <dt>end_date</dt><dd>YYYY-MM-DD HH:MM:SS</dd>
     * <dt>cohort_type</dt><dd>Cohort types: click, install</dd>
     * <dt>cohort_interval</dt><dd>Cohort intervals:
     *                        year_day, year_week, year_month, year</dd>
     * <dt>aggregation_type</dt> <dd>Aggregation types:
     *                        cumulative, incremental.</dd>
     * <dt>fields</dt><dd>Present results using this endpoint's fields.</dd>
     * <dt>group</dt><dd>Group results using this endpoint's fields.</dd>
     * <dt>filter</dt><dd>Apply constraints based upon values associated with
     *                    this endpoint's fields.</dd>
     * <dt>limit</dt><dd>Limit number of results, default 10, 0 shows all</dd>
     * <dt>page</dt><dd>Pagination, default 1.</dd>
     * <dt>sort</dt><dd>Sort results using this endpoint's fields.
     *                    Directions: DESC, ASC</dd>
     * <dt>response_timezone</dt><dd>Setting expected timezone for results,
     *                          default is set in account.</dd>
     * </dl><p>
     *
     * @return object @see TuneServiceResponse
     */
    public function export (
        $map_params
    ) {
        $map_query_string = array();
        /* Required parameters */
        $map_query_string = self::validateDateTime($map_params, 'start_date', $map_query_string);
        $map_query_string = self::validateDateTime($map_params, 'end_date', $map_query_string);

        $map_query_string = self::validateCohortType($map_params, $map_query_string);
        $map_query_string = self::validateCohortInterval($map_params, $map_query_string);

        if (array_key_exists('aggregation_type', $map_params)) {
            $map_query_string = self::validateAggregationType($map_params, $map_query_string);
        }

        $map_query_string = $this->validateGroup($map_params, $map_query_string);

        if (!array_key_exists('fields', $map_params) || is_null($map_params['fields'])) {
            $map_params['fields'] = $this->getFields(self::TUNE_FIELDS_DEFAULT);
        }
        if (array_key_exists('fields', $map_params)) {
            $map_query_string = $this->validateFields($map_params, $map_query_string);
        }

        if (array_key_exists('filter', $map_params) && !is_null($map_params['filter'])) {
            $map_query_string = $this->validateFilter($map_params, $map_query_string);
        }

        if (array_key_exists('format', $map_params)) {
            $map_query_string = $this->validateFormat($map_params, $map_query_string);
        } else {
            $map_query_string['format'] = 'csv';
        }

        if (array_key_exists('response_timezone', $map_params)) {
            $map_query_string = self::validateResponseTimezone($map_params, $map_query_string);
        }

        return parent::callRecords(
            $action = "export",
            $map_query_string
        );
    }

    /**
     * Fetch report when status is complete.
     *
     * @param    $job_id
     * @param bool   $verbose
     * @param int  $sleep
     *
     * @return null
     */
    public function fetch(
        $job_id,
        $verbose = false,
        $sleep = null
    ) {
        return parent::fetchRecords(
            $this->controller,
            "status",
            $job_id,
            $verbose,
            $sleep
        );
    }


    /**
     * @param $cohort_type
     *
     * @throws InvalidArgumentException
     */
    protected static function validateCohortType($map_params, $map_query_string)
    {
        if (!array_key_exists('cohort_type', $map_params)
        ) {
            throw new \InvalidArgumentException("Parameter 'cohort_type' is not defined.");
        }

        $cohort_type = $map_params['cohort_type'];

        if (is_null($cohort_type)
            || !is_string($cohort_type)
            || empty($cohort_type)
        ) {
            throw new \InvalidArgumentException("Parameter 'cohort_type' is not defined.");
        }

        if (!in_array($cohort_type, self::$cohort_types)
        ) {
            throw new \InvalidArgumentException("Parameter 'cohort_type' is invalid: '{$cohort_type}'.");
        }

        $map_query_string['cohort_type'] = $cohort_type;
        return $map_query_string;
    }
}
