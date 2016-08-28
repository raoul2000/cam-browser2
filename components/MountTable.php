<?php

namespace app\components;

use Yii;
use \yii\base\Object;

/**
 *
 */
class MountTable extends Object
{
  private $mountedFs = [];

  public function __construct($config = [])
  {
    foreach ($config as $mountCfg) {
      if( ! isset($mountCfg['name'])) {
        throw new \yii\base\InvalidConfigException("mount name is missing");
      }
      $name = $mountCfg['name'];
      // TODO : a mounted FS is identified by its name and mount point
      if( ! isset($this->mountedFs[$name])) {
        throw new \yii\base\InvalidConfigException("duplicate mounted fs name : $name");
      }
      unset($mountCfg['name']);
      $this->mountedFs[$name] = new MountedFs($name, $mountCfg);
    }
    parent::__construct();
  }

  public function init()
  {
    parent::init();        // ... initialization after configuration is applied
    // validate
  }

  public function findByMountPoint($mountPoint)
  {
    $result = [];
    foreach ($this->mountedFs as $mfs) {
      if ($mfs->getMountPoint() == $mountPoint) {
        $result[] = $mfs;
      }
    }
    return $result;
  }
  
  public function find($name, $mountPoint)
  {
    foreach ($this->mountedFs as $mfs) {
      if ($mfs->getName() == $name && $mfs->getMountPoint() == $mountPoint) {
        return $mfs;
      }
    }
    return null;
  }
}
