<?php
/**
 * TestReportsCohort.php, Tune SDK PHPUnit Test
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
 * @version   $Date: 2014-11-19 07:02:45 $
 * @link      https://developers.mobileapptracking.com @endlink
 *
 */

require_once dirname(__FILE__) . "/../src/TuneApi.php";

use \Tune\Management\Api\Advertiser\Stats\LTV;

class TestReportsCohort extends \PHPUnit_Framework_TestCase
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
        $reports_cohort = new LTV($this->api_key, $validate_fields = true);

        $fields = $reports_cohort->fields();
        $this->assertNotNull($fields);
        $this->assertNotEmpty($fields);
    }

    /**
     * Test fields
     */
    public function testFieldsEndpoint()
    {
        $reports_cohort = new LTV($this->api_key, $validate_fields = true);

        $fields = $reports_cohort->fields(LTV::TUNE_FIELDS_ENDPOINT);
        $this->assertNotNull($fields);
        $this->assertNotEmpty($fields);
    }

    /**
     * Test fields
     */
    public function testFieldsDefault()
    {
        $reports_cohort = new LTV(
            $this->api_key,
            $validate_fields = true
        );

        $fields = $reports_cohort->fields(LTV::TUNE_FIELDS_DEFAULT);
        $this->assertNotNull($fields);
        $this->assertNotEmpty($fields);
    }

    /**
     * Test fields
     */
    public function testFieldsRecommended()
    {
        $reports_cohort = new LTV($this->api_key, $validate_fields = true);

        $fields = $reports_cohort->fields(LTV::TUNE_FIELDS_RECOMMENDED);
        $this->assertNotNull($fields);
        $this->assertNotEmpty($fields);
    }

    /**
     * Test fields
     */
    public function testFieldsDefaultMinimal()
    {
        $reports_cohort = new LTV($this->api_key, $validate_fields = true);

        $fields = $reports_cohort->fields(LTV::TUNE_FIELDS_DEFAULT | LTV::TUNE_FIELDS_MINIMAL);
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

        $reports_cohort = new LTV($this->api_key, $validate_fields = true);

        $response = $reports_cohort->fields();
        $this->assertNotNull($response);

        $response = $reports_cohort->count(
            $start_date,
            $end_date,
            $cohort_type         = "click",
            $cohort_interval     = "year_day",
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

        $reports_cohort = new LTV($this->api_key, $validate_fields = true);

        $response = $reports_cohort->find(
            $start_date,
            $end_date,
            $cohort_type         = "click",
            $cohort_interval     = "year_day",
            $aggregation_type    = "cumulative",
            $fields              = null,
            $group               = "site_id,publisher_id",
            $filter              = "(publisher_id > 0)",
            $limit               = 5,
            $page                = null,
            $sort                = null,
            $format              = "csv",
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

        $reports_cohort = new LTV($this->api_key, $validate_fields = true);

        $response = $reports_cohort->find(
            $start_date,
            $end_date,
            $cohort_type         = "click",
            $cohort_interval     = "year_day",
            $aggregation_type    = "cumulative",
            $fields              = $reports_cohort->fields(LTV::TUNE_FIELDS_DEFAULT),
            $group               = "site_id,publisher_id",
            $filter              = "(publisher_id > 0)",
            $limit               = 5,
            $page                = null,
            $sort                = null,
            $format              = "csv",
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

        $reports_cohort = new LTV($this->api_key, $validate_fields = true);

        $response = $reports_cohort->find(
            $start_date,
            $end_date,
            $cohort_type         = "click",
            $cohort_interval     = "year_day",
            $aggregation_type    = "cumulative",
            $fields              = $reports_cohort->fields(LTV::TUNE_FIELDS_ENDPOINT),
            $group               = "site_id,publisher_id",
            $filter              = "(publisher_id > 0)",
            $limit               = 5,
            $page                = null,
            $sort                = null,
            $format              = "csv",
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

        $reports_cohort = new LTV($this->api_key, $validate_fields = true);

        $response = $reports_cohort->find(
            $start_date,
            $end_date,
            $cohort_type         = "click",
            $cohort_interval     = "year_day",
            $aggregation_type    = "cumulative",
            $fields              = $reports_cohort->fields(LTV::TUNE_FIELDS_RECOMMENDED),
            $group               = "site_id,publisher_id",
            $filter              = "(publisher_id > 0)",
            $limit               = 5,
            $page                = null,
            $sort                = null,
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

        $reports_cohort = new LTV($this->api_key, $validate_fields = true);

        $response = $reports_cohort->export(
            $start_date,
            $end_date,
            $cohort_type         = "click",
            $cohort_interval     = "year_day",
            $aggregation_type    = "cumulative",
            $fields              = null,
            $group               = "site_id,publisher_id",
            $filter              = "(publisher_id > 0)",
            $response_timezone   = "America/Los_Angeles"
        );

        $this->assertNotNull($response);
        $this->assertEquals(200, $response->getHttpCode());

        $job_id = LTV::parseResponseReportJobId($response);
        $this->assertNotNull($job_id);
        $this->assertTrue(!empty($job_id));
    }

    public function testExportRecommended()
    {
        $week_ago       = date('Y-m-d', strtotime("-8 days"));
        $yesterday      = date('Y-m-d', strtotime("-1 days"));
        $start_date     = "{$week_ago} 00:00:00";
        $end_date       = "{$yesterday} 23:59:59";

        $reports_cohort = new LTV($this->api_key, $validate_fields = true);

        $response = $reports_cohort->export(
            $start_date,
            $end_date,
            $cohort_type         = "click",
            $cohort_interval     = "year_day",
            $aggregation_type    = "cumulative",
            $fields              = $reports_cohort->fields(LTV::TUNE_FIELDS_RECOMMENDED),
            $group               = "site_id,publisher_id",
            $filter              = "(publisher_id > 0)",
            $response_timezone   = "America/Los_Angeles"
        );

        $this->assertNotNull($response);
        $this->assertEquals(200, $response->getHttpCode());

        $job_id = LTV::parseResponseReportJobId($response);
        $this->assertNotNull($job_id);
        $this->assertTrue(!empty($job_id));
    }

    /**
     * @large
     */
    public function testFetch()
    {
        try {
            $week_ago       = date('Y-m-d', strtotime("-8 days"));
            $yesterday      = date('Y-m-d', strtotime("-1 days"));
            $start_date     = "{$week_ago} 00:00:00";
            $end_date       = "{$yesterday} 23:59:59";

            $reports_cohort = new LTV($this->api_key, $validate_fields = true);

            $response = $reports_cohort->export(
                $start_date,
                $end_date,
                $cohort_type         = "click",
                $cohort_interval     = "year_day",
                $aggregation_type    = "cumulative",
                $fields              = $reports_cohort->fields(LTV::TUNE_FIELDS_RECOMMENDED),
                $group               = "site_id,publisher_id",
                $filter              = "(publisher_id > 0)",
                $response_timezone   = "America/Los_Angeles"
            );

            $this->assertNotNull($response);
            $this->assertEquals(200, $response->getHttpCode());

            $job_id = LTV::parseResponseReportJobId($response);
            $this->assertNotNull($job_id);
            $this->assertTrue(!empty($job_id));

            $response = $reports_cohort->fetch(
                $job_id,
                $verbose = false,
                $sleep = 10
            );

            $report_url = LTV::parseResponseReportUrl($response);
            $this->assertNotNull($report_url);
            $this->assertTrue(!empty($report_url));
        } catch ( Exception $ex ) {
            $this->fail($ex->getMessage());
        }
    }
}
