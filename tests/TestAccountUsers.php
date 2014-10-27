<?php
/**
 * TestAccountUsers.php, Tune SDK PHPUnit Test
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

require_once dirname(__FILE__) . "/../src/TuneApi.php";

use \Tune\Management\Api\Account\Users;

class TestAccountUsers extends \PHPUnit_Framework_TestCase
{
    /**
     * @ignore
     */
    protected $api_key = null;

    /**
     * Get API Key from environment.
     */
    protected function setUp()
    {
        $this->api_key = getenv('API_KEY');
        $this->assertNotNull($this->api_key);
        $this->assertInternalType('string', $this->api_key);
        $this->assertNotEmpty($this->api_key);
    }

    /**
     * Test /account/users/count
     */
    public function testCount()
    {
        $response = null;
        try {
            $account_users = new Users($this->api_key, $validate_fields = true);

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

        } catch ( Exception $ex ) {
            $this->fail($ex->getMessage());
        }

        $this->assertNotNull($response);
        $this->assertEquals(200, $response->getHttpCode());
    }

    /**
     * Test /account/users/find
     */
    public function testFind()
    {
        $response = null;
        try {
            $account_users = new \Tune\Management\Api\Account\Users($this->api_key, $validate_fields = false);

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
            $response = $account_users->find(
                $fields      = null,
                $filter      = $filter_array,
                $limit       = 5,
                $limit       = null,
                $sort        = array (
                    'first_name' => 'ASC',
                    'last_name' => 'ASC'
                )
            );

        } catch ( Exception $ex ) {
            $this->fail($ex->getMessage());
        }

        $this->assertNotNull($response);
        $this->assertEquals(200, $response->getHttpCode());
    }
}
