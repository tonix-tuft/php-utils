<?php

/*
 * Copyright (c) 2020 Anton Bagdatyev (Tonix)
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
 * Array utilities.
 */
final class ArrayUtils {
  /**
   * Maps an array preserving its keys.
   *
   * @param callable $callable A callable.
   * @param array $array An array.
   * @return array The mapped array.
   */
  public static function arrayMapPreserveKeys($callable, $array) {
    return array_combine(array_keys($array), array_map($callable, $array));
  }

  /**
   * Checks whether all the keys given as parameter exist in an array.
   * Each subsequent key is checked in an inner dimension of the given array.
   *
   * @param array $array An array
   * @param string|int ...$keys Variadic argument of the keys to check if they all exist in the given array.
   * @return bool TRUE if all the keys exist, each key on the corresponding dimension of the array, FALSE otherwise.
   */
  public static function arrayKeysExist($array, ...$keys) {
    $current = $array;
    if (empty($keys)) {
      return false;
    }
    foreach ($keys as $key) {
      if (!is_array($current) || !array_key_exists($key, $current)) {
        return false;
      }
      $current = $current[$key];
    }
    return true;
  }
}
