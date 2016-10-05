<?php

namespace app\actions;

use Yii;
use yii\base\Action;

class BaseAjaxAction extends Action
{
  public  function init()
  {
    $request = Yii::$app->request;
    if ($request->isAjax === false) {
      throw new \yii\web\BadRequestHttpException();
    }
  }

  protected function verifyBodyParamExists($param)
  {
    if( ! is_array($param)) {
      $param = [$param];
    }
    $paramValues = [];
    foreach ($param as $key => $paramName) {
      if( ($paramValues[] = Yii::$app->request->getBodyParam($paramName)) === null) {
        throw new \yii\web\BadRequestHttpException('missing body parameter : '.$paramName);
      }
    }
    return $paramValues;
  }

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
