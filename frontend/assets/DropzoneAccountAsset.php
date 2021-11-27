<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class DropzoneAccountAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/dropzone.js',
        'js/dropzoneAccountInit.js',
    ];
}