<?php

namespace app\controllers;

use Yii;

class ExplorerController extends \yii\web\Controller
{
    public function actionIndex()
    {
      require_once('../components/browse-folder.php');
      $config = Yii::$app->params;

      $folder = $config['folder'] . '/' . $config['filePattern'];
      $timezone = isset($config['timezone']) && ! empty($config['timezone'])
        ? $config['timezone']
        : null;

      $days = getIndexByDay($folder, $timezone );
      return $this->render('index',[
        'days' => $days
      ]);
    }

    public function actionViewImage($date)
    {
      require_once('../components/select-by-day.php');

      $config = Yii::$app->params;
      $folder = $config['folder'] . '/' . $config['filePattern'];
      $timezone = isset($config['timezone']) && ! empty($config['timezone'])
        ? $config['timezone']
        : null;

      $files = getFilesByDay($date , $folder , $timezone);
      return $this->render('view-image',[
        'files' => $files,
        'date' => $date,
        'config' => $config
      ]);
    }

}
