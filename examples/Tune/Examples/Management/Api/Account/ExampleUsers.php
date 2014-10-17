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

namespace Tune\Examples\Management\Api\Account;

use Tune\Management\Api\Account\Users;

/**
 * Class ExampleUsers
 *
 * @package Tune\Examples\Management\Api\Account
 */
class ExampleUsers
{
    /**
     * Constructor that prevents a default instance of this class from being created.
     */
    private function __construct()
    {

    }

    /**
     * @param string $api_key MobileAppTracking API Key
     *
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    public static function run(
        $api_key
    ) {
        // api_key
        if (!is_string($api_key) || empty($api_key)) {
            throw new \InvalidArgumentException("Parameter 'api_key' is not defined.");
        }

        print(  "=============================================" . PHP_EOL);
        print(  "= Tune Management API Account/Users Example =" . PHP_EOL);
        print(  "=============================================" . PHP_EOL);

        try {
            $account_users = new Users($api_key, $validate = true);

            echo "======================================================" . PHP_EOL;
            echo "= account/users all fields =" . PHP_EOL;
            $response = $account_users->getFields();
            echo print_r($response, true) . PHP_EOL;

            echo "======================================================" . PHP_EOL;
            echo "= account/users/count.json request =" . PHP_EOL;

            $filter_array = array(
                array(
                    "column" => "first_name",
                    "operator" => "LIKE",
                    "value" => "%a%"
                ),
                "AND",
                array(
                    "column" => "phone",
                    "operator" => "IS NOT NULL"
                )
            );
            $response = $account_users->count($filter_array);

            echo "= account/users/count.json response:" . PHP_EOL;

            echo "Count: " . print_r($response->getData(), true) . PHP_EOL;

            echo "======================================================" . PHP_EOL;
            echo "= account/users/find.json request =" . PHP_EOL;

            $filter_str = "((first_name LIKE '%a%') AND (phone IS NOT NULL))";

            $response = $account_users->find(
                $fields      = null,
                $filter      = $filter_str,
                $limit       = 5,
                $limit       = null,
                $sort        = array (
                    'first_name' => 'ASC',
                    'last_name' => 'DESC'
                )
            );

            echo "= account/users/find.json response:" . PHP_EOL;
            echo print_r($response, true) . PHP_EOL;

            echo "Find: " . print_r($response->getData(), true) . PHP_EOL;

        } catch (Exception $ex) {
            echo 'Caught exception: ',  $ex->getMessage(), "\n";
        }
    }
}
