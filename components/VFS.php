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
      $this->mfsRoot = new MountedFs('/',$config['root']);
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
  /**
   * Returns the MountedFs instance configured as root of this VFS
   * @return MountedFs The MountedFs instance for the root
   */
  public function getRootMountedFs()
  {
    return $this->mfsRoot;
  }

  /**
   * Returns the configured mount fs table.
   * @return MountTable|null the configured MountTable instance or NULL if none
   * has been configured.
   */
  public function getMountTable()
  {
    return $this->mountTable;
  }

  /**
   * Being given a path, this method returns the corresponding mounted FS
   * instance (as configured) and the path to apply to it.
   *
   * @param  string $path the path to search for (e.g. /a/b/c/d)
   * @return array       at index 0 the MountedFs instance, at index 1 the path to
   * apply to it.
   */
  public function findReference($path)
  {
    $mountedFs = $mountedFsPath = null;
    $parts = explode('/',$path);
    for ($i=count($parts)-1; $i >= 0; $i--) {
      $mountName = $parts[$i];

      // mount path
      $mountPoint  = implode('/', array_slice($parts,0,$i)); // path to folder the mounted fs is attached to
      $adapterPath = implode('/', array_slice($parts,$i+1));
      if ($mountPoint == '') {
        $mountedFs = $this->getRootMountedFs();
        $mountedFsPath = $adapterPath;
      } else if( $this->getMountTable() != null ){
        $mountedFs = $this->getMountTable()->find($mountName, $mountPoint);
        $mountedFsPath = $adapterPath;
      }
    }
    return [$mountedFs, $mountedFsPath];
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