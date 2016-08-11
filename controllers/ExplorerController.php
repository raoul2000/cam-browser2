<?php

namespace app\controllers;

use Yii;
use  app\components\Fs;

class ExplorerController extends \yii\web\Controller
{
    public function actions()
    {
      return [
        'rm' => [
          'class' => 'app\actions\RmAction'
        ]
      ];
    }

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

    public function actionBrowse($path="/")
    {
      $fs = Yii::createObject([
        'class'    => \app\components\Fs::className(),
        'basePath' => Yii::getAlias('@runtime/sample-data'),
        'baseUrl'  => 'http://localhost/devws/lab/cam-browser2/runtime/sample-data'
      ]);
      return $this->render('browse',[
        'parent' => Fs::dirname($path),
        'path' => $path,
        'baseUrl' => $fs->getBaseUrl($path),
        'list' => $fs->ls($path) //['jpg','txt']
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

    public function actionDeleteFile($path)
    {
      \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
      sleep(1);

      $filepath = Yii::$app->params['folder'] . '/' . $path;
      if( !file_exists($filepath)){
        return [
          'error' => TRUE,
          'message' => 'file not found'
        ];
      } else if( FALSE ){ //! unlink($filepath)
        return [
          'error' => TRUE,
          'message' => 'failed to delete file ' . $filepath
        ];
      } else {
        return [
          'error' => FALSE,
          'message' => 'file deleted'
        ];
      }
    }

}
