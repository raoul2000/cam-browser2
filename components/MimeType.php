<?php

namespace app\components;

use Yii;
use yii\helpers\FileHelper;
use yii\helpers\Url;

/**
 *
 */
class MimeType
{

    static public function findByExtension($file)
    {
      return FileHelper::getMimeTypeByExtension($file);
    }

    static public function getIconUrl($file)
    {
      $iconUrl = Url::to('@web/images/mime-type-icons/txt-icon-64x64.png'); // TODO : find a default mime icon
      $mimeType = MimeType::findByExtension($file);
      if( $mimeType !== NULL) {
        $type = explode('/',$mimeType)[1];
        $iconPath = Yii::getAlias("@app/web/images/mime-type-icons/$type-icon-64x64.png");
        if (file_exists($iconPath)) {
          # code...
          $iconUrl = Url::to("@web/images/mime-type-icons/$type-icon-64x64.png");
        }
        return $iconUrl;
      }
    }


}
