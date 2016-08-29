<?php

namespace app\components;

use Yii;
use \yii\base\Object;

/**
 *
 */
class MountTable extends Object
{
  private $mountedFs;
  private $config;

  /**
   *
   * config = [
   *  [
   *    'name' => 'mountName',
   *    'mount-point' => '/some/folder',
   *    'type' => 'local',
   *    'options' => [...]
   *  ],
   *  [ ... ]
   * ]
   * @param array $config list of mounted FS for this table
   */
  public function __construct($config = [])
  {
    if( ! is_array($config)){
      throw new \yii\base\InvalidCallException("invalid type : 'config' must be an array");
    }
    $this->config = $config;
    parent::__construct();
  }

  public function init()
  {
    parent::init();        // ... initialization after configuration is applied

    $this->mountedFs = [];
    foreach ($this->config as $mountCfg) {
      if( ! isset($mountCfg['name'])) {
        throw new \yii\base\InvalidConfigException("mount name is missing");
      }
      $name = $mountCfg['name'];
      unset($mountCfg['name']);
      $mfs  = new MountedFs($name, $mountCfg);
      $existingMFS = $this->find($name,$mfs->getMountPoint());
      if( $existingMFS != NULL) {
        throw new \yii\base\InvalidConfigException("duplicate mounted fs name : $name on ".  $mfs->getMountPoint());
      }
      $this->mountedFs[] = $mfs;
    }
  }

  public function getAll()
  {
    return $this->mountedFs;
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
