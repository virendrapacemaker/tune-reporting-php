<?php
/**
 * AdvertiserReportActuals.php
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
 * @author    Jeff Tanner <jefft@tune.com>
 * @copyright 2015 TUNE, Inc. (http://www.tune.com)
 * @package   tune_reporting_api
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version   $Date: 2015-11-17 09:18:01 $
 * @link      https://developers.mobileapptracking.com/tune-reporting-sdks @endlink
 *
 */

namespace TuneReporting\Api;

use TuneReporting\Base\Endpoints\AdvertiserReportActualsBase;

/**
 * TUNE Reporting API endpoint '/advertiser/stats/'
 *
 * @example ExampleAdvertiserReportActuals.php
 */
class AdvertiserReportActuals extends AdvertiserReportActualsBase
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct(
            "advertiser/stats",
            $filter_debug_mode = true,
            $filter_test_profile_id = true
        );

        /*
         * Fields recommended in suggested order.
         */
        $this->fields_recommended = array(
            "site_id"
           ,"site.name"
           ,"publisher_id"
           ,"publisher.name"
           ,"ad_impressions"
           ,"ad_impressions_unique"
           ,"ad_clicks"
           ,"ad_clicks_unique"
           ,"paid_installs"
           ,"paid_installs_assists"
           ,"non_installs_assists"
           ,"paid_events"
           ,"paid_events_assists"
           ,"non_events_assists"
           ,"paid_opens"
           ,"paid_opens_assists"
           ,"non_opens_assists"
        );
    }
}
