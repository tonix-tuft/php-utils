<?php

use Tonix\PHPUtils\CombinatoricsUtils;

require_once __DIR__ . '/../vendor/autoload.php';

$permutations = [];
$permutationsGenerator = CombinatoricsUtils::permute([1, 2, 3, 4]);
foreach ($permutationsGenerator as $permutation) {
  $permutations[] = $permutation;
}

echo PHP_EOL;
echo json_encode(
  [
    'permutations' => $permutations,
  ],
  JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
);
echo PHP_EOL;

echo PHP_EOL;
