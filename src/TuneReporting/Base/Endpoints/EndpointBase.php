<?php
/**
 * EndpointBase.php, Abstract class for defining TUNE MobileAppTracking Service actions.
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

namespace TuneReporting\Base\Endpoints;

require_once dirname(dirname(dirname(__FILE__))) . "/Helpers/String.php";
require_once dirname(dirname(dirname(__FILE__))) . "/Helpers/Utils.php";
require_once dirname(dirname(dirname(__FILE__))) . "/Version.php";

use TuneReporting\Base\Service\TuneServiceClient;
use TuneReporting\Base\Endpoints\ReportExportWorker;
use TuneReporting\Helpers\TuneSdkException;
use TuneReporting\Helpers\TuneServiceException;
use TuneReporting\Helpers\SdkConfig;

/**
 * Base class for handling TUNE Reporting API endpoints.
 */
class EndpointBase
{
    const TUNE_FIELDS_UNDEFINED       = 0;
    const TUNE_FIELDS_ALL             = 1;
    const TUNE_FIELDS_ENDPOINT        = 2;
    const TUNE_FIELDS_DEFAULT         = 4;
    const TUNE_FIELDS_RELATED         = 8;
    const TUNE_FIELDS_MINIMAL         = 16;
    const TUNE_FIELDS_RECOMMENDED     = 32;

    /**
     * TUNE Reporting API Endpoint
     * @var string
     */
    protected $controller = null;

    /**
     * TUNE Reporting authentication key.
     * @var string
     */
    protected $auth_key = null;

    /**
     * TUNE Reporting authentication type.
     * @var string
     */
    protected $auth_type = null;

    /**
     * TUNE Reporting API Endpoint's fields
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
     * Configuration settings.
     */
    protected $sdk_config = null;

    /**
     * Constructor
     *
     * @param string $controller        TUNE Reporting API Endpoint
     */
    public function __construct(
        $controller,
        $use_config = true
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

        if ($use_config) {
            $this->sdk_config = SdkConfig::getInstance();
            if (is_null($this->sdk_config)) {
                throw new TuneSdkException(
                    "SdkConfig is not defined."
                );
            }
        }

        // controller
        if (!is_string($controller) || empty($controller)) {
            throw new \InvalidArgumentException(
                "Parameter 'controller' is not defined."
            );
        }

        if ($use_config) {
            // Get TUNE Reporting authentication key.
            $auth_key = $this->sdk_config->getAuthKey();
            if (!is_string($auth_key) || empty($auth_key) || ('UNDEFINED' == $auth_key)) {
                throw new \InvalidArgumentException(
                    "Parameter 'auth_key' is not defined: '{$auth_key}'"
                );
            }

            // Get TUNE Reporting authentication type.
            $auth_type = $this->sdk_config->getAuthType();
            if (!is_string($auth_type) || empty($auth_type)) {
                throw new \InvalidArgumentException(
                    "Parameter 'auth_type' is not defined: '{$auth_type}'"
                );
            }

            // validate_fields
            $validate_fields = $this->sdk_config->getValidateFields();
        } else {
            $auth_key = null;
            $auth_type = null;
            $validate_fields = false;
        }

        $this->controller = $controller;
        $this->auth_key = $auth_key;
        $this->auth_type = $auth_type;
        $this->validate_fields = $validate_fields;
    }

