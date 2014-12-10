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
 * @author    Jeff Tanner <jefft@tune.com>
 * @copyright 2014 Tune (http://www.tune.com)
 * @package   tune_reporting_helpers
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version   $Date: 2014-12-10 11:17:09 $
 * @link      https://developers.mobileapptracking.com/tune-reporting-sdks @endlink
 *
 */

namespace TuneReporting\Helpers;

/**
 * Base abstract class for handling report reading that is appropriate by its content format.
 */
abstract class ReportReaderBase
{
    /**
     * Download url of report ready for export.
     * @var null|string $report_url
     */
    protected $report_url = null;

    /**
     * Extracted data from report contents.
     * @var null|array $report_data
     */
    protected $report_data = null;

    /**
     * Size of extracted data of raw report.
     * @var null|integer $report_data_size
     */
    protected $report_data_size = null;

    /**
     * Constructor
     *
     * @param string $report_url Download report URL of requested report to be exported.
     */
    public function __construct($report_url)
    {
        // report_url
        if (!is_string($report_url) || empty($report_url)) {
            throw new \InvalidArgumentException("Parameter 'report_url' is not defined.");
        }

        $this->report_url = $report_url;
    }

    /**
     * Using provided report download URL, extract contents appropriate to the content's format.
     *
     * @return mixed
     */
    abstract public function read();

    /**
     * Using report data pulled from remote file referenced by download URL, provide a pretty print of it's contents.
     *
     * @param int $limit Limit the number of rows to print.
     *
     * @return mixed
     */
    abstract public function prettyPrint($limit = 0);


    /**
     * Report URL provided by export status upon completion.
     *
     * @return string
     */
    public function getReportUrl()
    {
        return $this->report_url;
    }

    /**
     * Property providing the contents of exported report.
     *
     * @return array
     */
    public function getReportData()
    {
        return $this->report_data;
    }

    /**
     * Property providing the number of rows within contents of exported report.
     *
     * @return int Number of rows
     */
    public function getReportDataCount()
    {
        return count($this->report_data);
    }

    /**
     * Property providing the number of rows within contents of exported report.
     *
     * @return int Size of exported report data
     */
    public function getReportDataSize()
    {
        return $this->report_data_size;
    }

    /**
     * Reads entire report referenced by exported download URL into a string
     *
     * @return string The function returns the read data or false on failure.
     */
    public function retrieveRemoteFile()
    {
        $this->report_data = file_get_contents($this->report_url);
    }

    /**
     * Determines size of report referenced by exported download URL.
     *
     * @return bool|integer Size of report
     */
    public function retrieveRemoteFileSize()
    {
        static $regex = '/^Content-Length: *+\K\d++$/im';
        if (!$fp = @fopen($this->report_url, 'rb')) {
            $this->report_data_size = -1;
        } elseif (isset($http_response_header)
            && preg_match($regex, implode("\n", $http_response_header), $matches)
        ) {
            $this->report_data_size = (int)$matches[0];
        } else {
            $this->report_data_size = strlen(stream_get_contents($fp));
        }
    }
}
