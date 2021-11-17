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
 * String utilities.
 *
 * @author Anton Bagdatyev (Tonix) <antonytuft@gmail.com>
 */
class StringUtils {
  /**
   * Implementation of sprintf with associative keys as arguments.
   *
   * @param string $format The format of the string, as in PHP's `sprintf()` function using `%key$s` as named placeholders.
   * @param array $args An associative array with the arguments to interpolate (e.g. `['key' => 'value']` to interpolate `%key$s`).
   * @param bool $warningOnArgNotFound Whether or not to trigger an E_USER_WARNING if one argument is missing. By default, this method does not trigger a warning
   *                                   and the uninterpolated argument is left in the string as is.
   * @return string|bool The formatted string or a boolean FALSE if the `$warningOnArgNotFound` is truthy and at least one argument is missing.
   */
  public static function sprintfn(
    $format,
    array $args = [],
    $warningOnArgNotFound = false
  ) {
    // Map of argument names to their corresponding sprintf numeric argument value.
    $arg_nums = array_keys($args);
    array_unshift($arg_nums, 0);
    $arg_nums = array_flip(array_slice($arg_nums, 1, null, true));

    $charNotWithinString = null;

    // Find the next named argument. each search starts at the end of the previous replacement.
    $namedKeyRegex = '([a-zA-Z_]\w*)';
    for (
      $pos = 0;
      preg_match(
        '/(?<=%)' . $namedKeyRegex . '(?=\$)/',
        $format,
        $match,
        PREG_OFFSET_CAPTURE,
        $pos
      );

    ) {
      $arg_pos = $match[0][1];
      $arg_len = strlen($match[0][0]);
      $arg_key = $match[1][0];

      // Replace the named argument with the corresponding numeric one.
      $replace = null;
      if (!array_key_exists($arg_key, $arg_nums)) {
        // Programmer did not supply a value for the named argument found in the format string.
        if ($warningOnArgNotFound) {
          user_error(
            "sprintfn(): Missing argument '${arg_key}'",
            E_USER_WARNING
          );
          return false;
        }
        if (is_null($charNotWithinString)) {
          $charNotWithinString = static::generateRandomMD5ChunkNotWithinString(
            $format
          );
        }
        $replace = $charNotWithinString . $arg_key . $charNotWithinString;
      } else {
        $replace = $arg_nums[$arg_key];
      }

      $format = substr_replace($format, $replace, $arg_pos, $arg_len);
      $pos = $arg_pos + strlen($replace); // Skip to end of replacement for next iteration.
    }
    if (!empty($charNotWithinString)) {
      $format = preg_replace(
        '/%' .
          '(' .
          preg_quote($charNotWithinString, '/') .
          $namedKeyRegex .
          preg_quote($charNotWithinString, '/') .
          ')' .
          '\$/',
        '\1',
        $format
      );
      $returnStrTmp = vsprintf($format, array_values($args));
      $returnStr = preg_replace(
        '/' .
          preg_quote($charNotWithinString, '/') .
          '(' .
          $namedKeyRegex .
          ')' .
          preg_quote($charNotWithinString, '/') .
          '/',
        '%\1$',
        $returnStrTmp
      );
    } else {
      $returnStr = vsprintf($format, array_values($args));
    }
    return $returnStr;
  }

  /**
   * Generate a random MD5 chunk which doesn't appear within the string given as parameter.
   *
   * @param string $str The string.
   * @return string An MD5 chunk which is a string which doesn't appear within the given string, i.e. is not a substring of the string given as parameter.
   */
  public static function generateRandomMD5ChunkNotWithinString($str) {
    return static::generateRandomHashChunkNotWithinString($str, 'md5');
  }

  /**
   * Generate a random hash chunk which doesn't appear within the string given as parameter, using the given optional hash function.
   *
   * @param string $str The string.
   * @param callable The hashing function to use. Optional. Defaults to 'sha1'.
   * @return string An hash chunk which is a string which doesn't appear within the given string, i.e. is not a substring of the string given as parameter.
   */
  public static function generateRandomHashChunkNotWithinString(
    $str,
    $hashFunc = 'sha1'
  ) {
    $hash = $hashFunc($str);
    $i = 4;
    while (strpos($str, $hash) !== false) {
      $hash = $hashFunc(static::generateRandomString($i++));
    }
    return $hash;
  }

  /**
   * Generates a random string of the given length which contains the following ASCII characters:
   *
   *      - ASCII characters of the range 97-122 (upper and lower bounds included);
   *      - ASCII characters of the range 65-09 (upper and lower bounds included);
   *      - digits from 0 to 9.
   *
   * @param int $length The length of the string.
   * @return string The randomly generated string.
   */
  public static function generateRandomString($length) {
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
