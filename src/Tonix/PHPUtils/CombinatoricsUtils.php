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
 * Combinatorics utilities.
 *
 * @author Anton Bagdatyev (Tonix) <antonytuft@gmail.com>
 */
final class CombinatoricsUtils {
  /**
   * Generates unique, progressive and incremental combinations for the given array.
   *
   * Example:
   *
   * ```
   * use Tonix\PHPUtils\CombinatoricsUtils;
   *
   * foreach (CombinatoricsUtils::uniqueProgressiveIncrementalCombinations([1, 2, 3]) as $combination) {
   *     // 1st iteration -> $combination = [1];
   *     // 2nd iteration -> $combination = [2];
   *     // 3rd iteration -> $combination = [1, 2];
   *     // 4th iteration -> $combination = [3];
   *     // 5th iteration -> $combination = [1, 3];
   *     // 6th iteration -> $combination = [2, 3];
   *     // 7th iteration -> $combination = [1, 2, 3];
   * }
   * ```
   *
   * @param array $array An array to use to generate the combinations.
   * @return \Generator A generator yielding to the next combination.
   */
  public static function uniqueProgressiveIncrementalCombinations($array) {
    $results = [[]];
    foreach ($array as $values) {
      foreach ($results as $combination) {
        $newCombination = array_merge($combination, [$values]);
        array_push($results, $newCombination);
        yield $newCombination;
      }
    }
  }
}
