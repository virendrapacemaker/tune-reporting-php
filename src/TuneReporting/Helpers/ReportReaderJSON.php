<?php
/**
 * ReportReaderJSON.php
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

namespace TuneReporting\Helpers;

/**
 * Reads remote report referenced by download url with content in JSON format.
 */
class ReportReaderJSON extends ReportReaderBase
{
    /**
     * Constructor
     *
     * @param string $report_url Download report URL of requested report to be exported.
     */
    public function __construct($report_url)
    {
        parent::__construct($report_url);
    }

    /**
     * Using provided report download URL, extract contents appropriate to the content's format.
     *
     * @return bool|mixed
     * @throws TuneSdkException
     */
    public function read()
    {

        $success = false;

        try {
            $this->retrieveRemoteFileSize();

            ini_set('memory_limit', '1024M'); // or you could use 1G

            $content=file_get_contents($this->report_url);
            $this->report_data=json_decode($content);

            $success = true;
        } catch (Exception $ex) {
            throw new \TuneReporting\Helpers\TuneSdkException("Failed to process request.", $ex->getCode(), $ex);
        }

        return $success;
    }

    /**
     * Using report data pulled from remote file referenced by download URL, provide a pretty print of it's contents.
     *
     * @param int $limit Limit the number of rows to print.
     *
     * @return mixed
     */
    public function prettyPrint($limit = 0)
    {
        echo "Report REPORT_URL: " . $this->report_url . PHP_EOL;
        echo "Report total row count: " . count($this->report_data) . PHP_EOL;
        echo "Report data size: " . count($this->report_data_size) . PHP_EOL;

        if (count($this->report_data) > 0) {
            echo "------------------" . PHP_EOL;
            $row_index = 1;
            foreach ($this->report_data as $row) {
                $json_string = defined('JSON_PRETTY_PRINT') ? json_encode($row, JSON_PRETTY_PRINT) : json_encode($row);
                $json_row = str_replace(array("\r", "\n"), " ", $json_string);
                echo "{$row_index}. {$json_row}" . PHP_EOL;
                $row_index++;
                if (($limit > 0) && ($row_index > $limit)) {
                    break;
                }
            }
            echo "------------------" . PHP_EOL;
        }
    }
}
