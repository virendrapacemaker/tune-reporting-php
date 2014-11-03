<?php
/**
 * EndpointBase.php
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
 * 
 * @author    Jeff Tanner <jefft@tune.com>
 * @copyright 2014 Tune (http://www.tune.com)
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version   0.9.12
 * @link      https://developers.mobileapptracking.com @endlink
 *
 */

namespace Tune\Management\Shared\Endpoints;

require_once dirname(dirname(dirname(dirname(__FILE__)))) . "/Shared/Helper.php";
require_once dirname(dirname(dirname(dirname(__FILE__)))) . "/Version.php";

use Tune\Shared\TuneSdkException;
use Tune\Shared\TuneServiceException;
use Tune\Management\Shared\Service\TuneManagementClient;
use Tune\Shared\ReportExportWorker;

/**
 * Base class for handling Tune Management API endpoints.
 *
 * @package Tune\Management\Shared\Endpoints
 */
class EndpointBase
{
    const TUNE_FIELDS_ALL     = 0;
    const TUNE_FIELDS_DEFAULT = 1;
    const TUNE_FIELDS_RELATED = 2;
    const TUNE_FIELDS_MINIMAL = 4;
    const TUNE_FIELDS_RECOMMENDED = 8;

    /**
     * Tune Management API Endpoint
     * @var string
     */
    protected $controller = null;

    /**
     * MobileAppTracking API Key
     * @var string
     */
    protected $api_key = null;

    /**
     * Tune Management API Endpoint's fields
     * @var null|array
     */
    protected $fields = null;

    /**
     * Validate action's parameters against this endpoint' fields.
     * @var bool
     */
    protected $validate_fields = false;

    /**
     * Endpoint's model
     * @var string
     */
    protected $model_name = null;

    /**
     * Parameter 'sort' directions.
     * @var array
     */
    protected static $sort_directions
    = array(
            "DESC",
            "ASC"
    );

    /**
     * Parameter 'filter' expression operations.
     * @var array
    */
    protected static $filter_operations
    = array(
            "=",
            "!=",
            "<",
            "<=",
            ">",
            ">=",
            "IS",
            "NOT",
            "NULL",
            "IN",
            "LIKE",
            "RLIKE",
            "REGEXP",
            "BETWEEN"
    );

    /**
     * Parameter 'filter' expression conjunctions.
     * @var array
    */
    protected static $filter_conjunctions
    = array(
            "AND",
            "OR"
    );

    /**
     * Recommended fields for report exports.
     * @var array
     */
    protected $fields_recommended = null;

    /**
     * Parameter 'format' for export report.
     * @var array
    */
    protected static $report_export_formats
    = array(
            "csv",
            "json"
    );

    /**
     * Constructor
     *
     * @param string $controller        Tune Management API Endpoint
     * @param string $api_key           MobileAppTracking API Key
     * @param bool   $validate_fields   Validate fields used by actions' parameters.
    */
    public function __construct(
        $controller,
        $api_key,
        $validate_fields = false
    ) {
        if (!isCurlInstalled()) {
            throw new \Exception(
                sprintf(
                    "%s %s: requires PHP Module 'curl'",
                    constant("TUNE_SDK_NAME"),
                    constant("TUNE_SDK_VERSION")
                )
            );
        }

        // controller
        if (!is_string($controller) || empty($controller)) {
            throw new \InvalidArgumentException(
                "Parameter 'controller' is not defined."
            );
        }
        // api key
        if (!is_string($api_key) || empty($api_key)) {
            throw new \InvalidArgumentException(
                "Parameter 'api_key' is not defined."
            );
        }
        // validate_fields
        if (!is_bool($validate_fields)) {
            throw new \InvalidArgumentException(
                "Parameter 'validate_fields' is not defined as a boolean."
            );
        }

        $this->controller = $controller;
        $this->api_key = $api_key;
        $this->validate_fields = $validate_fields;
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
     * Get API Key property for this request.
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->api_key;
    }

