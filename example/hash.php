<?php

use Tonix\PHPUtils\HashUtils;

require_once __DIR__ . '/../vendor/autoload.php';

echo PHP_EOL;
echo HashUtils::crc64("A");
echo PHP_EOL;

echo PHP_EOL;
echo HashUtils::crc64("ABC");
echo PHP_EOL;

echo PHP_EOL;
echo HashUtils::crc64("dh76toygudjsalh9o7uy7803-908-90");
echo PHP_EOL;

echo PHP_EOL;
echo HashUtils::crc64('php'); // afe4e823e7cef190
echo PHP_EOL;

echo PHP_EOL;
echo HashUtils::crc64('php', '0x%x'); // "0xafe4e823e7cef190"
echo PHP_EOL;

echo PHP_EOL;
echo HashUtils::crc64('php', '0x%X'); // "0xAFE4E823E7CEF190"
echo PHP_EOL;

echo PHP_EOL;
echo HashUtils::crc64('php', '%d'); // -5772233581471534704 (signed int)
echo PHP_EOL;

echo PHP_EOL;
echo HashUtils::crc64('php', '%u'); // 12674510492238016912 (unsigned int)
echo PHP_EOL;

echo PHP_EOL;
