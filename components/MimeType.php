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
      $iconUrl = Url::to('@web/images/mime-type-icons/txt-icon-48x48.png'); // TODO : find a default mime icon
      $mimeType = MimeType::findByExtension($file);
      if( $mimeType !== NULL) {
        $type = explode('/',$mimeType)[1];
        $iconPath = Yii::getAlias("@app/web/images/mime-type-icons/$type-icon-48x48.png");
        if (file_exists($iconPath)) {
          # code...
          $iconUrl = Url::to("@web/images/mime-type-icons/$type-icon-48x48.png");
        }
        return $iconUrl;
      }
    }


}