    /**
     * Call Tune Management API service for this controller.
     *
     * @param string      $action               Tune Management API endpoint's
     *                                          action name
     * @param null|array  $query_string_dict    Action's query string
     *                                          parameters
     * @return object @see TuneManagementResponse
     * @throws TuneSdkException
     */
    protected function call(
        $action,
        $query_string_dict = null
    ) {
        // controller
        if (!is_string($action) || empty($action)) {
            throw new \InvalidArgumentException(
                "Parameter 'action' is not defined."
            );
        }

        $client = new TuneManagementClient(
            $this->controller,
            $action,
            $this->api_key,
            $query_string_dict
        );

        $client->call();

        return $client->getResponse();
    }

    /**
     * Provide complete definition for this endpoint.
     */
    public function define()
    {
        return $this->call("define");
    }

    /**
     * Get all fields for assigned endpoint.
     *
     * @param integer $enum_fields_selection Filter available fields.
     * @return array
     * @throws TuneSdkException
     * @throws TuneServiceException
     */
    public function fields(
        $enum_fields_selection = self::TUNE_FIELDS_ALL
    ) {
        if (($this->validate_fields
             || !($enum_fields_selection & self::TUNE_FIELDS_RECOMMENDED))
            && is_null($this->fields)
        ) {
            $this->getEndpointFields();
        }

        if ($enum_fields_selection & self::TUNE_FIELDS_RECOMMENDED) {
            if (!is_null($this->fields_recommended)
                && !empty($this->fields_recommended)
            ) {
                return $this->fields_recommended;
            }

            $enum_fields_selection = self::TUNE_FIELDS_DEFAULT;
        }

        if (!($enum_fields_selection & self::TUNE_FIELDS_DEFAULT)
            && ($enum_fields_selection & self::TUNE_FIELDS_RELATED)
            ) {
            return array_keys($this->fields);
        }

        $fields_filtered = array();
        foreach ($this->fields as $field_name => $field_info) {
            if (!($enum_fields_selection & self::TUNE_FIELDS_RELATED)
                && !($enum_fields_selection & self::TUNE_FIELDS_MINIMAL)
                && $field_info["related"]
                ) {
                continue;
            }

            if (!($enum_fields_selection & self::TUNE_FIELDS_DEFAULT)
                && !$field_info["related"]
            ) {
                $fields_filtered[$field_name] = $field_info;
                continue;
            }

            if (($enum_fields_selection & self::TUNE_FIELDS_DEFAULT)
                && $field_info["default"]
            ) {
                if (($enum_fields_selection & self::TUNE_FIELDS_MINIMAL)
                    && $field_info["related"]
                ) {
                    foreach (array(".name", ".ref") as $related_field) {
                        if (endsWith($field_name, $related_field)) {
                            $fields_filtered[$field_name] = $field_info;
                        }
                    }
                    continue;
                }
                $fields_filtered[$field_name] = $field_info;
                continue;
            }

            if (($enum_fields_selection & self::TUNE_FIELDS_RELATED)
                && $field_info["related"]
            ) {
                $fields_filtered[$field_name] = $field_info;
                continue;
            }
        }

        return array_keys($fields_filtered);
    }

    /**
     * Get model name for this endpoint.
     *
     * @return string
     */
    public function getModelName()
    {
        if (is_null($this->fields)) {
            $this->fields();
        }

        return $this->model_name;
    }

