<?php

namespace Tonix\PHPUtils;

/**
 * Combinatorics utilities.
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
