<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class YandexMapAsset extends AssetBundle
{
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
    public $js = [
        'https://api-maps.yandex.ru/2.1/?apikey=e666f398-c983-4bde-8f14-e3fec900592a&lang=ru_RU',
    ];
}