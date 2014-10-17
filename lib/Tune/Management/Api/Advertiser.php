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
 * @package   Tune_PHP_SDK
 * @author    Jeff Tanner <jefft@tune.com>
 * @copyright 2014 Tune (http://www.tune.com)
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version   0.9.2
 * @link      https://developers.mobileapptracking.com Tune Developer Community @endlink
 *
 */

namespace Tune\Management\Api;

use Tune\Management\Shared\Service\TuneManagementBase;

/**
 * Tune Management API endpoint '/account/'
 *
 * @package Tune\Management\Api
 */
class Advertiser extends TuneManagementBase
{
    /**
     * Constructor
     *
     * @param string $api_key       MobileAppTracking API Key
     * @param bool   $validate      Validate fields used by actions' parameters.
     */
    public function __construct(
        $api_key,
        $validate = false
    ) {
        parent::__contruct(
            "advertiser",
            $api_key,
            $validate
        );
    }

    /**
     * Counts all existing records that match filter criteria
     * and returns an array of found model data.
     *
     * @param string $filter                Filter the results and apply conditions
     *                                      that must be met for records to be
     *                                      included in data.
     *
     * @return object
     */
    public function count(
        $filter = null
    ) {
        $query_string_dict = [];
        $query_string_dict['filter'] = $filter;

        return parent::call(
            "count",
            $query_string_dict
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
        $fields = null,
        $filter = null,
        $limit = null,
        $page = null,
        $sort = null
    ) {
        $query_string_dict = [];
        $query_string_dict['fields'] = $fields;
        $query_string_dict['filter'] = $filter;
        $query_string_dict['limit'] = $limit;
        $query_string_dict['page'] = $page;
        $query_string_dict['sort'] = $sort;

        return parent::call(
            "find",
            $query_string_dict
        );
    }
}
