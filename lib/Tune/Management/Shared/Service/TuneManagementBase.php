<?php
/**
 * TuneManagementBase.php
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
 * @version   0.9.4
 * @link      https://developers.mobileapptracking.com Tune Developer Community @endlink
 *
 */

namespace Tune\Management\Shared\Service;

require_once dirname(dirname(dirname(dirname(__FILE__)))) . "/Shared/Helper.php";
require_once dirname(dirname(dirname(dirname(__FILE__)))) . "/Version.php";

use \Tune\Shared\TuneSdkException;
use \Tune\Shared\TuneServiceException;

use \Tune\Shared\ParenthesesParser;
use \Tune\Management\Shared\Service\TuneManagementClient;

/**
 * Base class for handling Tune Management API endpoints.
 *
 * @package Tune\Management\Shared\Service
 */
class TuneManagementBase
{
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
    protected $validate = false;

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
            "IS NULL",
            "IS NOT NULL",
            "IN",
            "NOT IN",
            "LIKE",
            "NOT LIKE",
            "RLIKE",
            "NOT RLIKE",
            "REGEXP",
            "NOT REGEXP",
            "BETWEEN",
            "NOT BETWEEN"
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
     * @param string $controller    Tune Management API Endpoint
     * @param string $api_key       MobileAppTracking API Key
     * @param bool   $validate      Validate fields used by actions' parameters.
     */
    public function __construct(
        $controller,
        $api_key,
        $validate = false
    ) {
        if (!isCurlInstalled()) {
            throw new \Exception(
                sprint("%s %s: requires PHP Module 'curl'",
                        constant("TUNE_SDK_NAME"),
                        constant("TUNE_SDK_VERSION")
                    )
            );
        }

        // controller
        if (!is_string($controller) || empty($controller)) {
            throw new \InvalidArgumentException("Parameter 'controller' is not defined.");
        }
        // api key
        if (!is_string($api_key) || empty($api_key)) {
            throw new \InvalidArgumentException("Parameter 'api_key' is not defined.");
        }
        // validate
        if (!is_bool($validate)) {
            throw new \InvalidArgumentException("Parameter 'validate' is not defined as a boolean.");
        }

        $this->controller = $controller;
        $this->api_key = $api_key;
        $this->validate = $validate;
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
     * @param string      $action               Tune Management API endpoint's action name
     * @param null|array  $query_string_dict    Action's query string parameters
     * @return object @see Response
     * @throws TuneSdkException
     */
    protected function call(
        $action,
        $query_string_dict = null
    ) {
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
     * @return array
     * @throws TuneSdkException
     * @throws TuneServiceException
     */
    public function getFields()
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
            throw new TuneServiceException("Connection failure '{$request_url}': '{$http_code}'");
        }

        if (is_null($data) || empty($data)) {
            $controller = $this->controller;
            throw new TuneServiceException("Failed to get fields for endpoint: '{$controller}'.");
        }

        $endpoint_metadata = $data[0];
        $fields             = $endpoint_metadata["fields"];
        $this->model_name   = $endpoint_metadata["modelName"];

        $field_names = array();
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

            $field_name = $field["name"];

            $field_names[] = $field_name;
        }

        sort($field_names);

        $field_names_merged = array();

        foreach ($field_names as $field_name) {
            $field_names_merged[] = $field_name;
            if (($field_name != "_id") && endsWith($field_name, "_id")) {
                $related_property = substr($field_name, 0, strlen($field_name) - 3);
                if (array_key_exists($related_property, $related_fields)
                    && !empty($related_fields[$related_property])
                ) {
                    foreach ($related_fields[$related_property] as $related_field_name) {
                        $field_names_merged[] = "{$related_property}.{$related_field_name}";
                    }
                } else {
                    $field_names_merged[] = "{$related_property}.name";
                }
            }
        }

        $this->fields = $field_names_merged;