    /**
     * Fetch all fields from model and related models of this endpoint.
     */
    protected function getEndpointFields()
    {
        $query_string_dict = array(
            "controllers" => $this->controller,
            "details" => "modelName,fields"
        );

        $client = new TuneManagementClient(
            "apidoc",
            "get_controllers",
            $this->api_key,
            $query_string_dict
        );

        $client->call();

        $response = $client->getResponse();
        $http_code = $response->getHttpCode();
        $data = $response->getData();

        if ($http_code != 200) {
            $request_url = $response->getRequestUrl();
            throw new TuneServiceException(
                "Connection failure '{$request_url}': '{$http_code}'"
            );
        }

        if (is_null($data) || empty($data)) {
            $controller = $this->controller;
            throw new TuneServiceException(
                "Failed to get fields for endpoint: '{$controller}'."
            );
        }

        $endpoint_metadata = $data[0];
        $fields             = $endpoint_metadata["fields"];
        $this->model_name   = $endpoint_metadata["modelName"];

        $fields_found = array();
        $related_fields = array();

        foreach ($fields as $field) {
            if ($field["related"] == 1) {
                if ($field["type"] == "property") {
                    $related_property = $field["name"];
                    if (!array_key_exists($related_property, $related_fields)) {
                        $related_fields[$related_property] = array();
                    }
                    continue;
                }

                $field_related = explode(".", $field["name"]);
                $related_property = $field_related[0];
                $related_field_name = $field_related[1];

                if (!array_key_exists($related_property, $related_fields)) {
                    $related_fields[$related_property] = array();
                }

                $related_fields[$related_property][] = $related_field_name;
                continue;
            }

            $fields_found[$field["name"]] = array(
                "default" => $field["fieldDefault"],
                "related" => false
            );
        }

        ksort($fields_found);

        $fields_found_merged = array();

        foreach ($fields_found as $field_name => $field_info) {
            $fields_found_merged[$field_name] = $field_info;
            if (($field_name != "_id") && endsWith($field_name, "_id")) {
                $related_property = substr($field_name, 0, strlen($field_name) - 3);
                if (array_key_exists($related_property, $related_fields)
                    && !empty($related_fields[$related_property])
                ) {
                    foreach ($related_fields[$related_property] as $related_field_name) {
                        // Not including duplicate data.
                        if ($related_field_name == "id") {
                            continue;
                        }
                        $related_property_field_name = "{$related_property}.{$related_field_name}";

                        $fields_found_merged[$related_property_field_name] = array(
                            "default" => $field_info["default"],
                            "related" => true
                        );
                    }
                } else {
                    $fields_found_merged["{$related_property}.name"] = array(
                        "default" => $field_info["default"],
                        "related" => true
                    );
                }
            }
        }

        $this->fields = $fields_found_merged;

        return $this->fields;
    }

    /**
     * Validate query string parameter 'fields' having valid endpoint's fields
     *
     * @param array|string $fields
     *
     * @return string
     * @throws TuneSdkException
     */
    public function validateFields($fields)
    {
        if (!is_string($fields) && !is_array($fields)) {
            throw new TuneSdkException(
                "Invalid parameter 'fields' provided."
            );
        }

        if (is_string($fields)) {
            $fields = preg_replace('/\s+/', '', $fields);

            $fields = explode(",", $fields);
        }

        if (!is_array($fields) || empty($fields)) {
            throw new TuneSdkException(
                "Invalid parameter 'fields' provided."
            );
        }

        if ($this->validate_fields) {
            if (is_null($this->fields)) {
                $this->fields();
            }

            foreach ($fields as $field) {
                if (!array_key_exists($field, $this->fields)) {
                    throw new TuneSdkException(
                        "Parameter 'fields' contains an invalid field: '{$field}'."
                    );
                }
            }
        }

        return implode(",", $fields);
    }

    /**
     * Validate query string parameter 'group' having valid endpoint's fields
     *
     * @param array|string $group
     *
     * @return bool
     * @throws \Tune\Shared\TuneSdkException
     */
    public function validateGroup($group)
    {
        if (!is_string($group) && !is_array($group)) {
            throw new TuneSdkException("Invalid parameter 'group' provided.");
        }

        if (is_string($group)) {
            $group = preg_replace('/\s+/', '', $group);
            $group = explode(",", $group);
        }

        if ($this->validate_fields) {
            if (is_null($this->fields)) {
                $this->fields();
            }

            foreach ($group as $field) {
                if (!array_key_exists($field, $this->fields)) {
                    throw new TuneSdkException(
                        "Parameter 'group' contains an invalid field: '{$field}'."
                    );
                }
            }
        }

        return implode(",", $group);
    }

