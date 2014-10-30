<?php
/**
 * Request.php
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
 * @version   0.9.9
 * @link      https://developers.mobileapptracking.com @endlink
 *
 */

namespace Tune\Management\Shared\Service;

/**
 * Request provides the basic interface for all the possible request types.
 *
 * @package Tune_API_PHP
 */
class Request
{
    /**
     * Property of Tune Management API controller requested.
     * @var string $controller
     */
    private $controller = null;

    /**
     * Property of Tune Management API controller's action requested.
     * @var string $action
     */
    private $action = null;

    /**
     * Property of user's API KEY provided from their Tune MobileAppTracking account.
     * @var string $api_key
     */
    private $api_key = null;

    /**
     * Query String key value dictionary
     * @var array $query_string_dict
     */
    private $query_string_dict = null;

    /**
     * Tune Management API URL
     * @var string $api_url_base
     */
    private $api_url_base = null;


    /**
     * Tune Management API Version
     * @var string $api_url_version
     */
    private $api_url_version = null;

    /**
     * @param string $controller        Tune Management API controller
     * @param string $action            Tune Management API controller's action
     * @param string $api_key           User's API Key provide by their MobileAppTracking platform account.
     * @param dict   $query_string_dict Query string elements appropriate to the requested controller's action.
     * @param string $api_url_base      Tune Management API base url.
     * @param string $api_url_version   Tune Management API version.
     */
    public function __construct(
        $controller,
        $action,
        $api_key,
        $query_string_dict,
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

        if (!is_string($api_key) || empty($api_key)) {
            throw new \InvalidArgumentException("Parameter 'api_key' must be defined string.");
        }

        if (!is_string($api_url_base) || empty($api_url_base)) {
            throw new \InvalidArgumentException("Parameter 'api_url_base' must be defined string.");
        }

        if (!is_string($api_url_version) || empty($api_url_version)) {
            throw new \InvalidArgumentException("Parameter 'api_url_version' must be defined string.");
        }

        if (!is_null($query_string_dict) && !is_array($query_string_dict)) {
            throw new \InvalidArgumentException("Parameter 'query_string_dict' must be defined string.");
        }

        if (is_array($query_string_dict) && !empty($query_string_dict)) {
            $this->query_string_dict = $query_string_dict;
        }

        if (is_string($api_url_base) && !empty($api_url_base)) {
            $this->api_url_base = $api_url_base;
        }

        if (is_string($api_url_version) && !empty($api_url_version)) {
            $this->api_url_version = $api_url_version;
        }

        $this->controller           = $controller;
        $this->action               = $action;
        $this->api_key              = $api_key;
        $this->query_string_dict    = $query_string_dict;
        $this->api_url_base         = $api_url_base;
        $this->api_url_version      = $api_url_version;
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
     * Get api_key property
     */
    public function getApiKey()
    {
        return $this->api_key;
    }

    /**
     * Set api_key property
     *
     * @param $api_key
     */
    public function setApiKey($api_key)
    {
        $this->api_key = $api_key;
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
     * @param $query_string_dict
     */
    public function setQueryData($query_string_dict)
    {
        $this->query_string_dict = $query_string_dict;
    }

    /**
     * Create query string using provide values in set properties of this request object.
     *
     * @return string
     */
    public function getQueryString()
    {
        $qsb = new \Tune\Management\Shared\Service\QueryStringBuilder();

        // api_key
        if (!is_string($this->api_key) || empty($this->api_key)) {
            throw new \Tune\Shared\TuneSdkException("Parameter 'api_key' is not defined.");
        }

        // Every request should contain an API Key
        $qsb->add('api_key', $this->api_key);

        // Build query string with provided contents in dictionary
        if ($this->query_string_dict && is_array($this->query_string_dict) && !empty($this->query_string_dict)) {
            foreach ($this->query_string_dict as $name => $value) {
                $qsb->add($name, $value);
            }
        }

        return $qsb->toString();
    }

    /**
     * Tune Management API service path
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
     * Tune Management API full service request
     *
     * @return string
     */
    public function getUrl()
    {
        $request_url = sprintf("%s?%s", $this->getPath(), $this->getQueryString());
        return $request_url;
    }
}
