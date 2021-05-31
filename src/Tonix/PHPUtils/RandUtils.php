<?php

/*
 * Copyright (c) 2021 Anton Bagdatyev (Tonix)
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation
 * files (the "Software"), to deal in the Software without
 * restriction, including without limitation the rights to use,
 * copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following
 * conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 */

namespace Tonix\PHPUtils;

/**
 * Utilities for randomness.
 *
 * @author Anton Bagdatyev (Tonix) <antonytuft@gmail.com>
 */
class RandUtils {
  /**
   * Generates a random string of ASCII alphanumerical characters ([A-Za-z0-9])
   * of the specified length or a default length of 15 characters.
   *
   * @param int $length The length of the random string (defaults to 15).
   * @return string The generated random string.
   */
  public static function generateRandomStr($length = 15) {
    static $whitelist = [];
    if (empty($whitelist)) {
      $whitelist = array_merge(
        array_map(function ($charASCIICode) {
          return chr($charASCIICode);
        }, range(97, 122)),
        array_map(function ($charASCIICode) {
          return chr($charASCIICode);
        }, range(65, 90)),
        range(0, 9)
      );
    }

    $str = '';
    $lastIndex = count($whitelist) - 1;
    for ($i = 0; $i < $length; $i++) {
      $str .= $whitelist[random_int(0, $lastIndex)];
    }

    return $str;
  }
}
