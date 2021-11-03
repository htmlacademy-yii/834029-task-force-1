<?php

namespace taskforce\services;

use common\models\City;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use taskforce\models\dto\LocationDto;
use taskforce\models\dto\LocationInfoDto;
use taskforce\models\exceptions\UnsupportedCityException;
use Yii;
use yii\helpers\ArrayHelper;

class GeoCoderService
{
    private Client $client;

    public function __construct()
    {
        $this->client = Yii::$container->get('geocoderClient');;
    }

    public function getLocationByCoordinates(float $latitude, float $longitude): ?LocationInfoDto
    {
        $queryData = $this->getLocationByCoordinatesQueryData($latitude, $longitude);
        $geo_object = $this->getGeoObject($queryData);

        if (!$geo_object) {
            return null;
        }

        return new LocationInfoDto($geo_object['name'], $geo_object['description']);
    }

    public function getLocationByAddress(string $address): ?LocationDto
    {
        if (!$address) {
            return null;
        }

        $queryData = $this->getLocationDtoQueryData($address);
        $geo_object = $this->getGeoObject($queryData);

        if (!$geo_object) {
            return null;
        }

        $city_id = $this->defineCity($geo_object['metaDataProperty']['GeocoderMetaData']['text']);
        if (!$city_id) {
            throw new UnsupportedCityException();
        }

        [$longitude, $latitude] = explode(' ', $geo_object['Point']['pos']);
        return new LocationDto($latitude, $longitude, $city_id);
    }

    private function getGeoObject(array $queryData): ?array
    {
        try {
            $response = $this->client->request(
                'GET',
                '1.x',
                ['query' => $queryData]
            );
        } catch (GuzzleException $e) {
            return null;
        }

        $content = $response->getBody()->getContents();
        $response_data = json_decode($content, true);
        if (!is_array($response_data)) {
            return null;
        }

        return $response_data['response']['GeoObjectCollection']['featureMember'][0]['GeoObject'] ?: null;
    }

    private function getLocationDtoQueryData(string $address): array
    {
        return [
            'apikey' => Yii::$app->params['geocoder_api_key'],
            'geocode' => str_replace(' ', '+', $address),
            'format' => 'json'
        ];
    }

    private function getLocationByCoordinatesQueryData(float $latitude, float $longitude): array
    {
        return [
            'apikey' => Yii::$app->params['geocoder_api_key'],
            'geocode' => $longitude . ', ' . $latitude,
            'format' => 'json'
        ];
    }

    private function defineCity(string $address): ?int
    {
        $cities = ArrayHelper::map(City::find()->all(), 'id', 'name');

        foreach ($cities as $id => $name) {
            if (strpos($address, $name) !== false) {
                return $id;
            }
        }

        return null;
    }
}