<?php

use GuzzleHttp\Client as Http;
use GuzzleHttp\Exception\GuzzleException;

class Ocr
{
    const GOOGLE_URL    = 'https://vision.googleapis.com/v1/images:annotate';

    const OCR_SPACE_URL = 'https://api.ocr.space/parse/image';

    const OCR_SPACE_KEY = '33eade853c88957';

    const GOOGLE_KEY    = 'AIzaSyCZODwtCsOLfaCeAOe3J3z079GxVzcfwRE';

    /**
     * @throws GuzzleException
     */
    public function getTextFromOcrSpace($image)
    {
        $response = $this->http()->request('POST', self::OCR_SPACE_URL, [
            'multipart' => [
                [
                    'name' => 'file',
                    'contents' => fopen($image, 'r'),
                ]
            ],
            'headers' => [
                'apikey' => self::OCR_SPACE_KEY
            ]
        ]);

        $body = json_decode($response->getBody(), true);

        return $body['ParsedResults'][0]['ParsedText'] ?? null;
    }

    /**
     * @param $image_path
     * @return mixed|null
     * @throws GuzzleException
     */
    public function getTextFromGoogle($image_path)
    {
        $params = [
            'requests' => [
                [
                    "features" => [
                        [
                            "maxResults" => 50,
                            "type" => "DOCUMENT_TEXT_DETECTION"
                        ]
                    ],
                    "image" => [
                        'content' => base64_encode(file_get_contents($image_path))
                    ]
                ]
            ]
        ];


        $http = new Http([
            'headers' => ['Content-Type' => 'application/json']
        ]);

        $response = $http->post(self::GOOGLE_URL . '?key=' . self::GOOGLE_KEY, ['body' => json_encode($params)]);

        $body = json_decode($response->getBody(), true);


        return $body['responses'][0]['fullTextAnnotation']['text'] ?? null;
    }


    /**
     * @return Http
     */
    public function http(): Http
    {
        return new Http();
    }
}
