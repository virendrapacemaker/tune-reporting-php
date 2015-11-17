<?php
/**
 * Utils.php
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
 * @category    TUNE_Reporting
 *
 * @author      Jeff Tanner <jefft@tune.com>
 * @copyright   2015 TUNE, Inc. (http://www.tune.com)
 * @package     tune_reporting_base_service
 * @license     http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version     $Date: 2015-11-17 09:18:01 $
 * @link        https://developers.mobileapptracking.com/tune-reporting-sdks @endlink
 *
 */

/**
 * Determine if array is associative.
 *
 * @param $array
 * @return bool
 */
function isAssoc($array)
{
    return ($array !== array_values($array));
}

/**
 * Check if PHP has curl extension.
 *
 * @return bool
 */
function isCurlInstalled()
{
    if (in_array('curl', get_loaded_extensions())) {
        return true;
    } else {
        return false;
    }
}
