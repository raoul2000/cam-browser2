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
  private $options;

  /**
   *
   * config :  [
   *  'type' => "local",
   *  'baseUrl' => 'http:// ...',
   *  'mount-point' => '/some/folder',
   *  'options' => [
   *      // adapter options
   *  ]
   * ]
   * @param string $name   name of the MountedFs
   * @param array $config set of configuration parameters
   */
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
    if( isset($config['options']) ) {
      $this->options = $config['options'];
      unset($config['options']);
    }
    parent::__construct($config);
  }

  public function init()
  {
    parent::init();        // ... initialization after configuration is applied

    if( ! isset($this->name)) {
      throw new \yii\base\InvalidConfigException("the 'name' property is missing");
    }
    if( ! isset($this->type)) {
      throw new \yii\base\InvalidConfigException("the 'type' property is missing");
    }
    if( ! isset($this->mountPoint)) {
      throw new \yii\base\InvalidConfigException("the 'mount-point' property is missing");
    }

    if( ! isset($this->options)) {
      throw new \yii\base\InvalidConfigException("the 'options' property is missing");
    } elseif ( ! is_array($this->options)) {
      throw new \yii\base\InvalidConfigException("the 'options' property must be an array");
    }
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
