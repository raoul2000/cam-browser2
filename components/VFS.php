<?php

namespace app\components;

use Yii;
use \yii\base\Object;

/**
 * Virtual File System implentation
 */
class VFS extends Object
{
    private $root;
    private $mountTable;

    public function __construct($config = [])
    {
      if( isset($config['root']) && is_array($config['root'])) {
        $this->root = new MountedFs('root',$config['root']);
        unset($config['root']);
      }

      if( isset($config['mount']) && is_array($config['mount'])) {
        $this->mountTable = new MountTable($config['mount']);
        unset($config['mount']);
      }
      parent::__construct($config);
    }

    public function init()
    {
      parent::init();        // ... initialization after configuration is applied
      if( !isset($this->root)) {
        throw new \yii\base\InvalidConfigException("root filesystem configuration is missing ");
      }
    }

    public function getRoot()
    {
      return $this->root;
    }

    public function getMountTable()
    {
      return $this->mountTable;
    }

    

  }
