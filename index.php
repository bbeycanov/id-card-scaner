<?php

use GuzzleHttp\Exception\GuzzleException;

require 'vendor/autoload.php';

require 'Ocr.php';

require 'IdCardParser.php';

$ocr = new Ocr();

$idCardParser = new IdCardParser();

/**
 *  Example photo
 */
try {

    $data = [];

    $text = $ocr->getTextFromGoogle('uploads/ocr.jpeg');

    $explode = explode(PHP_EOL, $text);

    foreach ($explode as $value) {
        if (!empty(trim($value))) {
            $data[] = $value;
        }
    }

    $string = "";

    $data = array_reverse($data);

    $data = array_slice($data, 0, 3);

    $data = array_reverse($data);

    foreach ($data as $value) {
        $string .= str_replace(' ', '', trim($value));
    }

    $dataPhoto = $idCardParser
        ->setText($string)
        ->parseText();

} catch (GuzzleException $e) {
    print_r($e->getMessage());
}

echo '<pre>';
print_r($dataPhoto);
echo '</pre>';


/**
 *  Example text
 */

$dataText = $idCardParser
    ->setText("IAAZEAA197294805V77GY2<<<<<<<<9606195M3010068AZE<<<<<<<<<<<8BAYJANOV<<BAYJAN<<<<<<<<<<<<<<")
    ->parseText();

echo '<pre>';
print_r($dataText);
echo '</pre>';