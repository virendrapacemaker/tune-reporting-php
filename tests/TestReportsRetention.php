<?php
/**
 * TestReportsRetention.php, Tune SDK PHPUnit Test
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

use \Tune\Management\Api\Advertiser\Stats\Retention;

class TestReportsRetention extends \PHPUnit_Framework_TestCase
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
        $retention = new Retention($this->api_key, $validate_fields = true);

        $response = $retention->fields();
        $this->assertNotNull($response);
        $this->assertNotEmpty($response);
    }

    /**
     * Test fields
     */
    public function testFieldsRecommended()
    {
        $retention = new Retention($this->api_key, $validate_fields = true);

        $response = $retention->fields(Retention::TUNE_FIELDS_RECOMMENDED);
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

        $retention = new Retention($this->api_key, $validate_fields = true);

        $response = $retention->count(
            $start_date,
            $end_date,
            $cohort_type         = "click",
            $group               = "site_id,install_publisher_id",
            $cohort_interval     = "year_day",
            $filter              = "(install_publisher_id > 0)",
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

        $retention = new Retention($this->api_key, $validate_fields = true);

        $response = $retention->find(
            $start_date,
            $end_date,
            $cohort_type         = "install",
            $aggregation_type    = "cumulative",
            $group               = "site_id,install_publisher_id",
            $fields              = $retention->fields(Retention::TUNE_FIELDS_RECOMMENDED),
            $cohort_interval     = "year_day",
            $filter              = "(install_publisher_id > 0)",
            $limit               = 10,
            $page                = null,
            $sort                = array("year_day" => "ASC", "install_publisher_id" => "ASC"),
            $format              = "csv",
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

        $retention = new Retention($this->api_key, $validate_fields = true);

        $response = $retention->export(
            $start_date,
            $end_date,
            $cohort_type         = "install",
            $aggregation_type    = "cumulative",
            $group               = "site_id,install_publisher_id",
            $fields              = $retention->fields(Retention::TUNE_FIELDS_RECOMMENDED),
            $cohort_interval     = "year_day",
            $filter              = "(install_publisher_id > 0)",
            $response_timezone   = "America/Los_Angeles"
        );

        $this->assertNotNull($response);
        $this->assertEquals(200, $response->getHttpCode());

        $job_id = Retention::parseResponseReportJobId($response);
        $this->assertNotNull($job_id);
        $this->assertTrue(!empty($job_id));
    }

    public function testFetch()
    {
        try {
            $week_ago       = date('Y-m-d', strtotime("-8 days"));
            $yesterday      = date('Y-m-d', strtotime("-1 days"));
            $start_date     = "{$week_ago} 00:00:00";
            $end_date       = "{$yesterday} 23:59:59";

            $retention = new Retention($this->api_key, $validate_fields = true);

            $response = $retention->export(
                $start_date,
                $end_date,
                $cohort_type         = "install",
                $aggregation_type    = "cumulative",
                $group               = "site_id,install_publisher_id",
                $fields              = $retention->fields(Retention::TUNE_FIELDS_RECOMMENDED),
                $cohort_interval     = "year_day",
                $filter              = "(install_publisher_id > 0)",
                $response_timezone   = "America/Los_Angeles"
            );

            $this->assertNotNull($response);
            $this->assertEquals(200, $response->getHttpCode());

            $job_id = Retention::parseResponseReportJobId($response);
            $this->assertNotNull($job_id);
            $this->assertTrue(!empty($job_id));

            $response = $retention->fetch(
                $job_id,
                $verbose = false,
                $sleep = 10
            );

            $report_url = Retention::parseResponseReportUrl($response);
            $this->assertNotNull($report_url);
            $this->assertTrue(!empty($report_url));
        } catch ( Exception $ex ) {
            $this->fail($ex->getMessage());
        }
    }
}
