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
 * Hash utilities.
 *
 * @author Anton Bagdatyev (Tonix) <antonytuft@gmail.com>
 */
class HashUtils {
  /**
   * @return array
   */
  protected static function crc64Table() {
    $CRC64Table = [];

    // ECMA polynomial
    $poly64rev = (0xc96c5795 << 32) | 0xd7870f42;

    // ISO polynomial
    // $poly64rev = (0xD8 << 56);
    for ($i = 0; $i < 256; $i++) {
      for ($part = $i, $bit = 0; $bit < 8; $bit++) {
        if ($part & 1) {
          $part = (($part >> 1) & ~(0x8 << 60)) ^ $poly64rev;
        } else {
          $part = ($part >> 1) & ~(0x8 << 60);
        }
      }
      $CRC64Table[$i] = $part;
    }

    return $CRC64Table;
  }

  /**
   * Generates a 64 bit CRC (Cyclic Redundancy Check) checksum for a given string.
   *
   * @see https://www.php.net/manual/ru/function.crc32.php#111699
   *
   * @param string $string The string for which to compute the 64 bit CRC.
   * @param string $format The format of the CRC.
   *                       Available formats are:
   *
   *                           HashUtils::crc64('php'); // afe4e823e7cef190
   *                           HashUtils::crc64('php', '0x%x'); // "0xafe4e823e7cef190"
   *                           HashUtils::crc64('php', '0x%X'); // "0xAFE4E823E7CEF190"
   *                           HashUtils::crc64('php', '%d'); // -5772233581471534704 (signed int)
   *                           HashUtils::crc64('php', '%u'); // 12674510492238016912 (unsigned int)
   *
   * @return string A string representing the 64 bit CRC of the given string.
   */
  public static function crc64($string, $format = '%u') {
    static $CRC64Table;
    if ($CRC64Table === null) {
      $CRC64Table = static::crc64Table();
    }

    $CRC = 0;
    for ($i = 0; $i < strlen($string); $i++) {
      $CRC =
        $CRC64Table[($CRC ^ ord($string[$i])) & 0xff] ^
        (($CRC >> 8) & ~(0xff << 56));
    }
    return sprintf($format, $CRC);
  }
}
