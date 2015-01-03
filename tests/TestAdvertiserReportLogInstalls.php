<?php
/**
 * TestAdvertiserReportLogInstalls.php, TUNE Reporting SDK PHPUnit Test
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
 * @category  TUNE_Reporting
 *
 * @author    Jeff Tanner <jefft@tune.com>
 * @copyright 2014 TUNE, Inc. (http://www.tune.com)
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version   $Date: 2015-01-03 08:14:06 $
 * @link      https://developers.mobileapptracking.com/tune-reporting-sdks @endlink
 *
 */

require_once dirname(__FILE__) . "/../src/TuneReporting.php";

use TuneReporting\Api\AdvertiserReportLogInstalls;
use TuneReporting\Helpers\SdkConfig;

class TestAdvertiserReportLogInstalls extends \PHPUnit_Framework_TestCase
{
    /**
     * @ignore
     */
    protected $advertiser_report = null;

    /**
     * Get API Key from environment.
     */
    protected function setUp()
    {
        $default_date_timezone = ini_get('date.timezone');
        $this->assertNotNull($default_date_timezone, "Set php.ini date.timezone.");
        $this->assertInternalType('string', $default_date_timezone, "Set php.ini date.timezone.");
        $this->assertNotEmpty($default_date_timezone, "Set php.ini date.timezone.");

        $api_key = getenv('API_KEY');
        $this->assertNotNull($api_key);
        $tune_reporting_config_file = dirname(__FILE__) . "/../config/tune_reporting_sdk.config";
        $this->assertTrue(file_exists($tune_reporting_config_file), "SDK config file does not exist: '{$tune_reporting_config_file}'");
        $sdk_config = SdkConfig::getInstance($tune_reporting_config_file);
        $this->assertNotNull($sdk_config);
        $sdk_config->setApiKey($api_key);

        $this->advertiser_report = new AdvertiserReportLogInstalls();
        $this->assertNotNull($this->advertiser_report);
    }

    /**
     * Test fields
     */
    public function testFields()
    {
        $sdk_config = $this->advertiser_report->getSdkConfig();
        $this->assertNotNull($sdk_config);
        $api_key = $sdk_config->getApiKey();

        $this->assertNotNull($api_key, "In tune_reporting_sdk.config, set 'tune_reporting_api_key_string'");
        $this->assertInternalType('string', $api_key, "In tune_reporting_sdk.config, set 'tune_reporting_api_key_string'");
        $this->assertNotEmpty($api_key, "In tune_reporting_sdk.config, set 'tune_reporting_api_key_string'");
        $this->assertNotEquals("API_KEY", $api_key, "In tune_reporting_sdk.config, set 'tune_reporting_api_key_string'");
    }

    /**
     * Test fields
     */
    public function testFieldsDefault()
    {
        $fields = $this->advertiser_report->fields(AdvertiserReportLogInstalls::TUNE_FIELDS_DEFAULT);
        $this->assertNotNull($fields);
        $this->assertNotEmpty($fields);
    }

    /**
     * Test fields
     */
    public function testFieldsRecommended()
    {
        $fields = $this->advertiser_report->fields(AdvertiserReportLogInstalls::TUNE_FIELDS_RECOMMENDED);
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

        $response = $this->advertiser_report->fields();
        $this->assertNotNull($response);

        $response = $this->advertiser_report->count(
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

        $response = $this->advertiser_report->find(
            $start_date,
            $end_date,
            $fields              = $this->advertiser_report->fields(AdvertiserReportLogInstalls::TUNE_FIELDS_RECOMMENDED),
            $filter              = "(status = 'approved')",
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

        $response = $this->advertiser_report->export(
            $start_date,
            $end_date,
            $fields              = $this->advertiser_report->fields(AdvertiserReportLogInstalls::TUNE_FIELDS_RECOMMENDED),
            $filter              = "(status = 'approved')",
            $format              = "csv",
            $response_timezone   = "America/Los_Angeles"
        );

        $this->assertNotNull($response);
        $this->assertEquals(200, $response->getHttpCode());

        $job_id = AdvertiserReportLogInstalls::parseResponseReportJobId($response);
        $this->assertNotNull($job_id);
        $this->assertTrue(!empty($job_id));
    }

    /**
     * @large
     */
    public function testFetch()
    {
        try {
            $yesterday      = date('Y-m-d', strtotime("-1 days"));
            $start_date     = "{$yesterday} 00:00:00";
            $end_date       = "{$yesterday} 23:59:59";

            $response = $this->advertiser_report->export(
                $start_date,
                $end_date,
                $fields              = $this->advertiser_report->fields(AdvertiserReportLogInstalls::TUNE_FIELDS_RECOMMENDED),
                $filter              = "(status = 'approved')",
                $format              = "csv",
                $response_timezone   = "America/Los_Angeles"
            );

            $this->assertNotNull($response);
            $this->assertEquals(200, $response->getHttpCode());

            $job_id = AdvertiserReportLogInstalls::parseResponseReportJobId($response);
            $this->assertNotNull($job_id);
            $this->assertTrue(!empty($job_id));

            $response = $this->advertiser_report->fetch(
                $job_id,
                $verbose = false
            );

            $report_url = AdvertiserReportLogInstalls::parseResponseReportUrl($response);
            $this->assertNotNull($report_url);
            $this->assertTrue(!empty($report_url));
        } catch (Exception $ex ) {
            $this->fail($ex->getMessage());
        }
    }
}
