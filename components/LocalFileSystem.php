<?php

namespace app\components;

use Yii;

class FileSystem extends yii\base\Object {

  private $_rootFolder;

  public function __construct($_rootFolder, $config = [])
  {
     // ... initialization before configuration is applied
     // test
     parent::__construct($config);
   }

   public function init()
   {
     parent::init();
   }

   public function getRootFolder()
   {
     return $this->_rootFolder;
   }

   public function ls()
   {

   }
}
