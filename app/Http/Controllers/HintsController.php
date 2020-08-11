<?php


namespace App\Http\Controllers;

use App\Services\YandexGeocoder;
use Illuminate\Http\Request;

/**
 * Контроллер подсказок.
 */
class HintsController extends Controller
{
    /** Сервис геокодера. */
    private YandexGeocoder $geocoder;

    /**
     * Конструктор.
     *
     * @param YandexGeocoder $geocoder
     */
    public function __construct(YandexGeocoder $geocoder)
    {
        $this->geocoder = $geocoder;
    }

    /**
     * Поиск подсказок.
     *
     * @param Request $r
     *
     * @return \Response
     */
    public function search(Request $r): \Response
    {
        $term = $r->get('term');

        return \Response::json($this->geocoder->getHints($term));
    }
}
