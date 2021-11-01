<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Tonix\PHPUtils\IOUtils;

$tmpFile = IOUtils::tmpFile();
$tmpFile2 = IOUtils::tmpFile([
  'prefix' => 'prefix_',
  'suffix' => '.suffix',
]);
$tmpFile3 = IOUtils::tmpFile([
  'prefix' => 'somefile_',
  'suffix' => '.txt',
]);
$tmpFile4 = IOUtils::tmpFile([
  'prefix' => 'anotherfile_',
  'suffix' => '.extension',
  'subdir' =>
    IOUtils::TMP_SUBDIR . '/a/b/c/d/e/f/g/h/i/l/m/n/o/p/q/r/s/t/u/v/w/x/y/z',
]);

echo PHP_EOL;
echo json_encode(
  [
    'tmpFile' => $tmpFile,
    'tmpFile2' => $tmpFile2,
    'tmpFile3' => $tmpFile3,
    'tmpFile4' => $tmpFile4,
  ],
  JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
);
echo PHP_EOL;

echo PHP_EOL;
