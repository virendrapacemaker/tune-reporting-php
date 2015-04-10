<?php
/**
 * TuneServiceRequest.php
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
 * @version   $Date: 2015-04-08 17:44:36 $
 * @link      https://developers.mobileapptracking.com/tune-reporting-sdks @endlink
 *
 */

namespace TuneReporting\Base\Service;

use TuneReporting\Base\Service\QueryStringBuilder;
use TuneReporting\Helpers\TuneSdkException;
use TuneReporting\Helpers\TuneServiceException;

/**
 * TuneServiceRequest provides the basic interface for all the possible request types.
 */
class TuneServiceRequest
{
  /**
   * Property of TUNE Reporting API controller requested.
   * @var string $controller
   */
  private $controller = null;

  /**
   * Property of TUNE Reporting API controller's action requested.
   * @var string $action
   */
  private $action = null;

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
   * Query String key value dictionary
   * @var array $map_query_string
   */
  private $map_query_string = null;

  /**
   * TUNE Reporting API URL
   * @var string $api_url_base
   */
  private $api_url_base = null;


  /**
   * TUNE Reporting API Version
   * @var string $api_url_version
   */
  private $api_url_version = null;

  /**
   * Constructor
   *
   * @param string $controller    TUNE Reporting API controller
   * @param string $action      TUNE Reporting API controller's action
   * @param string $auth_key      TUNE Reporting authentication key.
   * @param string $auth_type     TUNE Reporting authentication type.
   * @param dict   $map_query_string Query string elements appropriate to the requested controller's action.
   * @param string $api_url_base    TUNE Reporting API base url.
   * @param string $api_url_version   TUNE Reporting API version.
   */
  public function __construct(
    $controller,
    $action,
    $auth_key,
    $auth_type,
    $map_query_string,
    $api_url_base,
    $api_url_version
  ) {
    /*
     * Validate that all required parameters are defined properly.
     */
    if (!is_string($controller) || empty($controller)) {
      throw new \InvalidArgumentException("Parameter 'controller' must be defined string.");
    }

    if (!is_string($action) || empty($action)) {
      throw new \InvalidArgumentException("Parameter 'action' must be defined string.");
    }

    if (!is_string($api_url_base) || empty($api_url_base)) {
      throw new \InvalidArgumentException("Parameter 'api_url_base' must be defined string.");
    }

    if (!is_string($api_url_version) || empty($api_url_version)) {
      throw new \InvalidArgumentException("Parameter 'api_url_version' must be defined string.");
    }

    if (!is_null($map_query_string) && !is_array($map_query_string)) {
      throw new \InvalidArgumentException("Parameter 'query_string_dict' must be defined string.");
    }

    if (is_array($map_query_string) && !empty($map_query_string)) {
      $this->query_string_dict = $map_query_string;
    }

    if (is_string($api_url_base) && !empty($api_url_base)) {
      $this->api_url_base = $api_url_base;
    }

    if (is_string($api_url_version) && !empty($api_url_version)) {
      $this->api_url_version = $api_url_version;
    }

    $this->controller       = $controller;
    $this->action         = $action;
    $this->auth_key       = $auth_key;
    $this->auth_type      = $auth_type;
    $this->query_string_dict  = $map_query_string;
    $this->api_url_base     = $api_url_base;
    $this->api_url_version    = $api_url_version;
  }

  /**
   * Get controller property for this request.
   *
   * @return string
   */
  public function getController()
  {
    return $this->controller;
  }

  /**
   * Set controller property for this request.
   *
   * @param $controller
   */
  public function setController($controller)
  {
    $this->controller = $controller;
  }

  /**
   * Get controller action property for this request.
   *
   * @return string
   */
  public function getAction()
  {
    return $this->action;
  }

  /**
   * Set controller action property for this request.
   *
   * @param $action
   */
  public function setAction($action)
  {
    $this->action = $action;
  }

  /**
   * Get query_string_dict property
   */
  public function getQueryData()
  {
    return $this->query_string_dict;
  }

  /**
   * Set query_string_dict property
   *
   * @param $map_query_string
   */
  public function setQueryData($map_query_string)
  {
    $this->query_string_dict = $map_query_string;
  }

  /**
   * Create query string using provide values in set properties of this request object.
   *
   * @return string
   */
  public function getQueryString()
  {
    $qsb = new QueryStringBuilder();

    // TUNE Reporting SDK Name
    $qsb->add('sdk', constant("TUNE_SDK_NAME"));

    // TUNE Reporting SDK Version
    $qsb->add('ver', constant("TUNE_SDK_VERSION"));

    // TUNE Reporting authentication.
    if ($this->auth_type && $this->auth_key) {
      $qsb->add($this->auth_type, $this->auth_key);
    }

    // Build query string with provided contents in dictionary
    if ($this->query_string_dict && is_array($this->query_string_dict) && !empty($this->query_string_dict)) {
      foreach ($this->query_string_dict as $name => $value) {
        $qsb->add($name, $value);
      }
    }

    return $qsb->toString();
  }

  /**
   * TUNE Reporting API service path
   *
   * @return string
   */
  public function getPath()
  {
    $request_path = sprintf(
      "%s/%s/%s/%s",
      $this->api_url_base,
      $this->api_url_version,
      $this->controller,
      $this->action
    );

    return $request_path;
  }

  /**
   * TUNE Reporting API full service request
   *
   * @return string
   */
  public function getUrl()
  {
    $request_url = sprintf("%s?%s", $this->getPath(), $this->getQueryString());
    return $request_url;
  }
}
