<?php
/**
 * UnittestInstalls.php, Tune SDK PHPUnit Test
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
 * @version   0.9.6
 * @link      https://developers.mobileapptracking.com Tune Developer Community @endlink
 *
 */

require_once dirname(__FILE__) . "/../src/TuneApi.php";

use \Tune\Management\Api\Advertiser\Stats\Installs;

class UnittestInstalls extends \PHPUnit_Framework_TestCase
{
    /**
     * @ignore
     */
    protected $api_key = null;
    protected $endpoint = null;

    /**
     * Get API Key from environment.
     */
    protected function setUp()
    {
        $this->api_key = getenv('API_KEY');
    }

    /**
     * Test count
     */
    public function testCount()
    {
        $yesterday      = date('Y-m-d', strtotime("-1 days"));
        $start_date     = "{$yesterday} 00:00:00";
        $end_date       = "{$yesterday} 23:59:59";

        $installs = new Installs($this->api_key, $validate = true);

        $response = $installs->getFields();
        $this->assertNotNull($response);

        $response = $installs->count(
            $start_date,
            $end_date,
            $filter              = "(status = 'approved') AND (publisher_id > 0)",
            $response_timezone   = "America/Los_Angeles"
        );

        $this->assertNotNull($response);
        $this->assertEquals(200, $response->getHttpCode());
    }

    /**
     * Test find
     */
    public function testFind()
    {
        $yesterday      = date('Y-m-d', strtotime("-1 days"));
        $start_date     = "{$yesterday} 00:00:00";
        $end_date       = "{$yesterday} 23:59:59";

        $installs = new Installs($this->api_key, $validate = true);

        $response = $installs->find(
            $start_date,
            $end_date,
                $filter              = "(status = 'approved')",
                $fields              = "id"
                . ",created"
                . ",status"
                . ",site_id"
                . ",site.name"
                . ",publisher_id"
                . ",publisher.name"
                . ",advertiser_ref_id"
                . ",advertiser_sub_campaign_id"
                . ",advertiser_sub_campaign.ref"
                . ",publisher_sub_campaign_id"
                . ",publisher_sub_campaign.ref"
                . ",user_id"
                . ",device_id"
                . ",os_id"
                . ",google_aid"
                . ",ios_ifa"
                . ",ios_ifv"
                . ",windows_aid"
                . ",referral_url"
                . ",is_view_through",
            $limit               = 5,
            $page                = null,
            $sort                = array("created" => "DESC"),
            $response_timezone   = "America/Los_Angeles"
        );

        $this->assertNotNull($response);
        $this->assertEquals(200, $response->getHttpCode());
    }

    public function testExport()
    {
        $yesterday      = date('Y-m-d', strtotime("-1 days"));
        $start_date     = "{$yesterday} 00:00:00";
        $end_date       = "{$yesterday} 23:59:59";

        $installs = new Installs($this->api_key, $validate = true);

        $response = $installs->export(
            $start_date,
            $end_date,
                $filter              = "(status = 'approved')",
                $fields              = "id"
                . ",created"
                . ",status"
                . ",site_id"
                . ",site.name"
                . ",publisher_id"
                . ",publisher.name"
                . ",advertiser_ref_id"
                . ",advertiser_sub_campaign_id"
                . ",advertiser_sub_campaign.ref"
                . ",publisher_sub_campaign_id"
                . ",publisher_sub_campaign.ref"
                . ",user_id"
                . ",device_id"
                . ",os_id"
                . ",google_aid"
                . ",ios_ifa"
                . ",ios_ifv"
                . ",windows_aid"
                . ",referral_url"
                . ",is_view_through",
            $format              = "csv",
            $response_timezone   = "America/Los_Angeles"
        );

        $this->assertNotNull($response);
        $this->assertEquals(200, $response->getHttpCode());
    }
}