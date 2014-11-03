<?php
/**
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
 * @version   0.9.12
 * @link      https://developers.mobileapptracking.com @endlink
 *
 */

namespace Tune\Management\Api\Advertiser\Stats;

use Tune\Management\Shared\Endpoints\ReportsLogsEndpointBase;

/**
 * Tune Management API controller 'advertiser/stats/installs'
 *
 * @package Tune\Management\Api\Advertiser\Stats
 *
 * @example ExampleReportsInstalls.php
 */
class Installs extends ReportsLogsEndpointBase
{
    /**
     * Constructor
     *
     * @param string $api_key           Tune MobileAppTracking API Key.
     * @param bool   $validate_fields   Validate fields used by actions' parameters.
     */
    public function __construct(
        $api_key,
        $validate_fields = false
    ) {
        parent::__construct(
            "advertiser/stats/installs",
            $api_key,
            $filter_debug_mode = true,
            $filter_test_profile_id = true,
            $validate_fields
        );

        /*
         * Fields recommended in suggested order.
         */
        $this->fields_recommended = array(
             "id"
            ,"created"
            ,"status"
            ,"site_id"
            ,"site.name"
            ,"publisher_id"
            ,"publisher.name"
            ,"advertiser_ref_id"
            ,"advertiser_sub_campaign_id"
            ,"advertiser_sub_campaign.ref"
            ,"publisher_sub_campaign_id"
            ,"publisher_sub_campaign.ref"
            ,"user_id"
            ,"device_id"
            ,"os_id"
            ,"google_aid"
            ,"ios_ifa"
            ,"ios_ifv"
            ,"windows_aid"
            ,"referral_url"
            ,"is_view_through"
        );
    }
}
