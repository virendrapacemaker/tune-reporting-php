<?php
/**
 * String.php
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
 * @package   tune_reporting_helpers
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version   $Date: 2015-04-08 17:44:36 $
 * @link      https://developers.mobileapptracking.com/tune-reporting-sdks @endlink
 *
 */


/**
 * Wrap string with single-quotes.
 *
 * @param $str
 * @return string|void
 */
function addQuotes($str)
{
  return sprintf("'%s'", $str);
}

/**
 * Create string of value wrapped with single-quotes.
 *
 * @param $glue
 * @param $array
 * @return string|void
 */
function implodeQuotes($glue, $array)
{
  return implode($glue, array_map('addQuotes', $array));
}

/**
 * String starts with sub-string.
 *
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
 * Get sub-string that starts before ending sub-substring.
 *
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
 * Get the field or related property from a string.
 *
 * @param string $haystack
 *
 * @return string
 */
function starts_field($haystack)
{
  return substr($haystack, 0, strpos($haystack, "."));
}

/**
 * String ends with sub-string.
 *
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
 * Validate string has balanced parentheses.
 *
 * @param string $str
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
