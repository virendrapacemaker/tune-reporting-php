<?php
/**
 * TuneReporting.php
 *
 * Autoloading class file locations hierarchy by supplying it with
 * a function to run.
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
 * @author  Jeff Tanner <jefft@tune.com>
 * @copyright 2015 TUNE, Inc. (http://www.tune.com)
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version   $Date: 2015-11-17 09:18:01 $
 * @link    https://developers.mobileapptracking.com/tune-reporting-sdks @endlink
 *
 */

namespace TuneReporting;

/**
 * TUNE SDK Autoloader Class
 *
 */
class TuneApi
{
    /**
     * Constructor
     *
     * When using spl_autoload_register() with class methods, it might seem
     * that it can use only public methods, though it can use private/protected
     * methods as well, if registered from inside the class.
     */
    public function __construct()
    {
        spl_autoload_register(array($this, 'autoloadTuneReporting'));
    }

    /**
     * This function will handle the autoloading of the TUNE namespaced
     * classes.
     *
     * @param string $className The name of the class (with prepended namespace) to load.
     */
    private function autoloadTuneReporting($className)
    {
        // echo 'Trying to load class ', $className, ' via ', __METHOD__, "()\n";
        if (!class_exists($className)) {
            // The namespaces map 1-to-1 with the filepaths, so we can just so a
            // straight conversion.
            $dirname = dirname(__FILE__);
            $file = $dirname . '/' . str_replace('\\', '/', $className) . '.php';

            // echo "file {$file}\n";
            if (!file_exists($file)) {
                return false;
            }

            // do the actual require now that we've converted it into a file path
            include_once $file;
        }
    }
}

$autoloader = new TuneApi();
