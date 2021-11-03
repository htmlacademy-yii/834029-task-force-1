<?php
\Yii::$container->set('geocoderClient', function ($container, $params, $config) {
    return new GuzzleHttp\Client(['base_uri' => 'https://geocode-maps.yandex.ru/']);
});