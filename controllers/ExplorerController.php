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

    public function actionBrowse($path="/")
    {
      $path = empty($path) ? '/' : $path;

      $parts = explode('/',$path);
      var_dump($parts);
      
      // TODO : fix this ! get parent path
      //  if path = '/' parent = '/'
      //  if path = '/a' parent = '/'
      //  if path = '/a/b' parent = '/a'
      //
      if(count($parts) == 0) {
        $parent = '/';
      } else {
        array_pop($parts);
        $parent =  implode('/',$parts);
      }
      $fs = Yii::createObject([
        'class'    => \app\components\Fs::className(),
        'basePath' => Yii::getAlias('@app/tests/_work')
      ]);
      return $this->render('browse',[
        'parent' => $parent,
        'path' => $path,
        'list' => $fs->ls($path)
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
