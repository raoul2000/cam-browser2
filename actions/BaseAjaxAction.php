<?php

namespace app\actions;

use Yii;
use yii\base\Action;

class BaseAjaxAction extends Action
{
  protected function responseSuccess($message, $data)
  {
    Yii::$app->response->statusCode = 200;
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    return [
      'message' => $message,
      'data'    => $data
    ];
  }
  protected function responseError($message, $data, $statusCode)
  {
    Yii::$app->response->statusCode = $statusCode;
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    return [
      'message' => $message,
      'code'    => $statusCode,
      'data'    => $data
    ];
  }
}
