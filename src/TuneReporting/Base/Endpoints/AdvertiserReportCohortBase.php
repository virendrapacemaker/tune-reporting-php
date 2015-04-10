<?php
/**
 * AdvertiserReportCohortBase.php
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
 * @author    Jeff Tanner <jefft@tune.com>
 * @copyright 2015 TUNE, Inc. (http://www.tune.com)
 * @package   tune_reporting_base_endpoints
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version   $Date: 2015-04-09 17:36:25 $
 * @link      https://developers.mobileapptracking.com/tune-reporting-sdks @endlink
 *
 */

namespace TuneReporting\Base\Endpoints;

use TuneReporting\Helpers\TuneSdkException;
use TuneReporting\Helpers\TuneServiceException;
use TuneReporting\Base\Endpoints\AdvertiserReportBase;

/**
 * Base class intended for gathering from Advertiser reporting insights.
 *
 * @see AdvertiserReportCohortValue
 * @see AdvertiserReportCohortRetention
 */
abstract class AdvertiserReportCohortBase extends AdvertiserReportBase
{
  /**
   * @var array Available choices for Cohort intervals.
   */
  private static $cohort_intervals
    = array(
      "year_day",
      "year_week",
      "year_month",
      "year"
    );

  /**
   * @var array Available choices for Cohort types.
   */
  private static $cohort_types
    = array(
      "click",
      "install"
    );

  /**
   * @var array Available choices for Aggregation types.
   */
  private static $aggregation_types
    = array(
      "incremental",
      "cumulative"
    );

  /**
   * Constructor
   *
   * @param string $controller        TUNE Reporting API endpoint name.
   * @param bool   $filter_debug_mode     Remove debug mode information from results.
   * @param bool   $filter_test_profile_id  Remove test profile information from results.
   */
  public function __construct(
    $controller,
    $filter_debug_mode,
    $filter_test_profile_id
  ) {
    parent::__construct(
      $controller,
      $filter_debug_mode,
      $filter_test_profile_id
    );
  }

  /**
   * Counts all existing records that match filter criteria
   * and returns an array of found model data.
   *
   * @param mapParams    Mapping of: <p><dl>
   * <dt>start_date</dt><dd>YYYY-MM-DD HH:MM:SS</dd>
   * <dt>end_date</dt><dd>YYYY-MM-DD HH:MM:SS</dd>
   * <dt>cohort_type</dt><dd>Cohort types: click, install</dd>
   * <dt>cohort_interval</dt><dd>Cohort intervals:
   *                    year_day, year_week, year_month, year</dd>
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
   * Query status of insight reports. Upon completion will
   * return url to download requested report.
   *
   * @param string $job_id  Provided Job Identifier to reference
   *              requested report on export queue.
   */
  public function status(
    $job_id
  ) {
    if (!is_string($job_id) || empty($job_id)) {
      throw new \InvalidArgumentException(
        "Parameter 'job_id' is not defined."
      );
    }

    return parent::call(
      $action = "status",
      $map_query_string = array (
        'job_id' => $job_id
      )
    );
  }

  /**
   * Helper function for parsing export status response to gather report job_id.
   *
   * @param $response
   *
   * @return string Job identifier.
   *
   * @throws InvalidArgumentException
   * @throws TuneServiceException
   */
  public static function parseResponseReportJobId(
    $response
  ) {
    if (is_null($response)) {
      throw new \InvalidArgumentException("Parameter 'response' is not defined.");
    }

    $data = $response->getData();
    if (is_null($data)) {
      throw new TuneServiceException(
        "Report request failed to get export data, response: " . print_r($response, true)
      );
    }
    if (!array_key_exists("job_id", $data)) {
      throw new TuneSdkException(
        "Export data does not contain report 'job_id' for download: " . print_r($data, true) . PHP_EOL
      );
    }

    $job_id = $data["job_id"];

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
   * @param TuneServiceResponse $response
   *
   * @return string Report Download URL
   *
   * @throws InvalidArgumentException
   * @throws TuneSdkException
   * @throws TuneServiceException
   */
  public static function parseResponseReportUrl(
    $response
  ) {
    if (is_null($response)) {
      throw new \InvalidArgumentException(
        "Parameter 'response' is not defined."
      );
    }
    $data = $response->getData();
    if (is_null($data)) {
      throw new TuneServiceException(
        "Report export response does not contain data."
      );
    }

    if (!array_key_exists("url", $data)) {
      throw new TuneSdkException(
        "Report export response does not contain report 'url' for download: " . print_r($data, true) . PHP_EOL
      );
    }

    $report_url = $data["url"];

    return $report_url;
  }

  /**
   * Validate is allowed cohort intervals.
   *
   * @param $cohort_interval
   *
   * @returns boolean
   *
   * @throws InvalidArgumentException
   */
  protected static function validateCohortInterval($map_params, $map_query_string)
  {
    if (!array_key_exists('cohort_interval', $map_params)
    ) {
      throw new \InvalidArgumentException("Parameter 'cohort_interval' is not defined.");
    }

    $cohort_interval = $map_params['cohort_interval'];

    if (is_null($cohort_interval)
      || !is_string($cohort_interval)
      || empty($cohort_interval)
    ) {
      throw new \InvalidArgumentException("Parameter 'cohort_interval' is not defined.");
    }

    if (!in_array($cohort_interval, self::$cohort_intervals)
    ) {
      throw new \InvalidArgumentException("Parameter 'cohort_interval' is invalid: '{$cohort_interval}'.");
    }

    $map_query_string['interval'] = $cohort_interval;
    return $map_query_string;
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

  /**
   * @param $aggregation_type
   *
   * @throws InvalidArgumentException
   */
  protected static function validateAggregationType($map_params, $map_query_string)
  {
    if (!array_key_exists('aggregation_type', $map_params)
    ) {
      throw new \InvalidArgumentException("Parameter 'aggregation_type' is not defined.");
    }

    $aggregation_type = $map_params['aggregation_type'];

    if (is_null($aggregation_type)
      || !is_string($aggregation_type)
      || empty($aggregation_type)
    ) {
      throw new \InvalidArgumentException("Parameter 'aggregation_type' is not defined.");
    }

    if (!in_array($aggregation_type, self::$aggregation_types)
    ) {
      throw new \InvalidArgumentException("Parameter 'aggregation_type' is invalid: '{$aggregation_type}'.");
    }

    $map_query_string['aggregation_type'] = $aggregation_type;
    return $map_query_string;
  }
}
