<?php
/**
 * UnittestCohort.php, Tune SDK PHPUnit Test
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
 * @version   0.9.1
 * @link      https://developers.mobileapptracking.com Tune Developer Community @endlink
 *
 */

namespace Tune\Unittests\Management\Api\Advertiser\Reports\Logs;

require_once dirname(dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))))) . "/lib/TuneAutoloader.php";

use \Tune\Management\Api\Advertiser\Stats\LTV;

class UnittestCohort extends \PHPUnit_Framework_TestCase
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
        $week_ago       = date('Y-m-d', strtotime("-8 days"));
        $yesterday      = date('Y-m-d', strtotime("-1 days"));
        $start_date     = "{$week_ago} 00:00:00";
        $end_date       = "{$yesterday} 23:59:59";

        $ltv = new LTV($this->api_key, $validate = true);

        $response = $ltv->getFields();
        $this->assertNotNull($response);

        $response = $ltv->count(
            $start_date,
            $end_date,
            $cohort_type         = "click",
            $group               = "site_id,campaign_id,publisher_id",
            $interval            = "year_day",
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

        $ltv = new LTV($this->api_key, $validate = true);

        $response = $ltv->find(
            $start_date,
            $end_date,
            $cohort_type         = "click",
            $aggregation_type    = "cumulative",
            $group               = "site_id,campaign_id,publisher_id",
            $interval            = "year_day",
            $filter              = "(publisher_id > 0)",
            $fields              = "site_id,site.name,campaign_id"
            . ",campaign.name,publisher_id,publisher.name"
            . ",installs,events,purchases,opens,cpi,rpi,epi"
            . ",opi,currency_code",
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

        $ltv = new LTV($this->api_key, $validate = true);

        $response = $ltv->export(
            $start_date,
            $end_date,
            $cohort_type         = "click",
            $aggregation_type    = "cumulative",
            $group               = "site_id,campaign_id,publisher_id",
            $interval            = "year_day",
            $filter              = "(publisher_id > 0)",
            $fields              = "site_id,site.name,campaign_id"
            . ",campaign.name,publisher_id,publisher.name"
            . ",installs,events,purchases,opens,cpi,rpi,epi"
            . ",opi,currency_code",
            $response_timezone   = "America/Los_Angeles"
        );

        $this->assertNotNull($response);
        $this->assertEquals(200, $response->getHttpCode());
    }
}
