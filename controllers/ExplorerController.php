<?php

namespace app\controllers;

use Yii;
use  app\components\Fs;
use  app\components\VFSHelper;

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
      $path = Fs::normalizePath($path);
      return $this->render('browse',[
        'parent' => Fs::dirname($path),
        'path' => $path,
        'baseUrl' => Yii::$app->fs->getBaseUrl($path),
        'list' => Yii::$app->fs->ls($path) //['jpg','txt']
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

    public function actionVfs($path='/')
    {
      $path = VFSHelper::normalizePath($path);

      $vfs = Yii::createObject([
        'class' => 'app\components\VFS',
        'root' => [
          'type' => 'local',
          'options'  => [
            'rootPath' => '@runtime'
          ]
        ],
        'mount' => [
          [
            'name' => 'SAMPLE',
            'type' => 'local',
            'mount-point' => '/sample-data/WEB/assets/2bb476ee',
            'options' => [
              'rootPath' => '@webroot'
            ]
          ],
          [
            'name' => 'WEB',
            'type' => 'local',
            'mount-point' => '/',
            'options' => [
              'rootPath' => '@webroot'
            ]
          ]
        ]
      ]);
      $content = $vfs->ls($path);
      return $this->render('vfs',[
        'content' => $content,
        'path' => $path
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
