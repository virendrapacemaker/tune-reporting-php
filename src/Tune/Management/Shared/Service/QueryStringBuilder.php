<?php
/**
 * QueryStringBuilder.php
 *
 */

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
 * @package   Tune_API_PHP
 * @author    Jeff Tanner <jefft@tune.com>
 * @copyright 2014 Tune (http://www.tune.com)
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version   0.9.10
 * @link      https://developers.mobileapptracking.com @endlink
 *
 */

namespace Tune\Management\Shared\Service;

/**
 * QueryStringBuilder incrementally builds query string element by element.
 *
 * @package Tune_API_PHP
 */
class QueryStringBuilder
{

    /**
     * Property for holding query string under construction.
     *
     * @var string
     */
    private $query_string = "";

    /**
     * Constructor
     *
     * @param string $name
     * @param mixed $value
     */
    public function __construct(
        $name = null,
        $value = null
    ) {
        if (is_string($name) && !empty($name)) {
            $this->add($name, $value);
        }
    }

    /**
     * Add element to query string.
     *
     * @param string $name
     * @param mixed $value
     */
    public function add($name, $value)
    {

        if (is_null($value)) {
            return;
        }

        if (!$name || !is_string($name)) {
            throw new \InvalidArgumentException("Parameter 'name' must be defined string.");
        }

        $name = trim($name);

        if (empty($name)) {
            throw new \InvalidArgumentException("Parameter 'name' must be defined string.");
        }

        if (is_string($value)) {
            $value = trim($value);
            if (empty($value)) {
                return;
            }
        }

        try {
            if ($name == "fields") {
                $fields_value = preg_replace('/\s+/', '', $value);

                $this->encode($name, $fields_value);
            } elseif ($name == "sort") {
                if (!is_array($value)) {
                    throw new \InvalidArgumentException("Parameter 'sort' value is not a dictionary: {$value}");
                }

                foreach ($value as $sort_field => $sort_direction) {
                    $sort_direction = strtoupper($sort_direction);

                    if ($sort_direction != "ASC" && $sort_direction != "DESC") {
                        throw new \InvalidArgumentException("Invalid sort direction: {$sort_direction}.");
                    }

                    $sort_name = "sort[{$sort_field}]";
                    $sort_value = $sort_direction;

                    $this->encode($sort_name, $sort_value);
                }
            } elseif ($name == "filter") {
                $filter_value = preg_replace('/\s+/', ' ', $value);

                $this->encode($name, $filter_value);
            } elseif ($name == "group") {
                $group_value = preg_replace('/\s+/', '', $value);

                $this->encode($name, $group_value);
            } elseif (is_bool($value)) {
                $bool_value = ($value == true) ? "true" : "false";

                $this->encode($name, $bool_value);
            } else {
                $this->encode($name, $value);
            }

        } catch (Exception $ex) {
            throw new \Tune\Shared\TuneSdkException(
                "Failed to add query string parameter ({$name}, {$value})",
                $ex->getCode(),
                $ex
            );
        }
    }

    /**
     * URL query string element's name and value
     *
     * @param string $name
     * @param mixed $value
     */
    private function encode($name, $value)
    {
        if (is_string($this->query_string) && !empty($this->query_string)) {
            $this->query_string .= "&";
        }

        $this->query_string .= urlencode($name);
        $this->query_string .= "=";
        $this->query_string .= urlencode($value);
    }

    /**
     * Return built query string
     *
     * @return string
     */
    public function getQuery()
    {
        return $this->query_string;
    }

    /**
     * Custom string representation of object
     *
     * @return string
     */
    public function toString()
    {
        return $this->getQuery();
    }
}
