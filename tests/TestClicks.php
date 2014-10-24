<?php
/**
 * TestClicks.php, Tune SDK PHPUnit Test
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

use Tune\Management\Api\Advertiser\Stats\Clicks;
use Tune\Shared\TuneSdkException;

class TestClicks extends \PHPUnit_Framework_TestCase
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
        $default_date_timezone = ini_get('date.timezone');
        $this->assertNotNull($default_date_timezone, "Set php.ini date.timezone.");
        $this->assertInternalType('string', $default_date_timezone, "Set php.ini date.timezone.");
        $this->assertNotEmpty($default_date_timezone, "Set php.ini date.timezone.");

        $this->api_key = getenv('API_KEY');
        $this->assertNotNull($this->api_key, "In bash: 'export API_KEY=[your API KEY]'");
        $this->assertInternalType('string', $this->api_key, "In bash: 'export API_KEY=[your API KEY]'");
        $this->assertNotEmpty($this->api_key, "In bash: 'export API_KEY=[your API KEY]'");
    }

    /**
     * Test count
     */
    public function testCount()
    {
        $yesterday      = date('Y-m-d', strtotime("-1 days"));
        $start_date     = "{$yesterday} 00:00:00";
        $end_date       = "{$yesterday} 23:59:59";

        $clicks = new Clicks($this->api_key, $validate = true);

        $response = $clicks->getFields();
        $this->assertNotNull($response);

        $response = $clicks->count(
            $start_date,
            $end_date,
            $filter              = null,
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

        $clicks = new Clicks($this->api_key, $validate = true);

        $response = $clicks->find(
            $start_date,
            $end_date,
            $filter              = null,
            $fields              = "id"
            . ",created"
            . ",site_id"
            . ",site.name"
            . ",publisher_id"
            . ",publisher.name"
            . ",is_unique"
            . ",advertiser_sub_campaign_id"
            . ",advertiser_sub_campaign.ref"
            . ",publisher_sub_campaign_id"
            . ",publisher_sub_campaign.ref",
            $limit               = 5,
            $page                = null,
            $sort                = array("created" => "DESC"),
            $response_timezone   = "America/Los_Angeles"
        );

        $this->assertNotNull($response);
        $this->assertEquals(200, $response->getHttpCode());
    }

    /**
     * @expectedException Tune\Shared\TuneSdkException
     */
    public function testFindInvalidField()
    {
        $yesterday      = date('Y-m-d', strtotime("-1 days"));
        $start_date     = "{$yesterday} 00:00:00";
        $end_date       = "{$yesterday} 23:59:59";

        $clicks = new Clicks($this->api_key, $validate = true);

        $response = $clicks->find(
            $start_date,
            $end_date,
            $filter              = null,
            $fields              = "foo",
            $limit               = 5,
            $page                = null,
            $sort                = array("created" => "DESC"),
            $response_timezone   = "America/Los_Angeles"
        );
    }

    /**
     * @expectedException Tune\Shared\TuneSdkException
     */
    public function testFindInvalidFilterField()
    {
        $yesterday      = date('Y-m-d', strtotime("-1 days"));
        $start_date     = "{$yesterday} 00:00:00";
        $end_date       = "{$yesterday} 23:59:59";

        $clicks = new Clicks($this->api_key, $validate = true);

        $response = $clicks->find(
            $start_date,
            $end_date,
            $filter              = "(foo > 0)",
            $fields              = null,
            $limit               = 5,
            $page                = null,
            $sort                = array("created" => "DESC"),
            $response_timezone   = "America/Los_Angeles"
        );
    }

    /**
     * @expectedException Tune\Shared\TuneSdkException
     */
    public function testFindInvalidFilterOperator()
    {
        $yesterday      = date('Y-m-d', strtotime("-1 days"));
        $start_date     = "{$yesterday} 00:00:00";
        $end_date       = "{$yesterday} 23:59:59";

        $clicks = new Clicks($this->api_key, $validate = true);

        $response = $clicks->find(
            $start_date,
            $end_date,
            $filter              = "(created # 0)",
            $fields              = null,
            $limit               = 5,
            $page                = null,
            $sort                = array("created" => "DESC"),
            $response_timezone   = "America/Los_Angeles"
        );
    }

    /**
     * @expectedException Tune\Shared\TuneSdkException
     */
    public function testFindInvalidFields()
    {
        $yesterday      = date('Y-m-d', strtotime("-1 days"));
        $start_date     = "{$yesterday} 00:00:00";
        $end_date       = "{$yesterday} 23:59:59";

        $clicks = new Clicks($this->api_key, $validate = true);

        $response = $clicks->find(
            $start_date,
            $end_date,
            $filter              = null,
            $fields              = "id"
                . ",created"
                . ",site_id"
                . ",site.name"
                . ",publisher_id"
                . ",publisher.name"
                . ",foo"
                . ",advertiser_sub_campaign_id"
                . ",advertiser_sub_campaign.ref"
                . ",publisher_sub_campaign_id"
                . ",publisher_sub_campaign.ref",
            $limit               = 5,
            $page                = null,
            $sort                = array("created" => "DESC"),
            $response_timezone   = "America/Los_Angeles"
        );
    }

    public function testExport()
    {
        $yesterday      = date('Y-m-d', strtotime("-1 days"));
        $start_date     = "{$yesterday} 00:00:00";
        $end_date       = "{$yesterday} 23:59:59";

        $clicks = new Clicks($this->api_key, $validate = true);

        $response = $clicks->export(
            $start_date,
            $end_date,
            $filter              = null,
            $fields              = "id"
            . ",created"
            . ",site_id"
            . ",site.name"
            . ",publisher_id"
            . ",publisher.name"
            . ",is_unique"
            . ",advertiser_sub_campaign_id"
            . ",advertiser_sub_campaign.ref"
            . ",publisher_sub_campaign_id"
            . ",publisher_sub_campaign.ref",
            $format              = "csv",
            $response_timezone   = "America/Los_Angeles"
        );

        $this->assertNotNull($response);
        $this->assertEquals(200, $response->getHttpCode());
    }
}
