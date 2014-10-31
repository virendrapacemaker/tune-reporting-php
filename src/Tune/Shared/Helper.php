<?php
/**
 * Helper.php
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
 * @package   Tune_API_PHP
 * @author    Jeff Tanner <jefft@tune.com>
 * @copyright 2014 Tune (http://www.tune.com)
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version   0.9.10
 * @link      https://developers.mobileapptracking.com @endlink
 *
 */

/**
 * @param $array
 * @return bool
 */
function isAssoc($array)
{
    return ($array !== array_values($array));
}

/**
 * @param $str
 * @return string|void
 */
function addQuotes($str)
{
    return sprintf("'%s'", $str);
}

/**
 * @param $glue
 * @param $array
 * @return string|void
 */
function implodeQuotes($glue, $array)
{
    return implode($glue, array_map('addQuotes', $array));
}

/**
 * @param int $int
 * @return bool
 */
function isEven($int)
{
    return !( ( ( int ) $int ) & 1 );
}

/**
 * @param string $haystack
 * @param string $needle
 *
 * @return bool
 */
function startsWith($haystack, $needle)
{
    return $needle === "" || strpos($haystack, $needle) === 0;
}

/**
 * @param string $haystack
 * @param string $needle
 *
 * @return string
 */
function starts($haystack, $needle)
{
    return substr($haystack, 0, strlen($haystack) - strlen($needle));
}


/**
 * @param string $haystack
 *
 * @return string
 */
function starts_field($haystack)
{
    return substr($haystack, 0, strpos($haystack, "."));
}

/**
 * @param string $haystack
 * @param string $needle
 *
 * @return bool
 */
function endsWith($haystack, $needle)
{
    return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
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

/**
 * Check if PHP has JSON extension.
 *
 * @return bool
 */
function isJsonInstalled()
{
    if (in_array('json', get_loaded_extensions())) {
        return true;
    } else {
        return false;
    }
}

/**
 * Check if PHP has pthreads extension.
 *
 * @return bool
 */
function isPThreadsInstalled()
{
    if (in_array('pthreads', get_loaded_extensions())) {
        return true;
    } else {
        return false;
    }
}

/**
 * @param $str
 *
 * @return bool
 */
function isParenthesesBalanced($str)
{
    $count = 0;
    $length = strlen($str);
    for ($i = 0; $i < $length; $i++) {
        if ($str[$i] == '(') {
            $count += 1;
        } elseif ($str[$i] == ')') {
            $count -= 1;
        }
        if ($count == -1) {
            return false;
        }
    }
    return $count == 0;
}


function arrayToString($array)
{
    $str="";
    foreach ($array as $k => $i) {
        if (is_array($i)) {
            $str .= array2string($i);
        } else {
            $str .= " " . $i;
        }
    }
    return $str;
}
