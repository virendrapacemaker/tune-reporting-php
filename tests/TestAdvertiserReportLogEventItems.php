<?php
/**
 * TestAdvertiserReportLogEventItems.php, TUNE Reporting SDK PHPUnit Test
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
 * @author  Jeff Tanner <jefft@tune.com>
 * @copyright 2015 TUNE, Inc. (http://www.tune.com)
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version   $Date: 2015-04-09 17:36:25 $
 * @link    https://developers.mobileapptracking.com/tune-reporting-sdks @endlink
 *
 */

require_once dirname(__FILE__) . "/../src/TuneReporting.php";

use TuneReporting\Api\AdvertiserReportLogEventItems;
use TuneReporting\Api\SessionAuthenticate;
use TuneReporting\Helpers\SdkConfig;

class TestAdvertiserReportLogEventItems extends \PHPUnit_Framework_TestCase
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

    $session_authenticate = new SessionAuthenticate();
    $response = $session_authenticate->api_key($api_key);
    $this->assertNotNull($response);
    $session_token = $response->getData();
    $this->assertNotNull($session_token);

    $tune_reporting_config_file = dirname(__FILE__) . "/../config/tune_reporting_sdk.config";
    $this->assertTrue(file_exists($tune_reporting_config_file), "SDK config file does not exist: '{$tune_reporting_config_file}'");
    $sdk_config = SdkConfig::getInstance($tune_reporting_config_file);
    $this->assertNotNull($sdk_config);
    $sdk_config->setAuthKey($session_token);
    $sdk_config->setAuthType("session_token");

    $this->advertiser_report = new AdvertiserReportLogEventItems();
    $this->assertNotNull($this->advertiser_report);
  }

  /**
   * Test getSdkConfig
   */
  public function testSdkConfig()
  {
    $sdk_config = $this->advertiser_report->getSdkConfig();
    $this->assertNotNull($sdk_config);
    $auth_key = $sdk_config->getAuthKey();
    $auth_type = $sdk_config->getAuthType();

    $this->assertNotNull($auth_key, "In tune_reporting_sdk.config, set 'tune_reporting_auth_key_string'");
    $this->assertInternalType('string', $auth_key, "In tune_reporting_sdk.config, set 'tune_reporting_auth_key_string'");
    $this->assertNotEmpty($auth_key, "In tune_reporting_sdk.config, set 'tune_reporting_auth_key_string'");
    $this->assertEquals("session_token", $auth_type, "In tune_reporting_sdk.config, set 'tune_reporting_auth_type_string'");
  }

  /**
   * Test fields
   */
  public function testFields()
  {
    $fields = $this->advertiser_report->getFields();
    $this->assertNotNull($fields);
    $this->assertNotEmpty($fields);
  }

  /**
   * Test fields
   */
  public function testFieldsDefault()
  {
    $fields = $this->advertiser_report->getFields(AdvertiserReportLogEventItems::TUNE_FIELDS_DEFAULT);
    $this->assertNotNull($fields);
    $this->assertNotEmpty($fields);
  }

  /**
   * Test fields
   */
  public function testFieldsRecommended()
  {
    $fields = $this->advertiser_report->getFields(AdvertiserReportLogEventItems::TUNE_FIELDS_RECOMMENDED);
    $this->assertNotNull($fields);
    $this->assertNotEmpty($fields);
  }

  /**
   * Test count
   */
  public function testCount()
  {
    $yesterday    = date('Y-m-d', strtotime("-1 days"));
    $start_date   = "{$yesterday} 00:00:00";
    $end_date     = "{$yesterday} 23:59:59";

    $map_params = array(
      "start_date"          => $start_date,
      "end_date"            => $end_date,
      "filter"              => null,
      "response_timezone"   => "America/Los_Angeles"
    );

    $response = $this->advertiser_report->count(
      $map_params
    );

    $this->assertNotNull($response);
    $this->assertEquals(200, $response->getHttpCode());
  }

  /**
   * Test find
   */
  public function testFind()
  {
    $yesterday    = date('Y-m-d', strtotime("-1 days"));
    $start_date   = "{$yesterday} 00:00:00";
    $end_date     = "{$yesterday} 23:59:59";

    $map_params = array(
      "start_date"          => $start_date,
      "end_date"            => $end_date,
      "fields"              => null,
      "filter"              => null,
      "limit"               => 5,
      "page"                => null,
      "sort"                => array("created" => "DESC"),
      "response_timezone"   => "America/Los_Angeles"
    );

    $response = $this->advertiser_report->find(
      $map_params
    );

    $this->assertNotNull($response);
    $this->assertEquals(200, $response->getHttpCode());
  }

  /**
   * Test find
   */
  public function testFindRecommended()
  {
    $yesterday    = date('Y-m-d', strtotime("-1 days"));
    $start_date   = "{$yesterday} 00:00:00";
    $end_date     = "{$yesterday} 23:59:59";

    $map_params = array(
      "start_date"          => $start_date,
      "end_date"            => $end_date,
      "fields"              => $this->advertiser_report->getFields(AdvertiserReportLogEventItems::TUNE_FIELDS_RECOMMENDED),
      "filter"              => null,
      "limit"               => 5,
      "page"                => null,
      "sort"                => array("created" => "DESC"),
      "response_timezone"   => "America/Los_Angeles"
    );

    $response = $this->advertiser_report->find(
      $map_params
    );

    $this->assertNotNull($response);
    $this->assertEquals(200, $response->getHttpCode());
  }

  public function testExport()
  {
    $yesterday    = date('Y-m-d', strtotime("-1 days"));
    $start_date   = "{$yesterday} 00:00:00";
    $end_date     = "{$yesterday} 23:59:59";

    $map_params = array(
      "start_date"          => $start_date,
      "end_date"            => $end_date,
      "fields"              => null,
      "filter"              => null,
      "format"              => "csv",
      "response_timezone"   => "America/Los_Angeles"
    );

    $response = $this->advertiser_report->export(
      $map_params
    );

    $this->assertNotNull($response);
    $this->assertEquals(200, $response->getHttpCode());

    $job_id = AdvertiserReportLogEventItems::parseResponseReportJobId($response);
    $this->assertNotNull($job_id);
    $this->assertTrue(!empty($job_id));
  }

  public function testExportRecommended()
  {
    $yesterday    = date('Y-m-d', strtotime("-1 days"));
    $start_date   = "{$yesterday} 00:00:00";
    $end_date     = "{$yesterday} 23:59:59";

    $map_params = array(
      "start_date"          => $start_date,
      "end_date"            => $end_date,
      "fields"              => $this->advertiser_report->getFields(AdvertiserReportLogEventItems::TUNE_FIELDS_RECOMMENDED),
      "filter"              => null,
      "format"              => "csv",
      "response_timezone"   => "America/Los_Angeles"
    );

    $response = $this->advertiser_report->export(
      $map_params
    );

    $this->assertNotNull($response);
    $this->assertEquals(200, $response->getHttpCode());

    $job_id = AdvertiserReportLogEventItems::parseResponseReportJobId($response);
    $this->assertNotNull($job_id);
    $this->assertTrue(!empty($job_id));
  }

  /**
   * @large
   */
  public function testFetch() {
    try {
      $yesterday    = date('Y-m-d', strtotime("-1 days"));
      $start_date   = "{$yesterday} 00:00:00";
      $end_date     = "{$yesterday} 23:59:59";

      $map_params = array(
        "start_date"          => $start_date,
        "end_date"            => $end_date,
        "fields"              => $this->advertiser_report->getFields(AdvertiserReportLogEventItems::TUNE_FIELDS_RECOMMENDED),
        "filter"              => null,
        "format"              => "csv",
        "response_timezone"   => "America/Los_Angeles"
      );

      $response = $this->advertiser_report->export(
        $map_params
      );

      $this->assertNotNull($response);
      $this->assertEquals(200, $response->getHttpCode());

      $job_id = AdvertiserReportLogEventItems::parseResponseReportJobId($response);
      $this->assertNotNull($job_id);
      $this->assertTrue(!empty($job_id));

      $response = $this->advertiser_report->fetch(
        $job_id,
        $verbose = false
      );

      $report_url = AdvertiserReportLogEventItems::parseResponseReportUrl($response);
      $this->assertNotNull($report_url);
      $this->assertTrue(!empty($report_url));
    } catch (Exception $ex ) {
      $this->fail($ex->getMessage());
    }
  }
}
