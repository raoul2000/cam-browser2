<?php

namespace app\actions;

use Yii;
use yii\base\Action;

class RmAction extends BaseAjaxAction
{
  public function run($path)
  {
    return $this->response(true, 2200, "message", [ 'attr' => 'value']);
  }
}
