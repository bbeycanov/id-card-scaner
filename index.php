<?php

use GuzzleHttp\Exception\GuzzleException;

require 'vendor/autoload.php';

require 'Ocr.php';

require 'IdCardParser.php';

$idCardParser = new IdCardParser();

/**
 *  Example photo
 */

try {
    $dataPhoto = $idCardParser->parsePhoto('uploads/ocr.jpeg');
} catch (GuzzleException $e) {
    print_r($e->getMessage());
}

echo '<pre>';
print_r($dataPhoto);
echo '</pre>';
/**
 *  Example text
 */
try {
    $dataText = $idCardParser
        ->setText("IAAZEAA197294805V77GY2<<<<<<<<9606195M3010068AZE<<<<<<<<<<<8BAYJANOV<<BAYJAN<<<<<<<<<<<<<<")
        ->parseText();
} catch (GuzzleException $e) {
    print_r($e->getMessage());
}
echo '<pre>';
print_r($dataText);
echo '</pre>';