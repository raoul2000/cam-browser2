<?php

namespace app\components;

use Yii;
use \yii\base\Object;
use yii\helpers\FileHelper;

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
      $this->mfsRoot = new MountedFs(MountedFs::ROOT_NAME,$config['root']);
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
   * Results are returned in an array with the following structure :
   * [
   *    mountedFs : the MountedFs object instance
   *    path : (string) the path relative to the mounted FS instance
   * ]
   * @param  string $path the path to search for (e.g. /a/b/c/d)
   * @return array       at index 0 the MountedFs instance, at index 1 the path to
   * apply to it.
   */
  public function findReference($path)
  {
    $mountedFs = $this->getRootMountedFs(); // by default consider the root fs
    $path      = VFSHelper::normalizePath($path);

    if( $this->getMountTable() == null) {
      if( strpos($path, '/') === 0) {
        $path = substr($path,1);
      }
      $relativePath = $path;
    } else {

      $parts = explode('/',$path);
      for ($i=count($parts)-1; $i >= 0; $i--) {

        $mountName    = $parts[$i];  // name of the mounted FS to search for
        $mountPoint   = implode('/', array_slice($parts,0,$i)); // path to folder the mounted fs is attached to
        $mountPoint   = $mountPoint == '' ? '/' : $mountPoint;  // normalize mount point to '/'
        $relativePath = implode('/', array_slice($parts,$i+1)); // path relative to the mountedFs

        if(  $mountName != '' ){
          $searchResult = $this->getMountTable()->find($mountName, $mountPoint);
          if( $searchResult != null) {
            $mountedFs = $searchResult;
            break;
          }
        }
      }
    }

    return [$mountedFs, $relativePath];
  }
  /**
   * Returns a list of files and folder inside a path.
   *
   * @param  string $folderPath the folder to list
   * @return array         list of object representing files and folders
   */
  public function ls($folderPath = NULL)
  {

    $folderPath = $folderPath === NULL ? '/' : VFSHelper::normalizePath($folderPath);

    list($mountedFs, $relativePath) = $this->findReference($folderPath);
    $fileSystem = $mountedFs->getFileSystem();
    $result = $fileSystem->listContents($relativePath);

    // add the VFS absolute path for each item
    foreach ($result as &$item) {
      $item['vfspath'] = ($folderPath === '/' ? '' : $folderPath) . '/' . $item['basename'];
      if( $item['type'] === 'file') {
        $item['mimetype'] =  FileHelper::getMimeTypeByExtension($item['basename']);
      }
    }


    // add FS mounted to the path to list if there are some
    $mountList = $this->getMountTable()->findByMountPoint($folderPath);
    foreach ($mountList as $mount) {
      $result[] = [
        'type'     => 'mount',
        'path'     => $relativePath . '/' . $mount->getName() ,
        'basename' => $mount->getName(),
        'dirname'  => $relativePath,
        'vfspath'  => ($folderPath === '/' ? '': $folderPath . '/') . $mount->getName()
      ];
    }
    return $result;
  }

  public function read($filePath)
  {
    $filePath = VFSHelper::normalizePath($filePath);
    //$dir = VFSHelper::dirname($filePath);
    list($mountedFs, $relativePath) = $this->findReference($filePath);
    $fileSystem = $mountedFs->getFileSystem();
    return  $fileSystem->read($relativePath);

  }
}
