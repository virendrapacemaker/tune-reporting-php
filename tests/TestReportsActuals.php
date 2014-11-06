<?php
/**
 * TestReportsActuals.php, Tune SDK PHPUnit Test
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
 *
 * @author    Jeff Tanner <jefft@tune.com>
 * @copyright 2014 Tune (http://www.tune.com)
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version   $Date: 2014-11-05 16:25:44 $
 * @link      https://developers.mobileapptracking.com @endlink
 *
 */

require_once dirname(__FILE__) . "/../src/TuneApi.php";

use \Tune\Management\Api\Advertiser\Stats;

class TestReportsActuals extends \PHPUnit_Framework_TestCase
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
    public function testFields()
    {
        $stats = new Stats($this->api_key, $validate_fields = true);

        $response = $stats->fields();
        $this->assertNotNull($response);
        $this->assertNotEmpty($response);
    }

    /**
     * Test fields
     */
    public function testFieldsRecommended()
    {
        $stats = new Stats($this->api_key, $validate_fields = true);

        $response = $stats->fields(Stats::TUNE_FIELDS_RECOMMENDED);
        $this->assertNotNull($response);
        $this->assertNotEmpty($response);
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

        $stats = new Stats($this->api_key, $validate_fields = true);
        $response = $stats->count(
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

        $stats = new Stats($this->api_key, $validate_fields = true);

        $response = $stats->find(
            $start_date,
            $end_date,
            $group               = "site_id,publisher_id",
            $filter              = "(publisher_id > 0)",
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

        $stats = new Stats($this->api_key, $validate_fields = true);

        $response = $stats->export(
            $start_date,
            $end_date,
            $group               = "site_id,publisher_id",
            $filter              = "(publisher_id > 0)",
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
            $timestamp           = "datehour",
            $format              = "csv",
            $response_timezone   = "America/Los_Angeles"
        );

        $this->assertNotNull($response);
        $this->assertEquals(200, $response->getHttpCode());

        $job_id = Stats::parseResponseReportJobId($response);
        $this->assertNotNull($job_id);
        $this->assertTrue(!empty($job_id));
    }

    public function testFetch() {
        try {
            $week_ago       = date('Y-m-d', strtotime("-8 days"));
            $yesterday      = date('Y-m-d', strtotime("-1 days"));
            $start_date     = "{$week_ago} 00:00:00";
            $end_date       = "{$yesterday} 23:59:59";

            $stats = new Stats($this->api_key, $validate_fields = true);

            $response = $stats->export(
                $start_date,
                $end_date,
                $group               = "site_id,publisher_id",
                $filter              = "(publisher_id > 0)",
                $fields              = $stats->fields(Stats::TUNE_FIELDS_RECOMMENDED),
                $timestamp           = "datehour",
                $format              = "csv",
                $response_timezone   = "America/Los_Angeles"
            );

            $this->assertNotNull($response);
            $this->assertEquals(200, $response->getHttpCode());

            $job_id = Stats::parseResponseReportJobId($response);
            $this->assertNotNull($job_id);
            $this->assertTrue(!empty($job_id));

            $response = $stats->fetch(
                $job_id,
                $verbose = false,
                $sleep = 10
            );

            $report_url = Stats::parseResponseReportUrl($response);
            $this->assertNotNull($report_url);
            $this->assertTrue(!empty($report_url));
        } catch ( Exception $ex ) {
            $this->fail($ex->getMessage());
        }
    }
}
