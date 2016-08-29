<?php

namespace app\components;

use Yii;
use \yii\base\Object;

/**
 * Virtual File System implentation
 */
class VFS extends Object
{
  /**
   * the FS mounted as root
   * @var MountedFs
   */
  private $mfsRoot;
  private $mountTable;

  /**
   * Constructor for the VFS class.
   * The configuration array must include at least the 'root' entry to
   * configure the root file system (associated with '/').
   * The optional 'mount' entry is also available to describe all the mounted
   * file system that should be available.
   *
   * @param array $config configuration array
   */
  public function __construct($config = [])
  {
    if( isset($config['root']) && is_array($config['root'])) {
      $this->mfsRoot = new MountedFs('root',$config['root']);
      unset($config['root']);
    }

    if( isset($config['mount']) && is_array($config['mount'])) {
      $this->mountTable = new MountTable($config['mount']);
      unset($config['mount']);
    }
    parent::__construct($config);
  }

  /**
   * Initialize and validate the VFS instance.
   * Validation rules :
   * - a 'root' file system must be configured
   * - if a mount table is configured it should not include a mount point equal
   * to the root mounted fs.
   */
  public function init()
  {
    parent::init();
    if( !isset($this->mfsRoot)) {
      throw new \yii\base\InvalidConfigException("root filesystem configuration is missing");
    }
    if( isset($this->mountTable) && $this->mountTable->findByMountPoint($this->mfsRoot->getMountPoint() ) != null) {
      throw new \yii\base\InvalidConfigException("root filesystem duplicate declaration");
    }
  }

  public function getRootMountedFs()
  {
    return $this->mfsRoot;
  }

  public function getMountTable()
  {
    return $this->mountTable;
  }

  /**
   * Returns a list of files and folder inside a path.
   *
   * @param  string $folder the folder to list
   * @return array         list of object representing files and folders
   */
  public function ls($folder = "/", $filterExtension = null)
  {

  }
}
