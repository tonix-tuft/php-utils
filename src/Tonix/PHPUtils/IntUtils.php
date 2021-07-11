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
 * Utilities for integers.
 *
 * @author Anton Bagdatyev (Tonix) <antonytuft@gmail.com>
 */
class IntUtils {
  /**
   * Simulates a 32-bit int overflow on the given number.
   *
   * @see https://stackoverflow.com/questions/15557407/how-to-use-a-32bit-integer-on-a-64bit-installation-of-php#answer-55079085
   *
   * @param int|float A number.
   * @return int The 32-bit integer after the overflow.
   */
  public static function intOverflow32Bit($num) {
    $num = $num & 0xffffffff;
    if ($num & 0x80000000) {
      // This is needed for 64-bit versions of PHP in order to return the same value as for 32-bit versions,
      // otherwise `$num` is going to be positive on 64-bit versions of PHP.
      $num = -2147483648 + ($num & ~0x80000000);
    }
    return $num;
  }

  /**
   * Tests whether the given value is an integer or a string representing a valid integer.
   *
   * @param mixed $value A value.
   * @return bool TRUE if the given value is an integer or a string representing a valid integer, FALSE otherwise.
   */
  public static function isIntOrIntString($value) {
    $isPositiveIntString = function (string $value) {
      return ctype_digit($value) && (strlen($value) <= 1 || $value[0] !== '0');
    };
    // prettier-ignore
    return is_int($value) ||
      (
        is_string($value) &&
        (
          $isPositiveIntString($value)
          ||
          (
            $value[0] === '-'
            &&
            $isPositiveIntString(substr($value, 1))
          )
        )
      );
  }
}
