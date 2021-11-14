<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class MessangerAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/messenger.js',
    ];
}