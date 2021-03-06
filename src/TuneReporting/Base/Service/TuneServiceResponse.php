<?php
/**
 * TuneServiceResponse.php
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
 * @category    TUNE_Reporting
 *
 * @author      Jeff Tanner <jefft@tune.com>
 * @copyright   2015 TUNE, Inc. (http://www.tune.com)
 * @package     tune_reporting_base_service
 * @license     http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version     $Date: 2015-11-17 09:18:01 $
 * @link        https://developers.mobileapptracking.com/tune-reporting-sdks @endlink
 *
 */

namespace TuneReporting\Base\Service;

/**
 * The successful response to request.
 */
class TuneServiceResponse
{
    /**
     * Property of actual HTTP uri requested by by TUNE Reporting API.
     * @var string $request_url
     */
    private $request_url;

    /**
     * Property of HTTP response code returned from curl after completion for
     * TUNE Reporting API request.
     * @var integer $response_http_code
     */
    private $response_http_code;

    /**
     * Property of HTTP response headers returned from curl after completion
     * for TUNE Reporting API request.
     * @var array $response_headers
     */
    private $response_headers;

    /**
     * Property of full JSON response returned from service of
     * TUNE Reporting API.
     * @var array $response_json
     */
    private $response_json;

    /**
     * Constructor
     *
     * @param string $request_url             TUNE Reporting API request URL.
     * @param string $response_json         TUNE Reporting API Service full
     *                                        response.
     * @param array    $response_headers        TUNE Reporting API Service
     *                                        response HTTP headers.
     * @param string $response_http_code    TUNE Reporting API Service response
     *                                        HTTP code.
     */
    public function __construct(
        $request_url,
        $response_json,
        $response_headers,
        $response_http_code
    ) {
        /*
         * Validate that all required parameters are defined properly.
         */
        if (!is_string($request_url) || empty($request_url)) {
            throw new \InvalidArgumentException(
                "Parameter 'request_url' must be defined string."
            );
        }

        if (is_null($response_json)) {
            throw new \InvalidArgumentException(
                "Parameter 'response_json' must be defined."
            );
        }

        if (is_null($response_headers)) {
            throw new \InvalidArgumentException(
                "Parameter 'response_headers' must be defined."
            );
        }

        if (is_null($response_http_code)) {
            throw new \InvalidArgumentException(
                "Parameter 'response_http_code' must be defined."
            );
        }

        $this->request_url            = $request_url;
        $this->response_json        = $response_json;
        $this->response_headers     = $response_headers;
        $this->response_http_code     = $response_http_code;
    }

    /**
     * Custom string representation of object
     *
     * @return string
     */
    public function toString()
    {
        $string = $this->getHttpCode()
            . "\nrequest_url:\t " . $this->request_url
            . "\nstatus_code:\t " . $this->getStatusCode()
            . "\nresponse_size:\t " . $this->getResponseSize()
            . "\ndata:\t\t" . print_r($this->getData(), true)
            . "\nhttp_code:\t\t" . $this->getHttpCode()
            . "\nheaders:\t\t" . print_r($this->getHeaders(), true);

        $errors = $this->getErrors();
        if ($errors && is_array($errors) && !empty($errors)) {
            $string .= "\nerrors:\t\t" . print_r($errors, true);
        }

        $debugs = $this->getDebugs();
        if ($errors && is_array($debugs) && !empty($debugs)) {
            $string .= "\ndebugs:\t\t" . print_r($debugs, true);
        }

        return $string;
    }

    /**
     * Returns the JSON representation of a value.
     *
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->response_json);
    }

    /**
     * Get property of request URL used to generate this response.
     *
     * @return string
     */
    public function getRequestUrl()
    {
        return $this->request_url;
    }

    /**
     * Get property of full JSON response provided by
     * TUNE Reporting API service.
     *
     * @return array
     */
    public function getJSON()
    {
        return $this->response_json;
    }

    /**
     * Get property of HTTP status code returned from service proxy.
     * @return mixed
     */
    public function getHeaders()
    {
        return $this->response_headers;
    }

    /**
     * Get property of HTTP status code returned from service proxy response.
     * @return integer
     */
    public function getHttpCode()
    {
        return $this->response_http_code;
    }

    /**
     * Get property of data JSON response provided by TUNE Reporting API service.
     *
     * @return array
     */
    public function getData()
    {
        return $this->response_json['data'];
    }

    /**
     * TUNE Reporting API's response value pertaining to its key 'response_size'.
     *
     * @return integer
     */
    public function getResponseSize()
    {
        return $this->response_json['response_size'];
    }


    /**
     * TUNE Reporting API's response value pertaining to its key 'status_code'.
     *
     * @return integer
     */
    public function getStatusCode()
    {
        return $this->response_json['status_code'];
    }

    /**
     * TUNE Reporting API's response value pertaining to its key 'errors'
     * only if service experienced an error.
     *
     * @return array
     */
    public function getErrors()
    {
        if (array_key_exists('errors', $this->response_json)) {
            return $this->response_json['errors'];
        }

        return null;
    }

    /**
     * TUNE Reporting API's response value pertaining to its key 'debugs'
     * only if request's query string expressed for service to
     * provide debug information.
     *
     * @return array
     */
    public function getDebugs()
    {
        if (array_key_exists('debugs', $this->response_json)) {
            return $this->response_json['debugs'];
        }

        return null;
    }
}
