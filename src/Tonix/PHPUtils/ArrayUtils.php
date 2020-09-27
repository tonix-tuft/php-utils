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
 *
 * @author Anton Bagdatyev (Tonix) <antonytuft@gmail.com>
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

  /**
   * Sets a nested array value.
   *
   * @param array $array A reference to a multidimensional array.
   * @param array $keys Variadic arg. The keys and the leaf value (last parameter).
   *                    Each level represents a nested key of the previous level/dimension.
   *                    The last element is the leaf value to use (not a nested key).
   * @return void
   */
  public static function setNestedArrayValue(array &$array, ...$keys) {
    if (empty($keys)) {
      return;
    }

    $value = array_pop($keys);
    if (empty($keys)) {
      return;
    }

    $curr = &$array;
    foreach ($keys as $k) {
      if (is_array($curr) && (!isset($curr[$k]) || !is_array($curr[$k]))) {
        $curr[$k] = [];
      } elseif (!is_array($curr)) {
        $curr = [];
      }

      $curr = &$curr[$k];
    }
    $curr = $value;
  }

  /**
   * Gets a nested value of an array.
   *
   * @param array $array An Array.
   * @param string[]|int[] $nested Nested keys.
   * @return mixed The nested value, or NULL.
   */
  public static function nestedArrayValue($array, $nested = []) {
    $ret = $array;
    foreach ($nested as $key) {
      if (!isset($ret[$key])) {
        return null;
      }
      $ret = $ret[$key];
    }
    return $ret;
  }
}