        return $this->fields;
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
            throw new TuneSdkException("Invalid parameter 'fields' provided.");
        }

        if (is_string($fields)) {
            $fields = preg_replace('/\s+/', '', $fields);

            $fields = explode(",", $fields);
        }

        if (!is_array($fields) || empty($fields)) {
            throw new TuneSdkException("Invalid parameter 'fields' provided.");
        }

        if ($this->validate) {
            if (is_null($this->fields)) {
                $this->getFields();
            }

            foreach ($fields as $field) {
                if (!in_array($field, $this->fields)) {
                    throw new TuneSdkException("Parameter 'fields' contains an invalid field: '{$field}'.");
                }
            }
        }

        return implode(",", $fields);
    }

    /**
     * Validate query string parameter 'group' having valid endpoint's fields
     *
     * @param array|string $fields
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

        if ($this->validate) {
            if (is_null($this->fields)) {
                $this->getFields();
            }

            foreach ($group as $field) {
                if (!in_array($field, $this->fields)) {
                    throw new TuneSdkException("Parameter 'group' contains an invalid field: '{$field}'.");
                }
            }
        }

        return implode(",", $group);
    }

    /**
     * Validate query string parameter 'sort' having valid endpoint's fields and direction.
     *
     * @param $sort
     *
     * @return bool
     * @throws \Tune\Shared\TuneSdkException
     */
    public function validateSort($sort)
    {
        if (!isAssoc($sort)) {
            throw new TuneSdkException("Invalid parameter 'sort' provided.");
        }

        if ($this->validate) {
            if (is_null($this->fields)) {
                $this->getFields();
            }

            foreach ($sort as $sort_field => $sort_direction) {
                if (!in_array($sort_field, $this->fields)) {
                    throw new TuneSdkException("Parameter 'sort' contains an invalid field: '{$sort_field}'.");
                }
                if (!in_array($sort_direction, self::$sort_directions)) {
                    throw new TuneSdkException("Parameter 'sort' contains an invalid direction: '{$sort_direction}'.");
                }
            }
        }

        return $sort;
    }

    /**
     * @param $filter
     *
     * @return string|void
     */
    public function validateFilter($filter)
    {
        if (!is_string($filter) && !is_array($filter)) {
            throw new TuneSdkException("Invalid parameter 'filter' provided.");
        }

        if ($this->validate) {
            if (is_null($this->fields)) {
                $this->getFields();
            }
        }

        if (is_array($filter)) {
            $filter = validateFilterArray($this->fields, self::$filter_operations, self::$filter_conjunctions, $filter);
        } elseif (is_string($filter)) {
            validateFilterString($this->fields, self::$filter_operations, self::$filter_conjunctions, $filter);
        }

        return $filter;
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

        throw new \InvalidArgumentException("Parameter '{$param_name}' is invalid: '{$date_time}'.");
    }
}

/**
 * Validate provided filter string is properly formatted query.
 *
 * @param array $fields
 * @param array $filter_operations
 * @param array $filter_conjunctions
 * @param array $filter
 *
 * @return string|void
 * @throws \Tune\Shared\TuneSdkException
 */
