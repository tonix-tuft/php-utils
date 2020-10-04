<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Tonix\PHPUtils\HTMLUtils;
use Tonix\PHPUtils\StringUtils;

echo '<strong>Unescaped strong tag</strong>';
echo "<br>";

echo HTMLUtils::escape(
  '<strong>' .
    StringUtils::sprintfn('Some random value: %key$s', [
      'key' => StringUtils::generateRandomMD5ChunkNotWithinString(
        md5(microtime())
      ),
    ]) .
    '</strong>'
);
