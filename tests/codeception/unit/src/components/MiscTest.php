<?php

namespace tests\codeception\unit\src\components;

use Yii;
use Codeception\Specify;
use app\components\VFS;

class MiscTest extends \Codeception\TestCase\Test
{
  use Specify;
  public $vfs;

  protected function _before()
  {
    $this->vfs = new VFS([
      'root' => [
        'type' => 'local',
        'options'  => [
          'rootPath' => '@tests/_work/folder1'
        ]
      ],
      'mount' => [
        [
          'name' => 'MOUNTFS1',
          'type' => 'local',
          'mount-point' => '/',
          'options' => [
            'rootPath' => '@tests/_work/folder2'
          ]
        ]
      ]
    ]);
  }

  public function testPathParser()
  {
    // TODO : wip
    // /a/b/c/d => [ '', 'a', 'b', 'c', 'd']
    //  - i = 4   name = d  mount point = /a/b/c  folder =           folder '.' linked to mounted Fs 'd' under '/a/b/c'
    //  - i = 2   name = c  mount point = /a/b    folder = d         folder d linked to mounted fs 'c' under '/a/b'
    //  - i = 3   name = b  mount point = /a      folder = c/d       folder c/d linked to mounted fs 'b' under '/a'
    //  - i = 1   name = a  mount point =         folder = b/c/d     folder b/c/d linked to the mounted FS 'a' under '/'
    //  - i = 0   name =    mount point =         folder = a/b/c/d   folder a/b/c/d linked to the root Fs
    //

    $path = '/folderA/folderB /folderC/FolderD';
    //$path = '/folderA';
    codecept_debug($path);
    $parts = explode('/',$path);
    for ($i=count($parts)-1; $i >= 0; $i--) {
      codecept_debug($i . ' : ' . $parts[$i]);
      $name = $parts[$i];

      // mount path
      $mountPoint = implode('/',array_slice($parts,0,$i));
      $mountPoint = $mountPoint == '' ? '/' : $mountPoint;
      /*
      if($mountPoint == '') {
        $mountPoint = '/';
      }*/
      $folder = implode('/', array_slice($parts,$i+1));

      codecept_debug("name       = ". $name);
      codecept_debug("mountPoint = ". $mountPoint);
      codecept_debug("folder     = ". $folder);
      codecept_debug("-------------------------------");
    }
  }

  public function testPathParser2()
  {
    $path = '/MOUNTFS1';

    $mountedFs = $mountedFsPath = null;
    $parts = explode('/',$path);
    for ($i=count($parts)-1; $i >= 0; $i--) {

      $mountName = $parts[$i];

      // mount path
      $mountPoint  = implode('/', array_slice($parts,0,$i)); // path to folder the mounted fs is attached to
      $mountPoint  = $mountPoint == '' ? '/' : $mountPoint;
      $adapterPath = implode('/', array_slice($parts,$i+1));
      echo "mountPoint = $mountPoint\n";

      if ($mountPoint == '/' && $mountName == '') {
        $mountedFs = $this->vfs->getRootMountedFs();
        $mountedFsPath = $adapterPath;
        break;
      } else if( $this->vfs->getMountTable() != null ){
        $mountedFs = $this->vfs->getMountTable()->find($mountName, $mountPoint);
        if($mountedFs != null) {
          $mountedFsPath = $adapterPath;
          break;
        }
      }
    }

    codecept_debug("mountedFs name       = ". $mountedFs->getName());
    codecept_debug("mountedFsPath        = ". $mountedFsPath);
  }
}
