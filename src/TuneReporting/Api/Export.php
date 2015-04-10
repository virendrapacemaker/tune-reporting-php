<?php
/**
 * Export.php
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
 * @version   $Date: 2015-04-08 17:44:36 $
 * @link      https://developers.mobileapptracking.com/tune-reporting-sdks @endlink
 *
 */

namespace TuneReporting\Api;

use TuneReporting\Base\Endpoints\EndpointBase;

use TuneReporting\Helpers\TuneSdkException;
use TuneReporting\Helpers\TuneServiceException;

/**
 * Provides status of report export request, and upon completion provides
 * download url.
 */
class SessionAuthenticate extends EndpointBase
{
  /**
   * Constructor
   *
   * @param string $api_key MobileAppTracking API Key
   */
  public function __construct() {
    parent::__construct(
      "export"
    );
  }

  /**
   * No recommended fields assigned to this endpoint.
   *
   * @return null
   */
  public function getFieldsRecommended()
  {
    return null;
  }

  /**
   * Action 'download' for polling export queue for status information on request report to be exported.
   *
   * @param string $job_id Job identifier assigned for report export.
   *
   * @return object
   * @throws InvalidArgumentException
   */
  public function download(
    $job_id
  ) {
    if (!is_string($job_id) || empty($job_id)) {
      throw new \InvalidArgumentException("Parameter 'job_id' is not defined.");
    }

    return parent::call(
      $action = "download",
      $map_query_string = array (
        'job_id' => $job_id
      )
    );
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
      throw new \InvalidArgumentException("Parameter 'job_id' is not defined.");
    }

    return parent::fetchRecords(
      $export_controller = "export",
      $export_action = "download",
      $job_id,
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
   * @throws InvalidArgumentException
   * @throws TuneServiceException
   */
  public static function parseResponseReportUrl(
    $response
  ) {
    if (is_null($response)) {
      throw new \InvalidArgumentException("Parameter 'response' is not defined.");
    }

    $data = $response->getData();
    if (is_null($data)) {
      throw new TuneServiceException(
        "Report request failed to get export data: " . print_r($response, true) . PHP_EOL
      );
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
        "Export data response does not contain 'data: " . print_r($data, true) . PHP_EOL
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
