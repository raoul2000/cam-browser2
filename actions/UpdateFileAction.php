<?php

namespace app\actions;

use Yii;
use yii\base\Action;

class UpdateFileAction extends BaseAjaxAction
{

  public function run()
  {
    $request = Yii::$app->request;
    if( $request->isPost === false) {
      throw new \yii\web\BadRequestHttpException();
    }
    //throw new \yii\web\BadRequestHttpException();

    // verify expected POST params exist and if yes, read values
    list($filepath, $content) = $this->verifyBodyParamExists(['filepath', 'content']);
    //sleep(1);
    if( Yii::$app->VFS->update($filepath, $content) ) {
      return $this->responseSuccess("file updated", [ 'filepath' => $filepath] );
    }
  }
}
