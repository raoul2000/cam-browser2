<?php

namespace app\controllers;

use Yii;
use  app\components\Fs;
use  app\components\VFSHelper;
use yii\helpers\FileHelper;

class ExplorerController extends \yii\web\Controller
{
    public $vfs;
    public function actions()
    {
      return [
        'rm' => [
          'class' => 'app\actions\RmAction'
        ],
        'update' => [
          'class' => 'app\actions\UpdateFileAction'
        ]
      ];
    }

    public function init()
    {
      $this->vfs = Yii::$app->VFS;
        /*
      $this->vfs = Yii::createObject([
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
            'mount-point' => '/sample-data/WEB/assets',
            'options' => [
              'rootPath' => '@webroot'
            ]
          ],
          [
            'name' => 'WEB',
            'type' => 'local',
            'mount-point' => '/sample-data',
            'options' => [
              'rootPath' => '@webroot'
            ]
          ],
          [
            'name' => 'FTP',
            'type' => 'ftp',
            'mount-point' => '/sample-data',
            'options' => [
              'host' => '127.0.0.1',
              'username' => 'username',
              'password' => 'password',

              'port' => 7002,
              'passive' => true,
              'timeout' => 30,
            ]
          ]
        ]
      ]);
*/

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

      $content = $this->vfs->ls($path);
      $params = [
        'content' => $content,
        'path'    => $path,
        'parent'  => VFSHelper::dirname($path)
      ];
      if(  Yii::$app->request->isAjax ) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $params;
      } else {
        return $this->render('vfs',$params);

      }
    }

    public function actionViewFileContent($path='/')
    {
      $path = VFSHelper::normalizePath($path);
      ////$mimeType = FileHelper::getMimeTypeByExtension($path);
      //\Yii::$app->response->format = FileHelper::getMimeTypeByExtension($path);

      \Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
      return $this->vfs->read($path);
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
