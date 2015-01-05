<?php
/**
 * TestTuneManagementClient.php, TUNE Reporting SDK PHPUnit Test
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
 * @category  TUNE_Reporting
 *
 * @author    Jeff Tanner <jefft@tune.com>
 * @copyright 2015 TUNE, Inc. (http://www.tune.com)
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version   $Date: 2015-01-05 14:24:08 $
 * @link      https://developers.mobileapptracking.com/tune-reporting-sdks @endlink
 *
 */

require_once dirname(__FILE__) . "/../src/TuneReporting.php";

use TuneReporting\Api\SessionAuthenticate;
use TuneReporting\Base\Service\TuneManagementClient;
use TuneReporting\Helpers\SdkConfig;

/**
 * Unittest basic functionality of TuneManagementClient
 */
class TestTuneManagementClient extends \PHPUnit_Framework_TestCase
{
    /**
     * @ignore
     */
    protected $api_key = null;

    /**
     * @ignore
     */
    protected $session_token = null;

    /**
     * Get API Key from environment.
     */
    protected function setUp()
    {
        $api_key = getenv('API_KEY');
        $this->assertNotNull($api_key);

        $this->api_key = $api_key;

        $session_authenticate = new SessionAuthenticate();
        $response = $session_authenticate->api_key($api_key);
        $this->assertNotNull($response);
        $session_token = $response->getData();
        $this->assertNotNull($session_token);

        $this->session_token = $session_token;
    }

    /**
     * Test TUNE Reporting API client
     */
    public function testFind()
    {
        $response = null;
        try {
            $client = new TuneManagementClient(
                $controller = 'account/users',
                $action = 'find.json',
                $this->api_key,
                "api_key",
                $query_string_dict = array(
                    "fields" => "first_name,last_name,email",
                    "limit" => 5
                )
            );

            $client->call();

            $response = $client->getResponse();

        } catch (Exception $ex ) {
            $this->fail($ex->getMessage());
        }

        $this->assertNotNull($response);
        $this->assertEquals(200, $response->getHttpCode());
    }

    /**
     * Test TUNE Reporting API client
     */
    public function testFindSessionToken()
    {
        $response = null;
        try {
            $client = new TuneManagementClient(
                $controller = 'account/users',
                $action = 'find.json',
                $this->session_token,
                "session_token",
                $query_string_dict = array(
                    "fields" => "first_name,last_name,email",
                    "limit" => 5
                )
            );

            $client->call();

            $response = $client->getResponse();

        } catch (Exception $ex ) {
            $this->fail($ex->getMessage());
        }

        $this->assertNotNull($response);
        $this->assertEquals(200, $response->getHttpCode());
    }
}