    /**
     * Validate query string parameter 'sort' having valid endpoint's fields and direction.
     *
     * @param array $sort
     *
     * @return bool
     * @throws \Tune\Shared\TuneSdkException
     */
    public function validateSort($sort)
    {
        if (!isAssoc($sort)) {
            throw new TuneSdkException("Invalid parameter 'sort' provided.");
        }

        if ($this->validate_fields) {
            if (is_null($this->fields)) {
                $this->fields();
            }

            foreach ($sort as $sort_field => $sort_direction) {
                if (!array_key_exists($sort_field, $this->fields)) {
                    throw new TuneSdkException(
                        "Parameter 'sort' contains an invalid field: '{$sort_field}'."
                    );
                }
                if (!in_array($sort_direction, self::$sort_directions)) {
                    throw new TuneSdkException(
                        "Parameter 'sort' contains an invalid direction: '{$sort_direction}'."
                    );
                }
            }
        }

        return $sort;
    }

    /**
     * Validate query string parameter 'filter' having valid endpoint's fields
     * and filter expressions.
     *
     * @param string $filter
     *
     * @return string|void
     */
    public function validateFilter($filter)
    {
        if (is_null($filter) || !is_string($filter)) {
            throw new TuneSdkException(
                "Invalid parameter 'filter' provided."
            );
        }

        $filter = trim(preg_replace('/\s+/', ' ', $filter));

        if (!isParenthesesBalanced($filter)) {
            throw new TuneSdkException(
                "Invalid parameter 'filter' provided. {$filter}"
            );
        }

        $filter = str_replace(array('(', ')'), " ", $filter);
        $filter = trim(preg_replace('/\s+/', ' ', $filter));

        $filter_parts = explode(" ", $filter);

        if ($this->validate_fields) {
            if (is_null($this->fields)) {
                $this->fields();
            }
        }

        foreach ($filter_parts as $filter_part) {
            $filter_part = trim($filter_part);
            if (is_string($filter_part) && empty($filter_part)) {
                continue;
            }
            if (preg_match('#^(\').+\1$#', $filter_part) == 1) {
                continue;
            }
            if (is_int($filter_part)) {
                continue;
            }
            if (in_array($filter_part, self::$filter_operations)) {
                continue;
            }
            if (in_array($filter_part, self::$filter_conjunctions)) {
                continue;
            }
            if (is_string($filter_part)
                && !empty($filter_part)
                && preg_match('/[a-z0-9\.\_]+/', $filter_part)
            ) {
                if ($this->validate_fields) {
                    if (!is_null($this->fields)
                        && is_array($this->fields)
                        && array_key_exists($filter_part, $this->fields)
                    ) {
                        continue;
                    }
                } else {
                    continue;
                }
            }

            throw new TuneSdkException("Parameter 'filter' is invalid: '{$filter}'.");
        }

        return "({$filter})";
    }

    /**
     * For debug purposes, provide string representation of this object.
     *
     * @return string
     */
    public function toString()
    {
        return sprintf("Endpoint '%s', API Key: '%s", $this->controller, $this->api_key);
    }

    /**
     * Validate that provided string is either "YYYY-MM-DD' or "YYYY-MM-DD HH:MM:SS.
     * 
     * @param $param_name
     * @param $date_time
     *
     * @return bool
     * @throws \InvalidArgumentException
     */
    public static function validateDateTime($param_name, $date_time)
    {
        $d = \DateTime::createFromFormat('Y-m-d', $date_time);
        if ($d && $d->format('Y-m-d') == $date_time) {
            return true;
        }

        $d = \DateTime::createFromFormat('Y-m-d H:i:s', $date_time);
        if ($d && $d->format('Y-m-d H:i:s') == $date_time) {
            return true;
        }

        throw new \InvalidArgumentException(
            "Parameter '{$param_name}' is invalid: '{$date_time}'."
        );
    }

