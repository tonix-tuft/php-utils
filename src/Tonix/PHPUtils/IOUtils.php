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

use Tonix\TmpFile;
use Tonix\PHPUtils\Misc\MiscDir;
use Tonix\PHPUtils\RandUtils;

/**
 * I/O utilities.
 *
 * @author Anton Bagdatyev (Tonix) <antonytuft@gmail.com>
 */
class IOUtils {
  /**
   * Subdirectory for temporary files.
   */
  const TMP_SUBDIR = 'tmp';

  /**
   * Creates a path (directory) of an arbitrary depth starting at a given base path (directory).
   *
   * @param string $basePath A base path (a directory's absolute or relative path) from which to start creating
   *                         the path or the nested path.
   * @param string $pathToCreate The path to create (e.g. 'dir') or the nested path (e.g. 'dir/and/subdir' or '/dir/and/subdir',
   *                             the leading slash will be removed).
   *                             Note that if `$pathToCreate` starts with `$basePath`, then only the part after `$basePath` will be created under `$basePath`,
   *                             otherwise the whole `$pathToCreate` path will be created below `$basePath` even if `$pathToCreate` starts with a directory separator
   *                             (i.e. if `$basePath` is '/some/base/path' and `$pathToCreate` is '/some/base/path/dir/and/subdir', the final path created
   *                             will be '/some/base/path/dir/and/subdir', whereas if `$basePath` is '/some/base/path' and `$pathToCreate` is 'some/base/path/dir/and/subdir'
   *                             or '/dir/and/subdir' (in this case, same as 'dir/and/subdir'), then the final path created will be '/some/base/path/some/base/path/dir/and/subdir'
   *                             and '/some/base/path/dir/and/subdir', respectively).
   *                             Below some other examples:
   *
   *                             ```
   *                             createPath('some/dir', 'anotherdir'); // Creates `some/dir/anotherdir` (relative path)
   *                             createPath('/some/dir', 'anotherdir'); // Creates `/some/dir/anotherdir` (absolute path)
   *                             createPath('/a/base/path', '/a/base/path/some/nested/path'); // Creates `/a/base/path/some/nested/path` (`$pathToCreate` starts with `$basePath`)
   *                             createPath('a/base/path', '/a/base/path'); // Creates `a/base/path/a/base/path`
   *                             createPath('/a/base/path', 'some/nested/dir'); // Creates `/a/base/path/some/nested/dir`
   *                             createPath('/a/base/path', '/some/nested/dir'); // Same as the line above, creates `/a/base/path/some/nested/dir`
   *                                                                             // (the leading directory separatorof `$pathToCreate` is trimmed)
   *                             ```
   *
   * @param array $options An array of further options:
   *
   *                           - 'newPathCallback' (callable): A callable to execute for each new path created (each new directory of the path).
   *                                                           The callable will receive the just created path as its first argument;
   *
   * @return bool TRUE if the path was successfully created, FALSE otherwise.
   */
  public static function createPath($basePath, $pathToCreate, $options = []) {
    static $defaults = [
      'newPathCallback' => null,
    ];
    [
      'newPathCallback' => $newPathCallback,
    ] = $options + $defaults;

    if (strpos($pathToCreate, $basePath) === 0) {
      $basePath = rtrim($basePath, DIRECTORY_SEPARATOR);
      $pathRelativeToBasePath = trim(
        substr($pathToCreate, strlen($basePath)),
        DIRECTORY_SEPARATOR
      );
      $pathToCreate = $pathRelativeToBasePath;
    }
    $pathToCreate = trim($pathToCreate, DIRECTORY_SEPARATOR);
    $pathParts = explode(DIRECTORY_SEPARATOR, $pathToCreate);
    $len = count($pathParts);

    $path = $basePath;
    for ($i = 0; $i < $len; $i++) {
      $path .= DIRECTORY_SEPARATOR . $pathParts[$i];
      if (!file_exists($path)) {
        $ok = mkdir($path);
        if (!$ok) {
          return false;
        }
        if (is_callable($newPathCallback)) {
          $newPathCallback($path);
        }
      }
    }
    return true;
  }

