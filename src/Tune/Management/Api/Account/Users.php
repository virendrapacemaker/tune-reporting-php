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
 * @version   0.9.7
 * @link      https://developers.mobileapptracking.com Tune Developer Community @endlink
 *
 */

namespace Tune\Management\Api\Account;

use Tune\Management\Shared\Service\TuneManagementBase;

/**
 * Class Users
 *
 * @package Tune\Management\Api\Account
 *
 * @example ExampleUsers.php
 * @example UnittestUsers.php
 *
 * @code
 *  $account_users = new Users($api_key, $validate_fields = true);
 *
 * @endcode
 */
class Users extends TuneManagementBase
{
    /**
     * @param $api_key
     */
    public function __construct(
        $api_key,
        $validate_fields = false
    ) {
        parent::__construct(
            "account/users",
            $api_key,
            $validate_fields
        );
    }

    /**
     * Count users with current account based upon provided constraints.
     *
     * @code
     *   $filter_array = array(
     *        array(
     *            "column" => "first_name",
     *            "operator" => "LIKE",
     *            "value" => "%a%"
     *        ),
     *        "AND",
     *        array(
     *            "column" => "phone",
     *            "operator" => "IS NOT NULL"
     *        )
     *   );
     *   $response = $account_users->count($filter_array);
     * @endcode
     *
     * @param null|string|array      $filter
     *
     */
    public function count(
        $filter = null
    ) {
        if (!is_null($filter)) {
            $filter = $this->validateFilter($filter);
        }

        return parent::call(
            "count",
            $query_string_dict = array (
                'filter' => $filter
            )
        );
    }

    /**
     * Find users with current account based upon provided constraints.
     *
     * @param null|string|array      $fields
     * @param null|string|array      $filter
     * @param null|int               $limit
     * @param null|int               $page
     * @param null|array             $sort
     */
    public function find(
        $fields = null,
        $filter = null,
        $limit = null,
        $page = null,
        $sort = null
    ) {
        if (!is_null($fields)) {
            $fields = $this->validateFields($fields);
        }
        if (!is_null($filter)) {
            $filter = $this->validateFilter($filter);
        }
        if (!is_null($sort)) {
            $sort = $this->validateSort($sort);
        }

        return parent::call(
            "find",
            $query_string_dict = array (
                'fields' => $fields,
                'filter' => $filter,
                'limit' => $limit,
                'page' => $page,
                'sort' => $sort
            )
        );
    }
}
