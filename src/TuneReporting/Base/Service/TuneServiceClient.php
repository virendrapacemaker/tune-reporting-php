<?php
/**
 * TuneServiceClient.php, Client to TUNE MobileAppTracking Service.
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
 * @package   tune_reporting_base_service
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version   $Date: 2015-04-10 11:10:41 $
 * @link      https://developers.mobileapptracking.com/tune-reporting-sdks @endlink
 *
 */

namespace TuneReporting\Base\Service;

use TuneReporting\Base\Service\TuneServiceProxy;
use TuneReporting\Base\Service\TuneServiceRequest;
use TuneReporting\Helpers\TuneSdkException;

require_once dirname(dirname(dirname(__FILE__))) . "/Version.php";
require_once dirname(__FILE__) . "/Constants.php";

/**
 * TUNE MobileAppTracking Service access class
 */
class TuneServiceClient
{
  /**
   * @var object @see TuneServiceRequest
   */
  private $request = null;

  /**
   * @var object @see TuneServiceResponse
   */
  private $response = null;

  /**
   * Get request property for this request.
   *
   * @return object
   */
  public function getRequest()
  {
    return $this->request;
  }

  /**
   * Get response property for this request.
   *
   * @return object
   */
  public function getResponse()
  {
    return $this->response;
  }

  /**
   * Constructor
   *
   * @param string    $controller       TUNE Reporting API endpoint name
   * @param string    $action         TUNE Reporting API endpoint's action name
   * @param string    $auth_key       TUNE Reporting authentication key.
   * @param string    $auth_type      TUNE Reporting authentication type.
   * @param null|array  $map_query_string  Action's query string parameters
   * @param null|string $api_url_base     TUNE Reporting API base path
   * @param null|string $api_url_version    TUNE Reporting API version
   */
  public function __construct(
    $controller,
    $action,
    $auth_key,
    $auth_type,
    $map_query_string = null,
    $api_url_base = null,
    $api_url_version = null
  ) {
    // controller
    if (!is_string($controller) || empty($controller)) {
      throw new \InvalidArgumentException("Parameter 'controller' is not defined.");
    }
    // action
    if (!is_string($action) || empty($action)) {
      throw new \InvalidArgumentException("Parameter 'action' is not defined.");
    }
    if (is_null($api_url_base)) {
      $api_url_base = constant("TUNE_MANAGEMENT_API_BASE_URL");
    }

    if (is_null($api_url_version)) {
      $api_url_version = constant("TUNE_MANAGEMENT_API_VERSION");
    }

    // set up the request
    $this->request = new TuneServiceRequest(
      $controller,
      $action,
      $auth_key,
      $auth_type,
      $map_query_string,
      $api_url_base,
      $api_url_version
    );
  }

  /**
   *
   * Get the current version of this SDK from configuration file
   *
   * @return string version number
   */
  public static function version()
  {
    return constant("TUNE_SDK_VERSION");
  }

  /**
   * Call TUNE Reporting API Service with provided request.
   *
   * @return bool
   * @throws TuneSdkException
   * @throws Exception
   */
  public function call()
  {
    if (is_null($this->request)) {
      throw new TuneSdkException("TuneServiceRequest was not defined.");
    }

    $success = false;
    $this->response = null;

    try {
      # make the request
      $proxy = new TuneServiceProxy($this->request);
      if ($proxy->execute()) {
        $success = true;
        $this->response = $proxy->getResponse();
      }
    } catch (Exception $ex) {
      throw $ex;
    }

    return $success;
  }
}
