<?php
/**
 * ReportExportWorker.php
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
 * @package   tune_reporting_helpers
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version   $Date: 2015-04-10 11:10:41 $
 * @link      https://developers.mobileapptracking.com/tune-reporting-sdks @endlink
 *
 */

namespace TuneReporting\Base\Endpoints;

use TuneReporting\Helpers\TuneServiceException;
use TuneReporting\Helpers\TuneSdkException;
use TuneReporting\Api\Export;
use TuneReporting\Base\Service\TuneServiceClient;

/**
 * "Threaded worker for handle polling of report request on export queue.
 */
class ReportExportWorker
{
  /**
   * Export Controller
   * @var null|string
   */
  private $export_controller = null;
  /**
   * Export Action
   * @var null|string
   */
  private $export_action = null;


  /**
   * TUNE Reporting authentication key.
   * @var string
   */
  private $auth_key = null;

  /**
   * TUNE Reporting authentication type.
   * @var string
   */
  private $auth_type = null;

  /**
   * Report Job Identifier on Export Queue.
   * @var null|string
   */
  private $job_id = null;
  /**
   * Amount of sleep between status requests.
   * @var int seconds
   */
  private $sleep = 60;
  /**
   * Timeout requesting status.
   * @var int seconds
   */
  private $timeout = 0;
  /**
   * Verbose output to show progressive status of report on queue.
   * @var bool
   */
  private $verbose = false;
  /**
   * Response upon either completion or failure for request.
   * @var object @see TuneServiceResponse
   */
  private $response = null;

  /**
   * Constructor
   *
   * @param string  $export_controller    Controller for report export status.
   * @param string  $export_action      Action for report export status.
   * @param string  $auth_key         TUNE Reporting authentication key.
   * @param string  $auth_type        TUNE Reporting authentication type.
   * @param string  $job_id         Provided Job Identifier to reference requested report on export queue.
   * @param bool    $verbose        Debug purposes only to view progress of job on export queue.
   * @param int     $sleep          Polling delay between querying job status on export queue.
   * @param int     $timeout        Stop polling if time exceeds timeout period.
   */
  public function __construct(
    $export_controller,
    $export_action,
    $auth_key,
    $auth_type,
    $job_id,
    $verbose = false,
    $sleep = 60,
    $timeout = 0
  ) {
    if (!is_string($export_controller) || empty($export_controller)) {
      throw new \InvalidArgumentException("Parameter 'export_controller' is not defined.");
    }
    if (!is_string($export_action) || empty($export_action)) {
      throw new \InvalidArgumentException("Parameter 'export_action' is not defined.");
    }
    if (!is_string($auth_key) || empty($auth_key)) {
      throw new \InvalidArgumentException("Parameter 'auth_key' is not defined.");
    }
    if (!is_string($auth_type) || empty($auth_type)) {
      throw new \InvalidArgumentException("Parameter 'auth_type' is not defined.");
    }
    if (!is_string($job_id) || empty($job_id)) {
      throw new \InvalidArgumentException("Parameter 'job_id' is not defined.");
    }

    $this->export_controller = $export_controller;
    $this->export_action = $export_action;

    $this->auth_key = $auth_key;
    $this->auth_type = $auth_type;

    $this->job_id = $job_id;
    $this->sleep = $sleep;
    $this->timeout = $timeout;

    $this->verbose = $verbose;
    $this->response = null;
  }

  /**
   * Poll export status and upon completion gather download URL referencing requested report.
   *
   * @throws TuneSdkException
   * @throws TuneServiceException
   */
  public function run()
  {
    $status = null;
    $response = null;
    $attempt = 0;

    $client = new TuneServiceClient(
      $this->export_controller,
      $this->export_action,
      $this->auth_key,
      $this->auth_type,
      $map_query_string = array (
        'job_id' => $this->job_id
      )
    );

    $total_time = 0;

    while (true) {

      if (($this->timeout > 0) && ($this->timeout <= $total_time)) {
        throw new TuneSdkException(
          "Export request did not complete within timeout '" . $this->timeout . "' seconds."
        );
      }

      $client->call();
      $response = $client->getResponse();

      // Failed to return response.
      if (is_null($response)) {
        throw new TuneSdkException("No response returned from export request.");
      }

      $request_url = $response->getRequestUrl();
      $response_http_code = $response->getHttpCode();

      // Failed to get successful service response.
      if (($response->getHttpCode() != 200) || ($response->getErrors() != null)) {
        throw new TuneServiceException(
          sprintf("Service failed: %d: %s", $response->getHttpCode(), print_r($response, true))
        );
      }

      // Failed to get data.
      $data = $response->getData();
      if (is_null($data)) {
        throw new TuneServiceException(
          "Report request failed to get export data, response: " . print_r($response, true)
        );
      }

      // Failed to get status.
      if (!array_key_exists("status", $data)) {
        throw new TuneSdkException(
          "Export data does not contain report 'status', response: " . print_r($response, true)
        );
      }

      // Get status.
      $status = $data["status"];
      if ($status == "fail" || $status == "complete") {
        break;
      }

      $attempt += 1;
      if ($this->verbose) {
        echo " attempt: {$attempt}:, response: " . $response->toString() . PHP_EOL;
      }
      sleep($this->sleep);
      $total_time += $this->sleep;
    }

    if ($status != "complete") {
      throw new TuneServiceException(
        "Export request '{$status}':, response: " . $response->toString() . PHP_EOL
      );
    }

    if ($this->verbose) {
      echo " attempt: {$attempt}, response: " . $response->toString() . PHP_EOL;
    }

    $this->response = $response;

    return true;
  }

  /**
   * Property that will hold completed report downloaded from TUNE
   * MobileAppTracking service.
   *
   * @return object
   */
  public function getResponse()
  {
    return $this->response;
  }
}
