<?php
/**
 * ItemsEndpointBase.php
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

namespace Tune\Management\Shared\Endpoints;

require_once dirname(dirname(dirname(dirname(__FILE__)))) . "/Shared/Helper.php";

use Tune\Shared\TuneSdkException;
use Tune\Shared\TuneServiceException;
use Tune\Management\Shared\Service\TuneManagementClient;
use Tune\Management\Api\Export;

/**
 * Base class for handling Tune Management API endpoints.
 *
 * @package Tune\Management\Shared\Endpoints
 */
class ItemsEndpointBase extends EndpointBase
{
    /**
     * Constructor
     *
     * @param string $controller        Tune Management API endpoint name.
     * @param string $api_key           Tune MobileAppTracking API Key.
     * @param bool   $validate_fields   Validate fields used by actions' parameters.
     */
    public function __construct(
        $controller,
        $api_key,
        $validate_fields = false
    ) {
        parent::__construct(
            $controller,
            $api_key,
            $validate_fields
        );
    }

    /**
     * Counts all existing records that match filter criteria
     * and returns an array of found model data.
     *
     * @param string $filter            Filter the results and apply conditions
     *                                  that must be met for records to be included in data.
     */
    public function count(
        $filter = null
    ) {
        if (!is_null($filter)) {
            $filter = $this->validateFilter($filter);
        }

        return parent::call(
            $action = "count",
            $query_string_dict = array (
                'filter' => $filter
            )
        );
    }

    /**
     * Finds all existing records that match filter criteria
     * and returns an array of found model data.
     *
     * @param string $filter                Filter the results and apply conditions
     *                                      that must be met for records to be
     *                                      included in data.
     * @param string $fields                No value returns default fields, "*"
     *                                      returns all available fields,
     *                                      or provide specific fields.
     * @param int    $limit                 Limit number of results, default 10,
     *                                      0 shows all.
     * @param int    $page                  Pagination, default 1.
     * @param dict   $sort                  Expression defining sorting found
     *                                      records in result set base upon provided
     *                                      fields and its modifier (ASC or DESC).
     *
     * @return object
     */
    public function find(
        $filter = null,
        $fields = null,
        $limit = null,
        $page = null,
        $sort = null
    ) {
        if (!is_null($filter)) {
            $filter = $this->validateFilter($filter);
        }
        if (!is_null($fields)) {
            $fields = $this->validateFields($fields);
        }
        if (!is_null($sort)) {
            $sort = $this->validateSort($sort);
        }

        return parent::call(
            $action = "find",
            $query_string_dict = array (
                'filter' => $filter,
                'fields' => $fields,
                'limit' => $limit,
                'page' => $page,
                'sort' => $sort
            )
        );
    }

    /**
     * Places a job into a queue to generate a report that will contain
     * records that match provided filter criteria, and it returns a job
     * identifier to be provided to action /export/download.json to download
     * completed report.
     *
     * @param string $start_date            YYYY-MM-DD HH:MM:SS
     * @param string $end_date              YYYY-MM-DD HH:MM:SS
     * @param string $filter                Filter the results and apply conditions
     *                                      that must be met for records to be
     *                                      included in data.
     * @param string $fields                Provide fields if format is 'csv'.
     * @param string $format                Export format: csv, json
     * @param string $response_timezone     Setting expected timezone for results,
     *                                      default is set in account.
     *
     * @return object
     */
    public function export(
        $filter = null,
        $fields = null,
        $format = null
    ) {
        if (!is_null($filter)) {
            $filter = $this->validateFilter($filter);
        }
        if (!is_null($fields)) {
            $fields = $this->validateFields($fields);
        }

        if (is_null($format)) {
            $format = "csv";
        }

        if (!in_array($format, self::$report_export_formats)) {
            throw new \InvalidArgumentException("Parameter 'format' is invalid: '{$format}'.");
        }

        if (("csv" == $format) && (is_null($fields) || empty($fields))) {
            throw new \InvalidArgumentException("Parameter 'fields' needs to be defined if report format is 'csv'.");
        }

        return parent::call(
            $action = "find_export_queue",
            $query_string_dict = array (
                'filter' => $filter,
                'fields' => $fields,
                'format' => $format
            )
        );
    }

    /**
     * Query status of insight reports. Upon completion will
     * return url to download requested report.
     *
     * @param string $job_id    Provided Job Identifier to reference
     *                          requested report on export queue.
     */
    public function status(
        $job_id
    ) {
        if (!is_string($job_id) || empty($job_id)) {
            throw new \InvalidArgumentException(
                "Parameter 'job_id' is not defined."
            );
        }

        $client = new TuneManagementClient(
            "export",
            "download",
            $this->api_key,
            $query_string_dict = array (
                'job_id' => $job_id
            )
        );

        return $client->cal();
    }

    /**
     * Helper function for fetching report upon completion.
     *
     * @param string $job_id        Job identifier assigned for report export.
     * @param bool   $verbose       For debug purposes to monitor job export completion status.
     * @param int    $sleep         Polling delay for checking job completion status.
     *
     * @return null
     */
    public function fetch(
        $job_id,
        $verbose = false,
        $sleep = 10
    ) {
        if (!is_string($job_id) || empty($job_id)) {
            throw new \InvalidArgumentException(
                "Parameter 'job_id' is not defined."
            );
        }

        return parent::fetchRecords(
            $mod_export_class = "Tune\Management\Api\Export",
            $mod_export_function = "download",
            $job_id,
            $verbose,
            $sleep
        );
    }
}
