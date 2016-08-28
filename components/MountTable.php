<?php

namespace app\components;

use Yii;
use \yii\base\Object;

/**
 *
 */
class MountTable extends Object
{
  private $_mount = [];

  public function __construct($config = [])
  {
    if( isset($config['mount'])&& is_array($config['mount']) ) {
      $this->_mount = $config['mount'];
      unset($config['mount']);
    }
    parent::__construct($config);
  }

  public function init()
  {
    parent::init();        // ... initialization after configuration is applied
    // validate
  }

  public function find($name, $mountPoint)
  {
    foreach ($this->_mount as $item) {
      if( $item['name'] == $name && $item['mount-point'] == $mountPoint) {
        // we have a winner
        return $item;
      }
    }
    return null;
  }
}
