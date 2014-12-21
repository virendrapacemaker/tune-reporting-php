<?php
/**
 * AdvertiserReportRetention.php
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
 * @package   tune_reporting_api
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version   $Date: 2014-12-21 09:06:23 $
 * @link      https://developers.mobileapptracking.com/tune-reporting-sdks @endlink
 *
 */

namespace TuneReporting\Api;

use TuneReporting\Base\Endpoints\AdvertiserReportCohortBase;

/**
 * TUNE Reporting API controller 'advertiser/stats/retention'
 *
 * @example ExampleAdvertiserReportRetention.php
 */
class AdvertiserReportRetention extends AdvertiserReportCohortBase
{
    /**
     * Constructor.
     */
    public function __construct(
    ) {
        parent::__construct(
            "advertiser/stats/retention",
            $filter_debug_mode = false,
            $filter_test_profile_id = true
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
            ,"opens"
        );
    }

    /**
     * Finds all existing records that match filter criteria
     * and returns an array of found model data.
     *
     * @param string $start_date        YYYY-MM-DD HH:MM:SS
     * @param string $end_date          YYYY-MM-DD HH:MM:SS
     * @param string $cohort_type       Cohort types: click, install
     * @param string $cohort_interval   Cohort intervals: year_day, year_week, year_month, year
     * @param string $fields            Present results using these endpoint's fields.
     * @param string $group             Group results using this endpoint's fields.
     * @param string $filter            Apply constraints based upon values associated with
     *                                  this endpoint's fields.
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
        $cohort_interval,
        $fields,
        $group,
        $filter = null,
        $limit = null,
        $page = null,
        $sort = null,
        $response_timezone = null
    ) {
        self::validateDateTime('start_date', $start_date);
        self::validateDateTime('end_date', $end_date);

        self::validateCohortType($cohort_type);
        self::validateCohortInterval($cohort_interval);

        if (is_null($group)
            || (is_array($group) && empty($group))
            || (is_string($group) && empty($group))
        ) {
            throw new \InvalidArgumentException(
                "Parameter 'group' is invalid: '{$group}'."
            );
        } else {
            $group = $this->validateGroup($group);
        }

        if (is_null($fields) ||
            (is_array($fields) && empty($fields)) ||
            (is_string($fields) && empty($fields))
        ) {
            $fields = self::fields(self::TUNE_FIELDS_DEFAULT);
        }

        if (is_null($fields) || empty($fields)) {
            $fields = self::fields(self::TUNE_FIELDS_DEFAULT);
        }
        if (!is_null($fields)) {
            $fields = $this->validateFields($fields);
        }

        if (!is_null($filter)) {
            $filter = $this->validateFilter($filter);
        }

        return parent::callRecords(
            $action = "find",
            $query_string_dict = array (
                'start_date' => $start_date,
                'end_date' => $end_date,
                'cohort_type' => $cohort_type,
                'interval' => $cohort_interval,
                'fields' => $fields,
                'group' => $group,
                'filter' => $filter,
                'limit' => $limit,
                'page' => $page,
                'sort' => $sort,
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
     * @param string $cohort_type       Cohort types: click, install.
     * @param string $cohort_interval   Cohort intervals: year_day, year_week, year_month, year.
     * @param string $fields            Present results using these endpoint's fields.
     * @param string $group             Group results using this endpoint's fields.
     * @param string $filter            Apply constraints based upon values associated with
     *                                  this endpoint's fields.
     * @param string $response_timezone Setting expected timezone for results,
     *                                  default is set in account.
     *
     * @return object
     * @throws InvalidArgumentException
     */
    public function export(
        $start_date,
        $end_date,
        $cohort_type,
        $cohort_interval,
        $fields,
        $group,
        $filter = null,
        $response_timezone = null
    ) {
        self::validateDateTime('start_date', $start_date);
        self::validateDateTime('end_date', $end_date);

        self::validateCohortType($cohort_type);
        self::validateCohortInterval($cohort_interval);

        if (is_null($group)
            || (is_array($group) && empty($group))
            || (is_string($group) && empty($group))
        ) {
            throw new \InvalidArgumentException(
                "Parameter 'group' is invalid: '{$group}'."
            );
        } else {
            $group = $this->validateGroup($group);
        }

        if (is_null($fields) || empty($fields)) {
            $fields = self::fields(self::TUNE_FIELDS_DEFAULT);
        }
        if (!is_null($fields)) {
            $fields = $this->validateFields($fields);
        }
        if (!is_null($filter)) {
            $filter = $this->validateFilter($filter);
        }

        return parent::callRecords(
            $action = "export",
            $query_string_dict = array (
                'start_date' => $start_date,
                'end_date' => $end_date,
                'cohort_type' => $cohort_type,
                'interval' => $cohort_interval,
                'fields' => $fields,
                'group' => $group,
                'filter' => $filter,
                'response_timezone' => $response_timezone
            )
        );
    }

    /**
     * Fetch report when status is complete.
     *
     * @param        $job_id
     * @param bool   $verbose
     * @param int    $sleep
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
}
