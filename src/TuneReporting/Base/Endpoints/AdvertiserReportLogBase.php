<?php
/**
 * AdvertiserReportLogBase.php
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

use TuneReporting\Base\Endpoints\AdvertiserReportBase;
use TuneReporting\Helpers\TuneSdkException;
use TuneReporting\Helpers\TuneServiceException;

/**
 * Base class intended for gathering from Advertiser reporting logs.
 */
abstract class AdvertiserReportLogBase extends AdvertiserReportBase
{
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
   * @param string $start_date    YYYY-MM-DD HH:MM:SS
   * @param string $end_date      YYYY-MM-DD HH:MM:SS
   * @param string $filter      Filter the results and apply conditions
   *                  that must be met for records to be included in data.
   * @param string $response_timezone Setting expected time for data
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

    /* Optional parameters */
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
   * <dt>fields</dt><dd>Present results using these endpoint's fields.</dd>
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
   * @return object
   */
  public function find(
    $map_params
  ) {
    $map_query_string = array();
    /* Required parameters */
    $map_query_string = self::validateDateTime($map_params, 'start_date', $map_query_string);
    $map_query_string = self::validateDateTime($map_params, 'end_date', $map_query_string);

    if (array_key_exists('filter', $map_params) && !is_null($map_params['filter'])) {
      $map_query_string = $this->validateFilter($map_params, $map_query_string);
    }
    if (array_key_exists('fields', $map_params) && !is_null($map_params['fields'])) {
      $map_query_string = $this->validateFields($map_params, $map_query_string);
    }
    if (array_key_exists('sort', $map_params) && !is_null($map_params['sort'])) {
      $map_query_string = $this->validateSort($map_params, $map_query_string);
    }

    if (array_key_exists('limit', $map_params) && !is_null($map_params['limit'])) {
      $map_query_string = self::validateLimit($map_params, $map_query_string);
    }
    else {
      $map_query_string['limit'] = 0;
    }

    if (array_key_exists('page', $map_params) && !is_null($map_params['page'])) {
      $map_query_string = self::validatePage($map_params, $map_query_string);
    }
    else {
      $map_query_string['page'] = 0;
    }

    if (array_key_exists('response_timezone', $map_params)) {
      $map_query_string = self::validateResponseTimezone($map_params, $map_query_string);
    }

    return parent::call(
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
   * @param dict $map_params    Mapping of: <p><dl>
   * <dt>start_date</dt><dd>YYYY-MM-DD HH:MM:SS</dd>
   * <dt>end_date</dt><dd>YYYY-MM-DD HH:MM:SS</dd>
   * <dt>fields</dt><dd>Present results using these endpoint's fields.</dd>
   * <dt>filter</dt><dd>Apply constraints based upon values associated with
   *                    this endpoint's fields.</dd>
   * <dt>format</dt><dd>Export format choices: csv, json</dd>
   * <dt>response_timezone</dt><dd>Setting expected timezone for results,
   *                          default is set in account.</dd>
   * </dl><p>
   *
   * @return object @see TuneServiceResponse
   */
  public function export(
    $map_params
  ) {
    $map_query_string = array();
    /* Required parameters */
    $map_query_string = self::validateDateTime($map_params, 'start_date', $map_query_string);
    $map_query_string = self::validateDateTime($map_params, 'end_date', $map_query_string);

    if (array_key_exists('filter', $map_params) && !is_null($map_params['filter'])) {
      $map_query_string = $this->validateFilter($map_params, $map_query_string);
    }

    if (!array_key_exists('fields', $map_params) || is_null($map_params['fields'])) {
      $map_params['fields'] = $this->getFields(self::TUNE_FIELDS_DEFAULT);
    }
    if (array_key_exists('fields', $map_params)) {
      $map_query_string = $this->validateFields($map_params, $map_query_string);
    }

    if (array_key_exists('format', $map_params)) {
      $map_query_string = $this->validateFormat($map_params, $map_query_string);
    }
    else {
      $map_query_string['format'] = 'csv';
    }

    if (array_key_exists('response_timezone', $map_params)) {
      $map_query_string = self::validateResponseTimezone($map_params, $map_query_string);
    }

    return parent::callRecords(
      $action = "find_export_queue",
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

    $client = new TuneServiceClient(
      "export",
      "download",
      $this->auth_key,
      $this->auth_type,
      $map_query_string = array (
        'job_id' => $job_id
      )
    );

    $client->call();

    return $client->getResponse();
  }

  /**
   * Helper function for fetching report upon completion.
   *
   * @param string $job_id    Job identifier assigned for report export.
   * @param bool   $verbose     For debug purposes to monitor job export completion status.
   * @param int  $sleep     Polling delay for checking job completion status.
   *
   * @return null
   */
  public function fetch(
    $job_id,
    $verbose = false,
    $sleep = null
  ) {
    if (!is_string($job_id) || empty($job_id)) {
      throw new \InvalidArgumentException(
        "Parameter 'job_id' is not defined."
      );
    }

    return parent::fetchRecords(
      $export_controller = "export",
      $export_action = "download",
      $job_id,
      $verbose,
      $sleep
    );
  }
}
