<?php

namespace app\components;

use Yii;
use \yii\base\Object;

/**
 *
 */
class MountedFs extends Object
{
  private $name;
  private $type;
  private $baseUrl;
  private $mountPoint;
  private $adapter;
  /**
   * The FlySystem instance to use for this mounted fs
   * @var League\Flysystem\Filesystem
   */
  private $fileSystem;
  private $options = [];

  public function __construct($name, $config)
  {
    $this->name = $name;

    if( isset($config['type']) ) {
      $this->type = $config['type'];
      unset($config['type']);
    }
    if( isset($config['baseUrl']) ) {
      $this->baseUrl = $config['baseUrl'];
      unset($config['baseUrl']);
    }
    if( isset($config['mount-point']) ) {
      $this->mountPoint = $config['mount-point'];
      unset($config['mount-point']);
    }
    if( isset($config['options']) && is_array($config['options']) ) {
      $this->options = $config['options'];
      unset($config['options']);
    }
    parent::__construct($config);
  }

  public function init()
  {
    parent::init();        // ... initialization after configuration is applied
    // validate
  }

  public function getName()
  {
    return $this->name;
  }

  public function getOptions()
  {
    return $this->options;
  }
  public function getType()
  {
    return $this->type;
  }
  public function getBaseUrl()
  {
    return $this->baseUrl;
  }
  public function getMountPoint()
  {
    return $this->mountPoint;
  }

  public function getAdapter()
  {
    if( ! isset($this->adapter)) { // lazy instanciation
      $this->adapter = AdapterFactory::create(
        $this->getType(),
        $this->getOptions()
      );
    }
    return $this->adapter;
  }

  public function getFileSystem()
  {
    if( ! isset($this->fileSystem)) { // lazy instanciation
      $this->fileSystem = \League\Flysystem\Filesystem(
        $this->getAdapter()
      );
    }
    return $this->fileSystem;
  }
}
