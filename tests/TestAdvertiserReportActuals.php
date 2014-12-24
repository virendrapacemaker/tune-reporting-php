<?php
/**
 * TestAdvertiserReportActuals.php, TUNE Reporting SDK PHPUnit Test
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
 * @version   $Date: 2014-12-24 10:43:56 $
 * @link      https://developers.mobileapptracking.com/tune-reporting-sdks @endlink
 *
 */

require_once dirname(__FILE__) . "/../src/TuneReporting.php";

use TuneReporting\Api\AdvertiserReportActuals;
use TuneReporting\Helpers\SdkConfig;

class TestAdvertiserReportActuals extends \PHPUnit_Framework_TestCase
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

        $tune_reporting_test_config_file = dirname(__FILE__) . "/tune_reporting_sdk.test.config";
        $this->assertTrue(file_exists($tune_reporting_test_config_file), "Test config file does not exist: '{$tune_reporting_test_config_file}'");
        $sdk_config = SdkConfig::getInstance($tune_reporting_test_config_file);
        $this->assertNotNull($sdk_config);

        $this->advertiser_report = new AdvertiserReportActuals();
        $this->assertNotNull($this->advertiser_report);
    }

    /**
     * Test getSdkConfig
     */
    public function testSdkConfig()
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
    public function testFields()
    {
        $fields = $this->advertiser_report->fields();
        $this->assertNotNull($fields);
        $this->assertNotEmpty($fields);
    }

    /**
     * Test fields
     */
    public function testFieldsEndpoint()
    {
        $fields = $this->advertiser_report->fields(AdvertiserReportActuals::TUNE_FIELDS_ENDPOINT);
        $this->assertNotNull($fields);
        $this->assertNotEmpty($fields);
    }

    /**
     * Test fields
     */
    public function testFieldsDefault()
    {
        $fields = $this->advertiser_report->fields(AdvertiserReportActuals::TUNE_FIELDS_DEFAULT);
        $this->assertNotNull($fields);
        $this->assertNotEmpty($fields);
    }

    /**
     * Test fields
     */
    public function testFieldsRecommended()
    {
        $fields = $this->advertiser_report->fields(AdvertiserReportActuals::TUNE_FIELDS_RECOMMENDED);
        $this->assertNotNull($fields);
        $this->assertNotEmpty($fields);
    }

    /**
     * Test fields
     */
    public function testFieldsDefaultMinimal()
    {
        $fields = $this->advertiser_report->fields(AdvertiserReportActuals::TUNE_FIELDS_DEFAULT | AdvertiserReportActuals::TUNE_FIELDS_MINIMAL);
        $this->assertNotNull($fields);
        $this->assertNotEmpty($fields);
    }

    /**
     * Test count
     */
    public function testCount()
    {
        $week_ago       = date('Y-m-d', strtotime("-8 days"));
        $yesterday      = date('Y-m-d', strtotime("-1 days"));
        $start_date     = "{$week_ago} 00:00:00";
        $end_date       = "{$yesterday} 23:59:59";

        $response = $this->advertiser_report->count(
            $start_date,
            $end_date,
            $group               = "site_id,publisher_id",
            $filter              = "(publisher_id > 0)",
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
        $week_ago       = date('Y-m-d', strtotime("-8 days"));
        $yesterday      = date('Y-m-d', strtotime("-1 days"));
        $start_date     = "{$week_ago} 00:00:00";
        $end_date       = "{$yesterday} 23:59:59";

        $response = $this->advertiser_report->find(
            $start_date,
            $end_date,
            $fields              = null,
            $group               = "site_id,publisher_id",
            $filter              = "(publisher_id > 0)",
            $limit               = 5,
            $page                = null,
            $sort                = array("installs" => "DESC"),
            $timestamp           = "datehour",
            $response_timezone   = "America/Los_Angeles"
        );

        $this->assertNotNull($response);
        $this->assertEquals(200, $response->getHttpCode());
    }

    /**
     * Test find
     */
    public function testFindSelected()
    {
        $week_ago       = date('Y-m-d', strtotime("-8 days"));
        $yesterday      = date('Y-m-d', strtotime("-1 days"));
        $start_date     = "{$week_ago} 00:00:00";
        $end_date       = "{$yesterday} 23:59:59";

        $response = $this->advertiser_report->find(
            $start_date,
            $end_date,
            $fields              = "site_id"
            . ",site.name"
            . ",publisher_id"
            . ",publisher.name"
            . ",ad_impressions"
            . ",ad_impressions_unique"
            . ",ad_clicks"
            . ",ad_clicks_unique"
            . ",paid_installs"
            . ",paid_installs_assists"
            . ",non_installs_assists"
            . ",paid_events"
            . ",paid_events_assists"
            . ",non_events_assists"
            . ",paid_opens"
            . ",paid_opens_assists"
            . ",non_opens_assists",
            $group               = "site_id,publisher_id",
            $filter              = "(publisher_id > 0)",
            $limit               = 5,
            $page                = null,
            $sort                = array("installs" => "DESC"),
            $timestamp           = "datehour",
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
        $week_ago       = date('Y-m-d', strtotime("-8 days"));
        $yesterday      = date('Y-m-d', strtotime("-1 days"));
        $start_date     = "{$week_ago} 00:00:00";
        $end_date       = "{$yesterday} 23:59:59";

        $response = $this->advertiser_report->find(
            $start_date,
            $end_date,
            $fields              = $this->advertiser_report->fields(AdvertiserReportActuals::TUNE_FIELDS_DEFAULT),
            $group               = "site_id,publisher_id",
            $filter              = "(publisher_id > 0)",
            $limit               = 5,
            $page                = null,
            $sort                = array("installs" => "DESC"),
            $timestamp           = "datehour",
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
        $week_ago       = date('Y-m-d', strtotime("-8 days"));
        $yesterday      = date('Y-m-d', strtotime("-1 days"));
        $start_date     = "{$week_ago} 00:00:00";
        $end_date       = "{$yesterday} 23:59:59";

        $response = $this->advertiser_report->find(
            $start_date,
            $end_date,
            $fields              = $this->advertiser_report->fields(AdvertiserReportActuals::TUNE_FIELDS_ENDPOINT),
            $group               = "site_id,publisher_id",
            $filter              = "(publisher_id > 0)",
            $limit               = 5,
            $page                = null,
            $sort                = array("installs" => "DESC"),
            $timestamp           = "datehour",
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
        $week_ago       = date('Y-m-d', strtotime("-8 days"));
        $yesterday      = date('Y-m-d', strtotime("-1 days"));
        $start_date     = "{$week_ago} 00:00:00";
        $end_date       = "{$yesterday} 23:59:59";

        $response = $this->advertiser_report->find(
            $start_date,
            $end_date,
            $fields              = $this->advertiser_report->fields(AdvertiserReportActuals::TUNE_FIELDS_RECOMMENDED),
            $group               = "site_id,publisher_id",
            $filter              = "(publisher_id > 0)",
            $limit               = 5,
            $page                = null,
            $sort                = array("installs" => "DESC"),
            $timestamp           = "datehour",
            $response_timezone   = "America/Los_Angeles"
        );

        $this->assertNotNull($response);
        $this->assertEquals(200, $response->getHttpCode());
    }

        public function testExport()
    {
        $week_ago       = date('Y-m-d', strtotime("-8 days"));
        $yesterday      = date('Y-m-d', strtotime("-1 days"));
        $start_date     = "{$week_ago} 00:00:00";
        $end_date       = "{$yesterday} 23:59:59";

        $response = $this->advertiser_report->export(
            $start_date,
            $end_date,
            $fields              = null,
            $group               = "site_id,publisher_id",
            $filter              = "(publisher_id > 0)",
            $timestamp           = "datehour",
            $format              = "csv",
            $response_timezone   = "America/Los_Angeles"
        );

        $this->assertNotNull($response);
        $this->assertEquals(200, $response->getHttpCode());

        $job_id = AdvertiserReportActuals::parseResponseReportJobId($response);
        $this->assertNotNull($job_id);
        $this->assertTrue(!empty($job_id));
    }

    public function testExportRecommended()
    {
        $week_ago       = date('Y-m-d', strtotime("-8 days"));
        $yesterday      = date('Y-m-d', strtotime("-1 days"));
        $start_date     = "{$week_ago} 00:00:00";
        $end_date       = "{$yesterday} 23:59:59";

        $response = $this->advertiser_report->export(
            $start_date,
            $end_date,
            $fields              = $this->advertiser_report->fields(AdvertiserReportActuals::TUNE_FIELDS_RECOMMENDED),
            $group               = "site_id,publisher_id",
            $filter              = "(publisher_id > 0)",
            $timestamp           = "datehour",
            $format              = "csv",
            $response_timezone   = "America/Los_Angeles"
        );

        $this->assertNotNull($response);
        $this->assertEquals(200, $response->getHttpCode());

        $job_id = AdvertiserReportActuals::parseResponseReportJobId($response);
        $this->assertNotNull($job_id);
        $this->assertTrue(!empty($job_id));
    }

    public function testExportSelected()
    {
        $week_ago       = date('Y-m-d', strtotime("-8 days"));
        $yesterday      = date('Y-m-d', strtotime("-1 days"));
        $start_date     = "{$week_ago} 00:00:00";
        $end_date       = "{$yesterday} 23:59:59";

        $response = $this->advertiser_report->export(
            $start_date,
            $end_date,
            $fields              = "site_id"
            . ",site.name"
            . ",publisher_id"
            . ",publisher.name"
            . ",ad_impressions"
            . ",ad_impressions_unique"
            . ",ad_clicks"
            . ",ad_clicks_unique"
            . ",paid_installs"
            . ",paid_installs_assists"
            . ",non_installs_assists"
            . ",paid_events"
            . ",paid_events_assists"
            . ",non_events_assists"
            . ",paid_opens"
            . ",paid_opens_assists"
            . ",non_opens_assists",
            $group               = "site_id,publisher_id",
            $filter              = "(publisher_id > 0)",
            $timestamp           = "datehour",
            $format              = "csv",
            $response_timezone   = "America/Los_Angeles"
        );

        $this->assertNotNull($response);
        $this->assertEquals(200, $response->getHttpCode());

        $job_id = AdvertiserReportActuals::parseResponseReportJobId($response);
        $this->assertNotNull($job_id);
        $this->assertTrue(!empty($job_id));
    }

    /**
     * @large
     */
    public function testFetch() {
        try {
            $week_ago       = date('Y-m-d', strtotime("-8 days"));
            $yesterday      = date('Y-m-d', strtotime("-1 days"));
            $start_date     = "{$week_ago} 00:00:00";
            $end_date       = "{$yesterday} 23:59:59";

            $response = $this->advertiser_report->export(
                $start_date,
                $end_date,
                $fields              = $this->advertiser_report->fields(AdvertiserReportActuals::TUNE_FIELDS_RECOMMENDED),
                $group               = "site_id,publisher_id",
                $filter              = "(publisher_id > 0)",
                $timestamp           = "datehour",
                $format              = "csv",
                $response_timezone   = "America/Los_Angeles"
            );

            $this->assertNotNull($response);
            $this->assertEquals(200, $response->getHttpCode());

            $job_id = AdvertiserReportActuals::parseResponseReportJobId($response);
            $this->assertNotNull($job_id);
            $this->assertTrue(!empty($job_id));

            $response = $this->advertiser_report->fetch(
                $job_id,
                $verbose = false
            );

            $report_url = AdvertiserReportActuals::parseResponseReportUrl($response);
            $this->assertNotNull($report_url);
            $this->assertTrue(!empty($report_url));
        } catch (Exception $ex ) {
            $this->fail($ex->getMessage());
        }
    }
}
