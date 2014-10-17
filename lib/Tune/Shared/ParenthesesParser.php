<?php
/**
 * ParenthesesParser.php
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
 * @package   Tune_PHP_SDK
 * @author    Jeff Tanner <jefft@tune.com>
 * @copyright 2014 Tune (http://www.tune.com)
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version   0.9.2
 * @link      https://developers.mobileapptracking.com Tune Developer Community @endlink
 *
 */

namespace Tune\Shared;

/**
 * Class ParenthesesParser
 *
 * @package Tune\Shared
 */
class ParenthesesParser
{
    // something to keep track of parentheses nesting
    protected $stack = null;
    // current level
    protected $current = null;

    // input string to parse
    protected $string = null;
    // current character offset in string
    protected $position = null;
    // start of text-buffer
    protected $buffer_start = null;

    /**
     * Parse string with parentheses.
     * @param $string
     *
     * @return array
     */
    public function parse($string)
    {
        if (!$string) {
            // no string, no data
            return array();
        }

        if ($string[0] == '(') {
            // killer outer parentheses, as they're unnecessary
            $string = substr($string, 1, -1);
        }

        $this->current = array();
        $this->stack = array();

        $this->string = $string;
        $this->length = strlen($this->string);
        // look at each character
        for ($this->position=0; $this->position < $this->length; $this->position++) {
            switch ($this->string[$this->position]) {
                case '(':
                    $this->push();
                    // push current scope to the stack an begin a new scope
                    array_push($this->stack, $this->current);
                    $this->current = array();
                    break;
                case ')':
                    $this->push();
                    // save current scope
                    $t = $this->current;
                    // get the last scope from stack
                    $this->current = array_pop($this->stack);
                    // add just saved scope to current scope
                    $this->current[] = $t;
                    break;
                /*
                 case ' ':
                     // make each word its own token
                     $this->push();
                     break;
                 */
                default:
                    // remember the offset to do a string capture later
                    // could've also done $buffer .= $string[$position]
                    // but that would just be wasting resources…
                    if ($this->buffer_start === null) {
                        $this->buffer_start = $this->position;
                    }
            }
        }

        return $this->current;
    }

    /**
     * Push parentheses element into array.
     */
    protected function push()
    {
        if ($this->buffer_start !== null) {
            // extract string from buffer start to current position
            $buffer = substr($this->string, $this->buffer_start, $this->position - $this->buffer_start);
            // clean buffer
            $this->buffer_start = null;
            // throw token into current scope
            $this->current[] = $buffer;
        }
    }
}
