<?php
/**
 * TuneManagementProxy.php, Connection to TUNE Management API Service.
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
 * @version   $Date: 2015-01-05 14:24:08 $
 * @link      https://developers.mobileapptracking.com/tune-reporting-sdks @endlink
 *
 */

namespace TuneReporting\Base\Service;

require_once dirname(__FILE__) . "/Constants.php";

use TuneReporting\Helpers\TuneSdkException;
use TuneReporting\Helpers\TuneServiceException;
use TuneReporting\Base\Service\TuneManagementResponse;

/**
 * HTTP POST connection class to TUNE MobileAppTracking Management API environment.
 */
class TuneManagementProxy
{
    /**
     * @var object @see TuneManagementRequest
     */
    private $request = null;

    /**
     * @var object @see TuneManagementResponse
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
     *
     * Full request path
     * @var string
     */
    private $uri = null;

    /**
     * Constructor
     *
     * @param TuneManagementRequest $request
     */
    public function __construct($request)
    {
        if (is_null($request)) {
            throw new \InvalidArgumentException(
                "Parameter 'request' is not set."
            );
        }

        if (!is_a($request, "\TuneReporting\Base\Service\TuneManagementRequest")) {
            throw new \InvalidArgumentException(
                "Parameter 'request' is not an instance of \TuneReporting\Base\Service\TuneManagementRequest."
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
     * Execute to send request to TUNE Reporting API, and determine success
     * or failure based upon its service's response.
     *
     * @return bool
     * @throws TuneSdkException
     * @throws TuneServiceException
     * @throws Exception
     */
    public function execute()
    {
        if (is_null($this->request)) {
            throw new \InvalidArgumentException("Parameter 'request' is not set.");
        }

        if (!is_a($this->request, "\TuneReporting\Base\Service\TuneManagementRequest")) {
            throw new \InvalidArgumentException(
                "Parameter 'request' is not an instance of \TuneReporting\Base\Service\TuneManagementRequest."
            );
        }

        $this->response = null;
        $isSuccess = false;
        try {
            $this->uri = $this->request->getUrl();
            $isSuccess = $this->curlSend();
        } catch (TuneServiceException $ex) {
            throw $ex;
        } catch (TuneSdkException $ex) {
            throw $ex;
        } catch (Exception $ex) {
            throw new TuneSdkException(
                "Failed to process request.",
                $ex->getCode(),
                $ex
            );
        }
        return $isSuccess;
    }

    /**
     * Using build URL request, connect with TUNE Reporting API service, wait for reply, and
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

            $this->response = new TuneManagementResponse(
                $this->uri,
                $json,
                $headers,
                $http_code
            );
        } catch (TuneServiceException $ex) {
            throw $ex;
        } catch (TuneSdkException $ex) {
            throw $ex;
        } catch (Exception $ex) {
            throw new TuneSdkException("Failed to process request.", $ex->getCode(), $ex);
        }

        return $success;
    }
}
