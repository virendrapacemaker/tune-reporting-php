<?php
/**
 * TestAdvertiserReportClicks.php, TUNE SDK PHPUnit Test
 *
 * Copyright (c) 2014 TUNE, Inc.
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
 * @category  TUNE
 *
 * @author    Jeff Tanner <jefft@tune.com>
 * @copyright 2014 TUNE (http://www.tune.com)
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version   $Date: 2014-12-17 13:40:16 $
 * @link      https://developers.mobileapptracking.com/tune-reporting-sdks @endlink
 *
 */

require_once dirname(__FILE__) . "/../src/TuneReporting.php";

use TuneReporting\Api\AdvertiserReportClicks;
use TuneReporting\Helpers\TuneSdkException;

class TestAdvertiserReportClicks extends \PHPUnit_Framework_TestCase
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
     * Test fields
     */
    public function testConfig()
    {
        $advertiser_report_clicks = new AdvertiserReportClicks($this->api_key, $validate_fields = true);

        $config = $advertiser_report_clicks->getConfig();
        $this->assertNotNull($config);
    }

    /**
     * Test fields
     */
    public function testFields()
    {
        $advertiser_report_clicks = new AdvertiserReportClicks($this->api_key, $validate_fields = true);

        $fields = $advertiser_report_clicks->fields();
        $this->assertNotNull($fields);
        $this->assertNotEmpty($fields);
    }

    /**
     * Test fields
     */
    public function testFieldsEndpoint()
    {
        $advertiser_report_clicks = new AdvertiserReportClicks($this->api_key, $validate_fields = true);

        $fields = $advertiser_report_clicks->fields(AdvertiserReportClicks::TUNE_FIELDS_ENDPOINT);
        $this->assertNotNull($fields);
        $this->assertNotEmpty($fields);
    }

    /**
     * Test fields
     */
    public function testFieldsDefault()
    {
        $advertiser_report_clicks = new AdvertiserReportClicks(
            $this->api_key,
            $validate_fields = true
        );

        $fields = $advertiser_report_clicks->fields(AdvertiserReportClicks::TUNE_FIELDS_DEFAULT);
        $this->assertNotNull($fields);
        $this->assertNotEmpty($fields);
    }

    /**
     * Test fields
     */
    public function testFieldsRecommended()
    {
        $advertiser_report_clicks = new AdvertiserReportClicks($this->api_key, $validate_fields = true);

        $fields = $advertiser_report_clicks->fields(AdvertiserReportClicks::TUNE_FIELDS_RECOMMENDED);
        $this->assertNotNull($fields);
        $this->assertNotEmpty($fields);
    }

    /**
     * Test fields
     */
    public function testFieldsDefaultMinimal()
    {
        $advertiser_report_clicks = new AdvertiserReportClicks($this->api_key, $validate_fields = true);

        $fields = $advertiser_report_clicks->fields(AdvertiserReportClicks::TUNE_FIELDS_DEFAULT | AdvertiserReportClicks::TUNE_FIELDS_MINIMAL);
        $this->assertNotNull($fields);
        $this->assertNotEmpty($fields);
    }

    /**
     * Test count
     */
    public function testCount()
    {
        $yesterday      = date('Y-m-d', strtotime("-1 days"));
        $start_date     = "{$yesterday} 00:00:00";
        $end_date       = "{$yesterday} 23:59:59";

        $advertiser_report_clicks = new AdvertiserReportClicks($this->api_key, $validate_fields = true);

        $response = $advertiser_report_clicks->fields();
        $this->assertNotNull($response);

        $response = $advertiser_report_clicks->count(
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

        $advertiser_report_clicks = new AdvertiserReportClicks($this->api_key, $validate_fields = true);

        $response = $advertiser_report_clicks->find(
            $start_date,
            $end_date,
            $fields              = null,
            $filter              = null,
            $limit               = 5,
            $page                = null,
            $sort                = array("created" => "DESC"),
            $response_timezone   = "America/Los_Angeles"
        );

        $this->assertNotNull($response);
        $this->assertEquals(200, $response->getHttpCode());
    }

    /**
     * Test find
     */
    public function testFindDefault()
    {
        $yesterday      = date('Y-m-d', strtotime("-1 days"));
        $start_date     = "{$yesterday} 00:00:00";
        $end_date       = "{$yesterday} 23:59:59";

        $advertiser_report_clicks = new AdvertiserReportClicks($this->api_key, $validate_fields = true);

        $response = $advertiser_report_clicks->find(
            $start_date,
            $end_date,
            $fields              = $advertiser_report_clicks->fields(AdvertiserReportClicks::TUNE_FIELDS_DEFAULT),
            $filter              = null,
            $limit               = 5,
            $page                = null,
            $sort                = array("created" => "DESC"),
            $response_timezone   = "America/Los_Angeles"
        );

        $this->assertNotNull($response);
        $this->assertEquals(200, $response->getHttpCode());
    }

    /**
     * Test find
     */
    public function testFindEndpoint()
    {
        $yesterday      = date('Y-m-d', strtotime("-1 days"));
        $start_date     = "{$yesterday} 00:00:00";
        $end_date       = "{$yesterday} 23:59:59";

        $advertiser_report_clicks = new AdvertiserReportClicks($this->api_key, $validate_fields = true);

        $response = $advertiser_report_clicks->find(
            $start_date,
            $end_date,
            $fields              = $advertiser_report_clicks->fields(AdvertiserReportClicks::TUNE_FIELDS_ENDPOINT),
            $filter              = null,
            $limit               = 5,
            $page                = null,
            $sort                = array("created" => "DESC"),
            $response_timezone   = "America/Los_Angeles"
        );

        $this->assertNotNull($response);
        $this->assertEquals(200, $response->getHttpCode());
    }

    /**
     * Test find
     */
    public function testFindRecommended()
    {
        $yesterday      = date('Y-m-d', strtotime("-1 days"));
        $start_date     = "{$yesterday} 00:00:00";
        $end_date       = "{$yesterday} 23:59:59";

        $advertiser_report_clicks = new AdvertiserReportClicks($this->api_key, $validate_fields = true);

        $response = $advertiser_report_clicks->find(
            $start_date,
            $end_date,
            $fields              = $advertiser_report_clicks->fields(AdvertiserReportClicks::TUNE_FIELDS_RECOMMENDED),
            $filter              = null,
            $limit               = 5,
            $page                = null,
            $sort                = array("created" => "DESC"),
            $response_timezone   = "America/Los_Angeles"
        );

        $this->assertNotNull($response);
        $this->assertEquals(200, $response->getHttpCode());
    }

    /**
     * @expectedException TuneReporting\Helpers\TuneSdkException
     */
    public function testFindInvalidField()
    {
        $yesterday      = date('Y-m-d', strtotime("-1 days"));
        $start_date     = "{$yesterday} 00:00:00";
        $end_date       = "{$yesterday} 23:59:59";

        $advertiser_report_clicks = new AdvertiserReportClicks($this->api_key, $validate_fields = true);

        $response = $advertiser_report_clicks->find(
            $start_date,
            $end_date,
            $fields              = "foo",
            $filter              = null,
            $limit               = 5,
            $page                = null,
            $sort                = array("created" => "DESC"),
            $response_timezone   = "America/Los_Angeles"
        );
    }

    /**
     * @expectedException TuneReporting\Helpers\TuneSdkException
     */
    public function testFindInvalidFilterField()
    {
        $yesterday      = date('Y-m-d', strtotime("-1 days"));
        $start_date     = "{$yesterday} 00:00:00";
        $end_date       = "{$yesterday} 23:59:59";

        $advertiser_report_clicks = new AdvertiserReportClicks($this->api_key, $validate_fields = true);

        $response = $advertiser_report_clicks->find(
            $start_date,
            $end_date,
            $fields              = null,
            $filter              = "(foo > 0)",
            $limit               = 5,
            $page                = null,
            $sort                = array("created" => "DESC"),
            $response_timezone   = "America/Los_Angeles"
        );
    }

    /**
     * @expectedException TuneReporting\Helpers\TuneSdkException
     */
    public function testFindInvalidFilterOperator()
    {
        $yesterday      = date('Y-m-d', strtotime("-1 days"));
        $start_date     = "{$yesterday} 00:00:00";
        $end_date       = "{$yesterday} 23:59:59";

        $advertiser_report_clicks = new AdvertiserReportClicks($this->api_key, $validate_fields = true);

        $response = $advertiser_report_clicks->find(
            $start_date,
            $end_date,
            $fields              = null,
            $filter              = "(created # 0)",
            $limit               = 5,
            $page                = null,
            $sort                = array("created" => "DESC"),
            $response_timezone   = "America/Los_Angeles"
        );
    }

    /**
     * @expectedException TuneReporting\Helpers\TuneSdkException
     */
    public function testFindInvalidFields()
    {
        $yesterday      = date('Y-m-d', strtotime("-1 days"));
        $start_date     = "{$yesterday} 00:00:00";
        $end_date       = "{$yesterday} 23:59:59";

        $advertiser_report_clicks = new AdvertiserReportClicks($this->api_key, $validate_fields = true);

        $response = $advertiser_report_clicks->find(
            $start_date,
            $end_date,
            $fields              = "foo",
            $filter              = null,
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

        $advertiser_report_clicks = new AdvertiserReportClicks($this->api_key, $validate_fields = true);

        $response = $advertiser_report_clicks->export(
            $start_date,
            $end_date,
            $fields              = $advertiser_report_clicks->fields(AdvertiserReportClicks::TUNE_FIELDS_RECOMMENDED),
            $filter              = null,
            $format              = "csv",
            $response_timezone   = "America/Los_Angeles"
        );

        $this->assertNotNull($response);
        $this->assertEquals(200, $response->getHttpCode());

        $job_id = AdvertiserReportClicks::parseResponseReportJobId($response);
        $this->assertNotNull($job_id);
        $this->assertTrue(!empty($job_id));
    }

    /**
     * @large
     */
    public function testFetch() {
        try {
            $yesterday      = date('Y-m-d', strtotime("-1 days"));
            $start_date     = "{$yesterday} 00:00:00";
            $end_date       = "{$yesterday} 23:59:59";

            $advertiser_report_clicks = new AdvertiserReportClicks($this->api_key, $validate_fields = true);

            $response = $advertiser_report_clicks->export(
                $start_date,
                $end_date,
                $fields              = $advertiser_report_clicks->fields(AdvertiserReportClicks::TUNE_FIELDS_RECOMMENDED),
                $filter              = null,
                $format              = "csv",
                $response_timezone   = "America/Los_Angeles"
            );

            $this->assertNotNull($response);
            $this->assertEquals(200, $response->getHttpCode());

            $job_id = AdvertiserReportClicks::parseResponseReportJobId($response);
            $this->assertNotNull($job_id);
            $this->assertTrue(!empty($job_id));

            $response = $advertiser_report_clicks->fetch(
                $job_id,
                $verbose = false
            );

            $report_url = AdvertiserReportClicks::parseResponseReportUrl($response);
            $this->assertNotNull($report_url);
            $this->assertTrue(!empty($report_url));
        } catch ( Exception $ex ) {
            $this->fail($ex->getMessage());
        }
    }
}
