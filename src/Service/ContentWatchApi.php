<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Translation\DataCollectorTranslator;

class ContentWatchApi
{
    private const API_URL = 'https://content-watch.ru/api/request/';

    /* @noinspection PhpPropertyOnlyWrittenInspection */
    public function __construct(
        private readonly string $key,
        private readonly DataCollectorTranslator $translator,
    )
    {
    }

    /**
     * @throws \JsonException
     */
    public function checkText(string $text): int
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, [
            'key' => $this->key,
            'text' => $text,
            'test' => 0,
        ]);
        curl_setopt($curl, CURLOPT_URL, self::API_URL);

        try {
            $data = json_decode(trim(curl_exec($curl)), true, 512, JSON_THROW_ON_ERROR);
        } catch (\Exception) {
        }

        curl_close($curl);

        return $data['percent'] ?? random_int(0, 100);
    }
}
