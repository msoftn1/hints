<?php


namespace App\Services;


use App\HintLog;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

/**
 * Сервис геокодирования.
 */
class YandexGeocoder
{
    /**
     * Поиск подсказок.
     *
     * @param string $term
     *
     * @return array
     */
    public function getHints(string $term): array
    {
        $this->logQuery($term);

        $response = Http::get(
            sprintf(
                'https://geocode-maps.yandex.ru/1.x?apikey=%s&results=6&geocode=%s&format=json',
                Config::get('app.yandex_api_key'),
                urlencode($term)
            )
        );

        $data = [];

        if ($response->status() == 200) {
            $json = json_decode($response->body(), true);

            foreach ($json['response']['GeoObjectCollection']['featureMember'] as $featureMember) {
                $data[] = $featureMember['GeoObject']['metaDataProperty']['GeocoderMetaData']['text'];
            }
        }

        return $data;
    }

    /**
     * Логирование запросов.
     *
     * @param string $term
     */
    private function logQuery(string $term): void
    {
        $hintLog = new HintLog();
        $hintLog->query = $term;
        $hintLog->save();
    }
}