function validateFilterArray($fields, $filter_operations, $filter_conjunctions, $filter)
{
    if (!is_array($filter)) {
        throw new TuneSdkException("Parameter 'filter' expects array.");
    }

    $expression = "";

    foreach ($filter as $key => $value) {
        if (is_int($key)) {
            if (is_array($value)) {
                $sub_expression = validateFilterArray($fields, $filter_operations, $filter_conjunctions, $value);
                if (!empty($expression)) {
                    $expression = sprintf("%s %s", $expression, $sub_expression);
                } else {
                    $expression = $sub_expression;
                }
            } elseif (is_string($value)) {
                $filter_conjunction = trim($value);
                if (isEven($key)) {
                    throw new TuneSdkException("Invalid placement of conjunction in filter parameter: '{$key}'.");
                }
                if (!in_array($filter_conjunction, $filter_conjunctions)) {
                    throw new TuneSdkException("Invalid conjunction in filter parameter: '{$filter_conjunction}'.");
                }
                if (!empty($expression)) {
                    $expression = sprintf("%s %s", $expression, $filter_conjunction);
                } else {
                    $expression = $filter_conjunction;
                }
            }
        } elseif (is_string($key)) {
            if (array_key_exists("column", $filter) && array_key_exists("operator", $filter)) {
                $field = trim($filter["column"]);
                $operator = trim($filter["operator"]);
                if (!is_null($fields) && is_array($fields) && !in_array($field, $fields)) {
                    throw new TuneSdkException("Invalid field in filter parameter: '{$field}'.");
                }
                if (!in_array($operator, $filter_operations)) {
                    throw new TuneSdkException("Invalid operator in filter parameter: '{$operator}'.");
                }
                $expression = sprintf("%s %s", $field, trim($filter["operator"]));
                if (array_key_exists("value", $filter)) {
                    if (is_array($filter["value"])) {

                        $expression = sprintf("%s (%s)", $expression, implodeQuotes(",", $filter["value"]));

                    } else {
                        $expression = sprintf("%s '%s'", $expression, $filter["value"]);
                    }
                }
                break;
            }
        }
    }

    $expression = sprintf("(%s)", $expression);

    return $expression;
}

/**
 * Validate provided filter string is properly formatted query.
 *
 * @param array  $fields
 * @param array  $filter_operations
 * @param array  $filter_conjunctions
 * @param string $filter
 *
 * @return array
 * @throws \Tune\Shared\TuneSdkException
 */
function validateFilterString($fields, $filter_operations, $filter_conjunctions, $filter)
{
    $filter_expr = array();

    if (!is_string($filter)) {
        throw new TuneSdkException("Parameter 'filter' expects string.");
    }

    $pp = new ParenthesesParser();

    $filter_parts = $pp->parse($filter);

    foreach ($filter_parts as $key => $filter_part) {
        if (is_string($filter_part)) {

            $filter_conjunction = trim($filter_part);
            if (isEven($key)) {
                throw new TuneSdkException("Invalid placement of conjunction in filter parameter: '{$key}'.");
            }
            if (!in_array($filter_conjunction, $filter_conjunctions)) {
                throw new TuneSdkException("Invalid conjunction in filter parameter: '{$filter_conjunction}'.");
            }

            $filter_expr[] = $filter_conjunction;
        } elseif (is_array($filter_part)) {
            $sub_expr_part = $filter_part[0];
            $sub_filter_expr_parts = explode(" ", $sub_expr_part);

            $field = array_shift($sub_filter_expr_parts);
            if (!is_null($fields) && is_array($fields) && !in_array($field, $fields)) {
                throw new TuneSdkException("Invalid field in filter parameter: '{$field}'.");
            }
            $sub_filter_expr_part_end = trim(end($sub_filter_expr_parts));
            $sub_filter_expr_part_end_no_quotes = trim($sub_filter_expr_part_end, "'");

            $sub_filter_expr_value = null;
            if ($sub_filter_expr_part_end_no_quotes != $sub_filter_expr_part_end) {
                $sub_filter_expr_value = array_pop($sub_filter_expr_parts);
            }
            $sub_filter_expr_operator = implode(" ", $sub_filter_expr_parts);
            $sub_filter_expr_operator = trim($sub_filter_expr_operator);
            if (!in_array($sub_filter_expr_operator, $filter_operations)) {
                throw new TuneSdkException("Invalid operator in filter parameter '{$filter}': '{$sub_filter_expr_operator}'.");
            }

            if (count($filter_part) == 2) {
                $sub_filter_expr_value =  $filter_part[1];
            }

            $sub_filter = array (
                "column" => $field,
                "operator" => $sub_filter_expr_operator
            );

            if ($sub_filter_expr_value) {
                $sub_filter["value"] = $sub_filter_expr_part_end;
            }

            $filter_expr[] = $sub_filter;
        }
    }

    return $filter_expr;
}
