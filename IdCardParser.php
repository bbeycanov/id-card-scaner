<?php

use GuzzleHttp\Exception\GuzzleException;

class IdCardParser {

    /**
     * @var Ocr $ocr
     */
    protected Ocr $ocr;

    /**
     * @var string $text
     */
    protected string $text;

    /**
     * @var array $data
     */
    protected array $data;

    /**
     * @var array|string[]
     */
    protected array $sexy = [
        'M' => 'Kişi',
        'W' => 'Qadın',
    ];

    public function __construct()
    {
        $this->ocr = new Ocr();
    }

    /**
     * @return array
     */
    public function parseText(): array
    {
        $cleanData = [];

        $explodeString = explode("<", trim($this->getText()));

        foreach($explodeString as $value) {
            if (!empty($value)) {
                $cleanData[] = $value;
            }
        }

        $national = substr($cleanData[0],2, 3);
        $idCard = substr($cleanData[0],5, 9);
        $finCode = substr($cleanData[0],15, 7);
        $birthDate = substr($cleanData[1], 0, 6);
        $sex = $this->sexy[substr($cleanData[1], 7, 1)];
        $expireDate = substr($cleanData[1], 8, 6);
        $surname = ltrim($cleanData[2], $cleanData[2][0]);
        $name = $cleanData[3];

        $cleanBirthDate = DateTime::createFromFormat('ymd', $birthDate);
        $cleanExpireDate = DateTime::createFromFormat('ymd', $expireDate);

        return [
            'national' => $national,
            'id_card' => $idCard,
            'fin_code' => $finCode,
            'name' => $name,
            'surname' => $surname,
            'sex' => $sex,
            'birth_date'=> $cleanBirthDate->format('d-m-Y'),
            'expire_date'=> $cleanExpireDate->format('d-m-Y'),
        ];
    }


    /**
     * @param $imagePath
     * @return array
     * @throws GuzzleException
     */
    public function parsePhoto($imagePath): array
    {

        $data = [];

        $text = $this->ocr->getTextFromGoogle($imagePath);

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

        $this->setText($string);

        return $this->parseText();
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string|null $text
     * @return IdCardParser
     */
    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }
}
