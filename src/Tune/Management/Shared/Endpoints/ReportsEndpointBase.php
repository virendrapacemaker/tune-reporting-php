<?php
/**
 * ReportsEndpointBase.php
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

use Tune\Management\Shared\Endpoints\EndpointBase;
use Tune\Shared\TuneSdkException;
use Tune\Shared\TuneServiceException;
use Tune\Shared\ReportExportWorker;

/**
 * Base class for handling all endpoints that pertain to reports.
 *
 * @package Tune\Management\Shared\Endpoints
 */
class ReportsEndpointBase extends EndpointBase
{
    /**
     * Remove debug mode information from results.
     * @var bool
     */
    protected $filter_debug_mode = false;
    /**
     * Remove test profile information from results.
     * @var bool
     */
    protected $filter_test_profile_id = false;

    /**
     * Constructor
     *
     * @param string $controller                Tune Management API endpoint name.
     * @param string $api_key                   MobileAppTracking API Key.
     * @param bool   $filter_debug_mode         Remove debug mode information from results.
     * @param bool   $filter_test_profile_id    Remove test profile information from results.
     * @param bool   $validate_fields                  Validate fields used by actions' parameters.
     */
    public function __construct(
        $controller,
        $api_key,
        $filter_debug_mode,
        $filter_test_profile_id,
        $validate_fields = false
    ) {
        // controller
        if (!is_string($controller) || empty($controller)) {
            throw new \InvalidArgumentException(
                "Parameter 'controller' is not defined."
            );
        }
        // api_key
        if (!is_string($api_key) || empty($api_key)) {
            throw new \InvalidArgumentException(
                "Parameter 'api_key' is not defined."
            );
        }
        // filter_debug_mode
        if (!is_bool($validate_fields)) {
            throw new \InvalidArgumentException(
                "Parameter 'validate' is not defined as a bool."
            );
        }
        // filter_debug_mode
        if (!is_bool($filter_debug_mode)) {
            throw new \InvalidArgumentException(
                "Parameter 'filter_debug_mode' is not defined as a bool."
            );
        }
        // filter_test_profile_id
        if (!is_bool($filter_test_profile_id)) {
            throw new \InvalidArgumentException(
                "Parameter 'filter_test_profile_id' is not defined as a bool."
            );
        }

        $this->filter_debug_mode = $filter_debug_mode;
        $this->filter_test_profile_id = $filter_test_profile_id;

        parent::__construct($controller, $api_key, $validate_fields);
    }

    /**
     * Prepare action with provided query string parameters, then call
     * Management API service.
     *
     * @param string    $action Endpoint action to be called.
     * @param dict      $query_string_dict Query string parameters for this action.
     *
     * @return object @see Response
     * @throws \InvalidArgumentException
     */
    protected function callRecords(
        $action,
        $query_string_dict
    ) {
        // action
        if (!is_string($action) || empty($action)) {
            throw new \InvalidArgumentException("Parameter 'action' is not defined.");
        }
        if (is_null($query_string_dict) || !is_array($query_string_dict)) {
            throw new \InvalidArgumentException(
                "Parameter 'query_string_dict' is not defined as associative array."
            );
        }

        $sdk_filter = "";

        if ($this->filter_debug_mode) {
            $sdk_filter = "(debug_mode=0 OR debug_mode is NULL)";
        }
        
        if ($this->filter_test_profile_id) {
            if (!is_null($sdk_filter)
                && is_string($sdk_filter)
                && !empty($sdk_filter)
            ) {
                $sdk_filter .= " AND ";
            }

            $sdk_filter .= "(test_profile_id=0 OR test_profile_id IS NULL)";
        }

        if (!empty($sdk_filter)) {
            if (array_key_exists('filter', $query_string_dict)) {
                if (!is_null($query_string_dict['filter'])
                    && is_string($query_string_dict['filter'])
                    && !empty($query_string_dict['filter'])) {
                    $query_string_dict['filter'] =
                        "(" . $query_string_dict['filter'] . ") AND " . $sdk_filter;
                } else {
                    $query_string_dict['filter'] = $sdk_filter;
                }
            } else {
                $query_string_dict['filter'] = $sdk_filter;
            }
        }

        if (array_key_exists('filter', $query_string_dict)) {
            if (!is_null($query_string_dict['filter'])
                && is_string($query_string_dict['filter'])
                && !empty($query_string_dict['filter'])) {
                $query_string_dict['filter'] = "(" . $query_string_dict['filter'] . ")";
            }
        }

        return parent::call($action, $query_string_dict);
    }
}