    /**
     * Get SDK configuration settings.
     *
     * @return object @see SdkConfig
     */
    public function getSdkConfig()
    {
        return $this->sdk_config;
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
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Call TUNE Reporting API service for this controller.
     *
     * @param string        $action                 TUNE Reporting API endpoint's
     *                                            action name
     * @param null|array    $map_query_string    Action's query string
     *                                            parameters
     * @return object @see TuneServiceResponse
     * @throws TuneSdkException
     */
    protected function call(
        $action,
        $map_query_string = null
    ) {
        // controller
        if (!is_string($action) || empty($action)) {
            throw new \InvalidArgumentException(
                "Parameter 'action' is not defined."
            );
        }

        $client = new TuneServiceClient(
            $this->controller,
            $action,
            $this->auth_key,
            $this->auth_type,
            $map_query_string
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
    public function getFields(
        $enum_fields_selection = self::TUNE_FIELDS_DEFAULT
    ) {
        if (is_null($this->fields) || empty($fields)) {
            $this->getEndpointFields();
        }

        if (($enum_fields_selection & self::TUNE_FIELDS_ALL) ||
            (!($enum_fields_selection & self::TUNE_FIELDS_DEFAULT)
            && ($enum_fields_selection & self::TUNE_FIELDS_RELATED))
        ) {
            return array_keys($this->fields);
        }

        if ($enum_fields_selection & self::TUNE_FIELDS_RECOMMENDED) {

            $fields = $this->fields_recommended;

            if (is_null($fields) || empty($fields)) {
                $fields = $this->getFields(self::TUNE_FIELDS_DEFAULT);
            }

            if (is_null($fields) || empty($fields)) {
                $fields = $this->getFields(self::TUNE_FIELDS_ENDPOINT);
            }

            if (is_null($fields) || empty($fields)) {
                throw new TuneSdkException("No fields found for TUNE_FIELDS_RECOMMENDED.");
            }

            return $fields;
        }

        $fields_filtered = array();
        foreach ($this->fields as $field_name => $field_info) {

            if ((($enum_fields_selection & self::TUNE_FIELDS_ENDPOINT)
                 || !($enum_fields_selection & self::TUNE_FIELDS_DEFAULT))
                && !$field_info["related"]
            ) {
                $fields_filtered[$field_name] = $field_info;
                continue;
            }

            if (!($enum_fields_selection & self::TUNE_FIELDS_RELATED)
                && !($enum_fields_selection & self::TUNE_FIELDS_MINIMAL)
                && $field_info["related"]
                ) {
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

        $fields = array_keys($fields_filtered);

        // Provide all immediate fields for this endpoint if
        // requested default fields but none were found.
        if (($enum_fields_selection & self::TUNE_FIELDS_DEFAULT)
            && (is_null($fields) || empty($fields))
        ) {
            $fields = $this->getFields(self::TUNE_FIELDS_RECOMMENDED);

            if (is_null($fields) || empty($fields)) {
                $fields = $this->getFields(self::TUNE_FIELDS_ENDPOINT);
            }

            if (is_null($fields) || empty($fields)) {
                throw new TuneSdkException("No fields found for TUNE_FIELDS_DEFAULT.");
            }
        }

        return $fields;
    }

    /**
     * Get model name for this endpoint.
     *
     * @return string
     */
    public function getModelName()
    {
        if (is_null($this->fields)) {
            $this->getFields();
        }

        return $this->model_name;
    }

    /**
     * Fetch all fields from model and related models of this endpoint.
     */
    protected function getEndpointFields()
    {
        $map_query_string = array(
            "controllers" => $this->controller,
            "details" => "modelName,fields"
        );

        $client = new TuneServiceClient(
            "apidoc",
            "get_controllers",
            $this->auth_key,
            $this->auth_type,
            $map_query_string
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
        $this->model_name     = $endpoint_metadata["modelName"];

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
     * @param dict $map_params
     * @param dict $map_query_string
     *
     * @return dict
     * @throws TuneSdkException
     */
    public function validateFields($map_params, $map_query_string)
    {
        if (!array_key_exists('fields', $map_params)
        ) {
            throw new \InvalidArgumentException("Parameter 'fields' is not defined.");
        }

        $fields = $map_params['fields'];

        if (!is_string($fields) && !is_array($fields)) {
            throw new TuneSdkException(
                "Invalid parameter 'fields' provided: '{$fields}'"
            );
        }

        if (is_string($fields)) {
            $fields = preg_replace('/\s+/', '', $fields);
            $array_fields = explode(",", $fields);
        } else {
            $array_fields = $fields;
        }

        if ($this->validate_fields) {
            if (is_null($this->fields)) {
                $this->getFields();
            }

            foreach ($array_fields as $field) {
                if (!array_key_exists($field, $this->fields)) {
                    throw new TuneSdkException(
                        "Parameter 'fields' contains an invalid field: '{$field}'."
                    );
                }
            }
        }

        $str_fields = implode(",", $array_fields);
        $map_query_string['fields'] = $str_fields;

        return $map_query_string;
    }

    /**
     * Validate query string parameter 'group' having valid endpoint's fields
     *
     * @param dict $map_params
     * @param dict $map_query_string
     *
     * @return dict
     * @throws TuneSdkException
     */
    public function validateGroup($map_params, $map_query_string)
    {
        if (!is_array($map_params)) {
            throw new TuneSdkException(__METHOD__ . ": Invalid parameter 'map_params' provided.");
        }
        if (!is_array($map_query_string)) {
            throw new TuneSdkException(__METHOD__ . ": Invalid parameter 'map_query_string' provided.");
        }

        if (!array_key_exists('group', $map_params)
        ) {
            throw new \InvalidArgumentException("Parameter 'group' is not defined.");
        }

        $group = $map_params['group'];

        if (!is_string($group) && !is_array($group)) {
            throw new TuneSdkException("Invalid parameter 'group' provided.");
        }

        if (is_string($group)) {
            $group = preg_replace('/\s+/', '', $group);
            $group = explode(",", $group);
        }

        if ($this->validate_fields) {
            if (is_null($this->fields)) {
                $this->getFields();
            }

            foreach ($group as $field) {
                if (!array_key_exists($field, $this->fields)) {
                    throw new TuneSdkException(
                        "Parameter 'group' contains an invalid field: '{$field}'."
                    );
                }
            }
        }

        $map_query_string['group'] = implode(",", $group);
        return $map_query_string;
    }

    /**
     * Validate query string parameter 'sort' having valid endpoint's fields and direction.
     *
     * @param dict $map_params
     * @param dict $map_query_string
     *
     * @return dict
     * @throws TuneSdkException
     */
    public function validateSort($map_params, $map_query_string)
    {
        if (!array_key_exists('sort', $map_params)
        ) {
            throw new \InvalidArgumentException("Parameter 'sort' is not defined.");
        }

        $sort = $map_params['sort'];

        if (!isAssoc($sort)) {
            throw new TuneSdkException("Invalid parameter 'sort' provided.");
        }

        if ($this->validate_fields) {
            if (is_null($this->fields)) {
                $this->getFields();
            }
        }

        foreach ($sort as $sort_field => $sort_direction) {
            $sort_field = trim($sort_field);
            $sort_direction = strtoupper(trim($sort_direction));

            if ($this->validate_fields) {
                if (!array_key_exists($sort_field, $this->fields)) {
                    throw new TuneSdkException(
                        "Parameter 'sort' contains an invalid field: '{$sort_field}'."
                    );
                }
            }

            if (!in_array($sort_direction, self::$sort_directions)) {
                throw new TuneSdkException(
                    "Parameter 'sort' contains an invalid direction: '{$sort_direction}'."
                );
            }
        }

        $map_query_string['sort'] = $sort;

        return $map_query_string;
    }

    /**
     * Validate query string parameter 'filter' having valid endpoint's fields
     * and filter expressions.
     *
     * @param dict $map_params
     * @param dict $map_query_string
     *
     * @return dict
     * @throws TuneSdkException
     */
    public function validateFilter($map_params, $map_query_string)
    {
        if (!array_key_exists('filter', $map_params)
        ) {
            throw new \InvalidArgumentException("Parameter 'filter' is not defined.");
        }

        $filter = $map_params['filter'];

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
                $this->getFields();
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

        $map_query_string['filter'] = "({$filter})";
        return $map_query_string;
    }

    /**
     * Validate query string parameter 'filter' having valid endpoint's fields
     * and filter expressions.
     *
     * @param dict $map_params
     * @param dict $map_query_string
     *
     * @return dict
     * @throws TuneSdkException
     */
    public static function validateResponseTimezone($map_params, $map_query_string)
    {
        if (!array_key_exists('response_timezone', $map_params)
        ) {
            throw new \InvalidArgumentException("Parameter 'response_timezone' is not defined.");
        }

        $response_timezone = $map_params['response_timezone'];

        if (is_null($response_timezone) || !is_string($response_timezone)) {
            throw new TuneSdkException(
                "Invalid parameter 'response_timezone' provided."
            );
        }

        $map_query_string['response_timezone'] = $response_timezone;
        return $map_query_string;
    }

    /**
     * Validate query string parameter 'filter' having valid endpoint's fields
     * and filter expressions.
     *
     * @param dict $map_params
     * @param dict $map_query_string
     *
     * @return dict
     * @throws InvalidArgumentException
     * @throws TuneSdkException
     */
    public static function validateFormat($map_params, $map_query_string)
    {
        if (!array_key_exists('format', $map_params)
        ) {
            throw new \InvalidArgumentException("Parameter 'format' is not defined.");
        }

        $format = $map_params['format'];

        if (is_null($format) || !is_string($format)) {
            throw new TuneSdkException(
                "Invalid parameter 'format' provided."
            );
        }

        if (!in_array($format, self::$report_export_formats)) {
            throw new \InvalidArgumentException("Parameter 'format' is invalid: '{$format}'.");
        }

        $map_query_string['format'] = $format;
        return $map_query_string;
    }

    /**
     * For debug purposes, provide string representation of this object.
     *
     * @return string
     */
    public function toString()
    {
        return sprintf("Endpoint '%s', API Key: '%s", $this->controller, $this->auth_key);
    }

    /**
     * Validate that provided string is either "YYYY-MM-DD' or "YYYY-MM-DD HH:MM:SS.
     *
     * @param dict     $map_params
     * @param string    $param_name
     * @param dict     $map_query_string
     *
     * @return dict
     * @throws InvalidArgumentException
     * @throws TuneSdkException
     */
    public static function validateDateTime($map_params, $param_name, $map_query_string)
    {
        if (!is_array($map_params)) {
            throw new TuneSdkException(__METHOD__ . ": Invalid parameter 'map_params' provided.");
        }
        if (!is_array($map_query_string)) {
            throw new TuneSdkException(__METHOD__ . ": Invalid parameter 'map_query_string' provided.");
        }
        if (is_null($param_name) || !is_string($param_name) || empty($param_name)
        ) {
            throw new \InvalidArgumentException("Parameter 'param_name' is not defined.");
        }

        if (!array_key_exists($param_name, $map_params)
        ) {
            throw new \InvalidArgumentException("Parameter '$param_name' is not defined.");
        }

        $date_time = $map_params[$param_name];

        if (is_null($date_time) || !is_string($date_time) || empty($date_time)
        ) {
            throw new \InvalidArgumentException("Parameter '{$param_name}' is not defined.");
        }

        $is_date_time_valid = false;

        if (!$is_date_time_valid) {
            $d = \DateTime::createFromFormat('Y-m-d', $date_time);
            if ($d && $d->format('Y-m-d') == $date_time) {
                $is_date_time_valid = true;
            }
        }

        if (!$is_date_time_valid) {
            $d = \DateTime::createFromFormat('Y-m-d H:i:s', $date_time);
            if ($d && $d->format('Y-m-d H:i:s') == $date_time) {
                $is_date_time_valid = true;
            }
        }

        if (!$is_date_time_valid) {
            throw new \InvalidArgumentException(
                "Parameter '{$param_name}' is invalid: '{$date_time}'."
            );
        }

        $map_query_string[$param_name] = $date_time;
        return $map_query_string;
    }

    /**
     * Validate query string parameter 'limit'.
     *
     * @param dict     $map_params
     * @param dict     $map_query_string
     *
     * @return dict
     * @throws InvalidArgumentException
     * @throws TuneSdkException
     */
    public static function validateLimit($map_params, $map_query_string)
    {
        if (!array_key_exists('limit', $map_params)
        ) {
            throw new \InvalidArgumentException("Parameter 'limit' is not defined.");
        }

        $limit = $map_params['limit'];

        if (is_null($limit)) {
            $limit = 0;
        }

        if (!is_integer($limit)) {
            throw new \InvalidArgumentException("Parameter 'limit' is invalid: '{$limit}'.");
        }

        $map_query_string['limit'] = $limit;
        return $map_query_string;
    }

    /**
     * Validate query string parameter 'limit'.
     *
     * @param dict     $map_params
     * @param dict     $map_query_string
     *
     * @return dict
     * @throws InvalidArgumentException
     * @throws TuneSdkException
     */
    public static function validatePage($map_params, $map_query_string)
    {
        if (!array_key_exists('page', $map_params)
        ) {
            throw new \InvalidArgumentException("Parameter 'page' is not defined.");
        }

        $page = $map_params['page'];

        if (is_null($page)) {
            $page = 0;
        }

        if (!is_integer($page)) {
            throw new \InvalidArgumentException("Parameter 'page' is invalid: '{$page}'.");
        }

        $map_query_string['page'] = $page;
        return $map_query_string;
    }

    /**
     * Helper function for fetching report document given provided job identifier.
     *
     * Requesting for report url is not the same for all report endpoints.
     *
     * @param string    $export_controller        Controller for report export status.
     * @param string    $export_action            Action for report export status.
     * @param string    $job_id                 Job Identifier of report on queue.
     * @param bool        $verbose                For debugging purposes only.
     *
     * @return object @see TuneServiceResponse
     * @throws InvalidArgumentException
     * @throws TuneServiceException
     */
    protected function fetchRecords(
        $export_controller,
        $export_action,
        $job_id,
        $verbose = false
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
        if (!is_string($this->auth_key) || empty($this->auth_key)) {
            throw new TuneSdkException("Parameter 'auth_key' is not defined.");
        }

        $status_sleep = $this->sdk_config->getStatusSleep();
        $status_timeout = $this->sdk_config->getStatusTimeout();

        $export_worker = new ReportExportWorker(
            $export_controller,
            $export_action,
            $this->auth_key,
            $this->auth_type,
            $job_id,
            $verbose,
            $status_sleep,
            $status_timeout
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
     * @throws InvalidArgumentException
     * @throws TuneServiceException
     */
    public static function parseResponseReportJobId(
        $response
    ) {
        if (is_null($response)) {
            throw new \InvalidArgumentException("Parameter 'response' is not defined.");
        }

        $data = $response->getData();
        if (is_null($data)) {
            throw new TuneServiceException(
                "Report request failed to get export data, response: " . print_r($response, true)
            );
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
     * @throws InvalidArgumentException
     * @throws TuneServiceException
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
            throw new TuneServiceException(
                "Export response does not contain 'data': " . print_r($data, true) . PHP_EOL
            );
        }
        if (is_null($data["data"])) {
            throw new TuneServiceException(
                "Export data response does not contain 'data': " . print_r($data, true) . PHP_EOL
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
