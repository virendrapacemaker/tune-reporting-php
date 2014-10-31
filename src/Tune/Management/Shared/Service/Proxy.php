<?php
/**
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
 * @version   0.9.10
 * @link      https://developers.mobileapptracking.com @endlink
 *
 */

namespace Tune\Management\Shared\Service;

require_once dirname(__FILE__) . "/Constants.php";

use Tune\Shared\TuneSdkException;
use Tune\Shared\TuneServiceException;

/**
 * HTTP POST connection class to Tune MobileAppTracking Management API environment.
 *
 * @package Tune\Management\Shared\Service
 */
class Proxy
{
    private $request = null;
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
     *
     * Full request path
     * @var string
     */
    private $uri = null;

    /**
     * Constructor
     *
     * @param Request $request
     */
    public function __construct($request)
    {
        if (is_null($request)) {
            throw new \InvalidArgumentException(
                "Parameter 'request' is not set."
            );
        }

        if (!is_a($request, "\Tune\Management\Shared\Service\Request")) {
            throw new \InvalidArgumentException(
                "Parameter 'request' is not an instance of \Tune\Management\Shared\Service\Request."
            );
        }

        if (!function_exists('curl_init')) {
            throw new \RuntimeException(
                "PHP extension 'php_curl' is not installed."
            );
        }

        $this->request = $request;
    }

    /**
     * Execute to send request to Tune Management API, and determine success
     * or failure based upon its service's response.
     *
     * @return bool
     * @throws TuneSdkException
     * @throws TuneServiceException
     * @throws \Exception
     */
    public function execute()
    {
        if (is_null($this->request)) {
            throw new \InvalidArgumentException("Parameter 'request' is not set.");
        }

        if (!is_a($this->request, "\Tune\Management\Shared\Service\Request")) {
            throw new \InvalidArgumentException(
                "Parameter 'request' is not an instance of \Tune\Management\Shared\Service\Request."
            );
        }

        $this->response = null;
        $isSuccess = false;
        try {
            $this->uri = $this->request->getUrl();
            $isSuccess = $this->curlSend();
        } catch (\Tune\Shared\TuneServiceException $ex) {
            throw $ex;
        } catch (\Tune\Shared\TuneSdkException $ex) {
            throw $ex;
        } catch (Exception $ex) {
            throw new \Tune\Shared\TuneSdkException(
                "Failed to process request.",
                $ex->getCode(),
                $ex
            );
        }
        return $isSuccess;
    }

    /**
     * Using build URL request, connect with Tune Management API service, wait for reply, and
     * then return complete response including HTTP code, HTTP headers, and full JSON.
     *
     * @return bool
     */
    private function curlSend()
    {
        $this->response = null;
        $success = false;

        try {
            // create a new cURL resource
            $ch = curl_init();

            // set URL and other appropriate options
            curl_setopt($ch, CURLOPT_URL, $this->uri);
            curl_setopt($ch, CURLOPT_HEADER, 1);

            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            if (! $response_raw = curl_exec($ch)) {
                $error = curl_error($ch);

                // close cURL resource, and free up system resources
                curl_close($ch);

                throw new \RuntimeException($error);
            }

            $success = true;
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            // close cURL resource, and free up system resources
            curl_close($ch);

            $response_array = explode("\r\n", trim($response_raw));
            $response_json = array_pop($response_array);
            array_pop($response_array);
            $headers = $response_array;
            $json = json_decode($response_json, true);

            $this->response = new \Tune\Management\Shared\Service\Response(
                $this->uri,
                $json,
                $headers,
                $http_code
            );
        } catch (\Tune\Shared\TuneServiceException $ex) {
            throw $ex;
        } catch (\Tune\Shared\TuneSdkException $ex) {
            throw $ex;
        } catch (Exception $ex) {
            throw new \Tune\Shared\TuneSdkException("Failed to process request.", $ex->getCode(), $ex);
        }

        return $success;
    }
}