  /**
   * Creates a temporary file.
   *
   * @param array $options An array of options. The available options are the following:
   *
   *                           - 'dir' (string|null, defaults to null): The directory where to create the temporary file.
   *                                                                    If null (default), then the result of `MiscDir::getPath(IOUtils::TMP_SUBDIR)` will be used.
   *                                                                    See {@link MiscDir};
   *                           - 'subdir' (string, defaults to ''): A subdirectory to use to annidate within 'dir' (e.g. 'some-subdir' or 'some/subdir').
   *                                                                If 'subdir' is an empty string (default) and 'dir' is null,
   *                                                                then a default `IOUtils::TMP_SUBDIR` will be used as the subdirectory
   *                                                                and it will be annidated below `MiscDir::getPath(IOUtils::TMP_SUBDIR)`;
   *                           - 'prefix' (string, defaults to ''): The prefix of the temporary file;
   *                           - 'suffix' (string, defaults to ''): The suffix of the temporary file (for file extensions, the dot '.' must be used as well, e.g. '.txt', '.pdf', '.zip', etc...);
   *                           - 'autoDelete' (boolean, defaults to true): Whether or not to autodelete the file;
   *                           - 'autoRandomPrefixIfEmpty' (boolean, defaults to true): If TRUE, an automatically generated random prefix will be used when 'prefix' is empty;
   *                           - 'autoRandomSuffixIfEmpty' (boolean, defaults to true): If TRUE, an automatically generated random suffix will be used when 'suffix' is empty;;
   *                           - 'tmpFileClass' (string, defaults to `TmpFile::class`): The FQN of the class to use for the temporary file creation (MUST implement the {@link \Tonix\TmpFileInterface} interface);
   *                           - 'tmpFileConstructParams' (array, defaults to `[]`): An array of constructor parameters to pass to the constructor of the class identified by 'tmpFileClass';
   *                           - 'tmpFile' => (\Tonix\TmpFileInterface, defaults to null): An instance of {@link \Tonix\TmpFileInterface} to use as-is instead of instantiating a new instance using 'tmpFileClass'
   *                                                                                       and 'tmpFileConstructParams';
   *
   * @return string The absolute filename of the created temporary file.
   */
  static function tmpFile($options = []) {
    static $defaults = [
      'dir' => null,
      'subdir' => '',
      'prefix' => '',
      'suffix' => '',
      'autoDelete' => true,
      'autoRandomPrefixIfEmpty' => true,
      'autoRandomSuffixIfEmpty' => true,
      'tmpFileClass' => TmpFile::class,
      'tmpFileConstructParams' => [],
      'tmpFile' => null,
    ];
    [
      'dir' => $dir,
      'subdir' => $subdir,
      'prefix' => $prefix,
      'suffix' => $suffix,
      'autoDelete' => $autoDelete,
      'autoRandomPrefixIfEmpty' => $autoRandomPrefixIfEmpty,
      'autoRandomSuffixIfEmpty' => $autoRandomSuffixIfEmpty,
      'tmpFileClass' => $tmpFileClass,
      'tmpFileConstructParams' => $tmpFileConstructParams,
      'tmpFile' => $tmpFile,
    ] = $options + $defaults;

    $subdir = trim($subdir, DIRECTORY_SEPARATOR);
    if (empty($dir)) {
      if (empty($subdir)) {
        $subdir = self::TMP_SUBDIR;
      }
      $dir = MiscDir::getPath($subdir);
    }

    $tmpDir = rtrim($dir, DIRECTORY_SEPARATOR);
    if (empty($prefix) && $autoRandomPrefixIfEmpty) {
      $prefix = RandUtils::generateRandomStr(10);
    }
    if (empty($suffix) && $autoRandomSuffixIfEmpty) {
      $suffix = RandUtils::generateRandomStr(5);
    }

    if (empty($tmpFile)) {
      /**
       * @var \Tonix\TmpFileInterface $tmpFile
       */
      $tmpFile = new $tmpFileClass(...$tmpFileConstructParams);
      $absoluteFilename = $tmpFile->create(
        $tmpDir,
        $prefix,
        $suffix,
        $autoDelete
      );
    }

    return $absoluteFilename;
  }
}