    /**
     * Helper function for fetching report document given provided job identifier.
     *
     * Requesting for report url is not the same for all report endpoints.
     *
     * @param string    $export_controller      Controller for report export status.
     * @param string    $export_action          Action for report export status.
     * @param string    $job_id                     Job Identifier of report on queue.
     * @param bool      $verbose                    For debugging purposes only.
     * @param int       $sleep                      How long worker should sleep
     *                                              before next status request.
     * @return object @see TuneManagementResponse
     * @throws \InvalidArgumentException
     * @throws TuneServiceException
     */
    protected function fetchRecords(
        $export_controller,
        $export_action,
        $job_id,
        $verbose = false,
        $sleep = 60
    ) {
        if (!is_string($export_controller) || empty($export_controller)) {
            throw new \InvalidArgumentException(
                "Parameter 'export_controller' is not defined."
            );
        }
        if (!is_string($export_action) || empty($export_action)) {
            throw new \InvalidArgumentException(
                "Parameter 'export_action' is not defined."
            );
        }
        if (!is_string($job_id) || empty($job_id)) {
            throw new \InvalidArgumentException("Parameter 'job_id' is not defined.");
        }
        if (!is_string($this->api_key) || empty($this->api_key)) {
            throw new \TuneSdkException("Parameter 'api_key' is not defined.");
        }

        $export_worker = new ReportExportWorker(
            $export_controller,
            $export_action,
            $this->api_key,
            $job_id,
            $verbose,
            $sleep
        );

        if ($verbose) {
            echo "Starting worker..." . PHP_EOL;
        }
        if ($export_worker->run()) {
            if ($verbose) {
                echo "Completed worker..." . PHP_EOL;
            }
        }
        $response = $export_worker->getResponse();

        if (is_null($response)) {
            throw new TuneServiceException(
                "Report export request no response: "
                . print_r($response, true)
                . PHP_EOL
            );
        }

        $http_code = $response->getHttpCode();
        if ($http_code != 200) {
            throw new TuneServiceException(
                "Report export request error: '{$http_code}'"
            );
        }

        $data = $response->getData();
        if (is_null($data)) {
            throw new TuneServiceException(
                "Report export response failed to get data."
            );
        }
        if (!array_key_exists("status", $data)) {
            throw new TuneSdkException(
                "Export data does not contain report 'status' for download: "
                . print_r($data, true)
                . PHP_EOL
            );
        }
        if ($data["status"] == "fail"
        ) {
            throw new TuneServiceException(
                "Report export request failed: "
                . print_r($response, true)
                . PHP_EOL
            );
        }

        return $response;
    }


    /**
     * Helper function for parsing export status response to gather report job_id.
     *
     * @param $response
     *
     * @return mixed
     * @throws \InvalidArgumentException
     * @throws \Tune\Shared\TuneServiceException
     */
    public static function parseResponseReportJobId(
        $response
    ) {
        if (is_null($response)) {
            throw new \InvalidArgumentException("Parameter 'response' is not defined.");
        }

        $data = $response->getData();
        if (is_null($data)) {
            throw new TuneServiceException("Report request failed to get export data.");
        }

        $job_id = $data;

        if (!is_string($job_id) || empty($job_id)) {
            throw new \Exception(
                "Failed to return job_id: " . print_r($response, true)
            );
        }

        return $job_id;
    }

    /**
     * Helper function for parsing export status response to gather report url.
     *
     * @param $response
     *
     * @return mixed
     * @throws \InvalidArgumentException
     * @throws \Tune\Shared\TuneServiceException
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