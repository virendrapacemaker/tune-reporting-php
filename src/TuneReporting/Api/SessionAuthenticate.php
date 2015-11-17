<?php
/**
 * SessionAuthenticate.php
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

use TuneReporting\Base\Endpoints\EndpointBase;

use TuneReporting\Helpers\TuneSdkException;
use TuneReporting\Helpers\TuneServiceException;

/**
 * Provides status of report export request, and upon completion provides
 * download url.
 */
class SessionAuthenticate extends EndpointBase
{
    /**
     * Constructor
     *
     * @param string $api_key MobileAppTracking API Key
     */
    public function __construct()
    {
        parent::__construct(
            "session/authenticate",
            false
        );
    }

    /**
     * No recommended fields assigned to this endpoint.
     *
     * @return null
     */
    public function getFieldsRecommended()
    {
        return null;
    }

    /**
     * Generate session token is returned to provide access to service.
     *
     * @param string $api_keys  Generate 'session token' for this api_keys.
     *
     * @return object
     * @throws InvalidArgumentException
     */
    public function api_key(
        $api_keys
    ) {
        if (!is_string($api_keys) || empty($api_keys)) {
            throw new \InvalidArgumentException("Parameter 'api_keys' is not defined.");
        }

        return parent::call(
            $action = "api_key",
            $map_query_string = array (
                'api_keys' => $api_keys
            )
        );
    }
}
