<?php

namespace common\models;

use Yii;
use yii\helpers\FileHelper;

class Portfolio extends base\Portfolio
{
    public static function saveFiles(array $files, int $user_id) : void
    {
        $upload_dir = Yii::getAlias('@webroot/uploads/portfolio');

        if(!file_exists($upload_dir)) {
            FileHelper::createDirectory($upload_dir);
        }

        foreach ($files as $file) {
            $new_name = Yii::$app->security->generateRandomString(8) . '.' . $file->getExtension();

            $file->saveAs($upload_dir . '/' . $new_name);

            $portfolio_file = new self();
            $portfolio_file->user_id = $user_id;
            $portfolio_file->source = '/uploads/portfolio/' . $new_name;
            $portfolio_file->save();
        }
    }
}
