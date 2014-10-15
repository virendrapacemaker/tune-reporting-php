<?php
/**
 * ReportExportWorker.php
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
 * @package   Tune_PHP_SDK
 * @author    Jeff Tanner <jefft@tune.com>
 * @copyright 2014 Tune (http://www.tune.com)
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version   0.9.1
 * @link      https://developers.mobileapptracking.com Tune Developer Community @endlink
 *
 */

namespace Tune\Management\Reports;

use Tune\Common\TuneSdkException;
use Tune\Common\TuneServiceException;

/**
 * "Threaded worker for handle polling of report request on export queue.
 *
 * @package Tune\Management\Reports
 */
class ReportExportWorker extends \Thread
{
    /**
     * @var null|string
     */
    private $mod_export_class = null;
    /**
     * @var null|string
     */
    private $mod_export_function = null;
    /**
     * @var null|string
     */
    private $api_key = null;
    /**
     * @var null|string
     */
    private $job_id = null;
    /**
     * @var int
     */
    private $sleep = 60;

    private $verbose = false;
    private $class_export = null;
    private $response = null;

    /**
     * Constructor
     *
     * @param string    $mod_export_class       Reference class name for worker to
     *                                          perform download status query.
     * @param string    $mod_export_function    Reference class function name for worker
     *                                          to perform download status query.
     * @param string    $api_key                MobileAppTracking API Key
     * @param string    $job_id                 Provided Job Identifier to reference requested report on export queue.
     * @param bool      $verbose                Debug purposes only to view progress of job on export queue.
     * @param int       $sleep                  Polling delay between querying job status on export queue.
     */
    public function __construct(
        $mod_export_class,
        $mod_export_function,
        $api_key,
        $job_id,
        $verbose = false,
        $sleep = 60
    ) {
        if (!is_string($mod_export_class) || empty($mod_export_class)) {
            throw new \InvalidArgumentException("Parameter 'mod_export_class' is not defined.");
        }
        if (!is_string($mod_export_function) || empty($mod_export_function)) {
            throw new \InvalidArgumentException("Parameter 'mod_export_function' is not defined.");
        }
        if (!is_string($api_key) || empty($api_key)) {
            throw new \InvalidArgumentException("Parameter 'api_key' is not defined.");
        }
        if (!is_string($job_id) || empty($job_id)) {
            throw new \InvalidArgumentException("Parameter 'job_id' is not defined.");
        }

        $instance = new $mod_export_class($api_key);

        $this->mod_export_class = $mod_export_class;
        $this->mod_export_function = $mod_export_function;

        $this->api_key = $api_key;
        $this->job_id = $job_id;
        $this->sleep = $sleep;

        $this->verbose = $verbose;
        $this->class_export = $instance;
        $this->response = null;

    }

    /**
     * Poll export status and upon completion gather download URL referencing requested report.
     *
     * @throws \Tune\Common\TuneSdkException
     * @throws \Tune\Common\TuneServiceException
     */
    public function run()
    {
        $percent_complete = 0;
        $status = null;
        $response = null;
        $attempt = 0;

        while (true) {

            $mod_export_function = $this->mod_export_function;
            $response = $this->class_export->$mod_export_function($this->job_id);

            if (is_null($response)) {
                throw new TuneSdkException("No response returned from export request.");
            }

            $request_url = $response->getRequestUrl();
            $response_http_code = $response->getHttpCode();

            if (is_null($response->getData())) {
                throw new TuneSdkException(
                    "No response data returned from export. Request URL: '{$request_url}'"
                );
            }

            if ($response_http_code != 200) {
                throw new TuneServiceException(
                    "Service failed request: {$response_http_code}. Request URL: '{$request_url}'"
                );
            }

            $status = $response->getData()["status"];
            if ($status == "fail" || $status == "complete") {
                break;
            }

            $attempt += 1;
            if ($this->verbose) {
                echo "= attempt: {$attempt}, response: " . print_r($response, true) . PHP_EOL;
            }
            sleep($this->sleep);
        }

        if ($status != "complete") {
            throw new TuneServiceException(
                "Export request '{$status}':, response: " . print_r($response, true)
            );
        }

        if ($this->verbose) {
            echo "= attempt: {$attempt}, response: " . print_r($response, true) . PHP_EOL;
        }

        $this->response = $response;
    }

    /**
     * Property that will hold completed report downloaded from Management API service.
     *
     * @return object
     */
    public function getResponse()
    {
        return $this->response;
    }
}
