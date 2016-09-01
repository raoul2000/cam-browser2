<?php

namespace tests\codeception\unit\src\components;

use Yii;
use Codeception\Specify;
use app\components\VFS;
use app\components\MountedFs;
use tests\codeception\unit\TestHelper;

class VFSFindeReferenceTest extends \Codeception\TestCase\Test
{
   use Specify;

   public function testFindReference()
   {

     $vfs = new VFS([
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

     $this->specify('find reference for path /', function () use($vfs){
       list($mountedFs, $fsPath) = $vfs->findReference('/');

       verify("a mounted FS has been found", $mountedFs)->notNull();
       verify("a path has been found"      , $fsPath   )->notNull();

       verify("mounted FS is the root FS", $mountedFs->getName())->equals(MountedFs::ROOT_NAME);
       verify("the path is correct"      , $fsPath   )->equals('');
     });

     $this->specify('find reference for path /file1.jpg', function () use($vfs){
       list($mountedFs, $fsPath) = $vfs->findReference('/file1.jpg');

       verify("a mounted FS has been found", $mountedFs)->notNull();
       verify("a path has been found"      , $fsPath   )->notNull();

       verify("mounted FS is the root FS", $mountedFs->getName())->equals(MountedFs::ROOT_NAME);
       verify("the path is correct"      , $fsPath   )->equals('file1.jpg');
     });

     $this->specify('find reference for path /MOUNTFS1', function () use($vfs){
       list($mountedFs, $fsPath) = $vfs->findReference('/MOUNTFS1');

       verify("a mounted FS has been found", $mountedFs)->notNull();
       verify("a path has been found"      , $fsPath   )->notNull();

       verify("mounted FS is 'MOUNTFS1'", $mountedFs->getName())->equals('MOUNTFS1');
       verify("the path is correct"     , $fsPath   )->equals('');
     });

     $this->specify('find reference for path /MOUNTFS1/sub-folder2', function () use($vfs){
       list($mountedFs, $fsPath) = $vfs->findReference('/MOUNTFS1/sub-folder2');

       verify("a mounted FS has been found", $mountedFs)->notNull();
       verify("a path has been found"      , $fsPath   )->notNull();

       verify("mounted FS is 'MOUNTFS1'", $mountedFs->getName())->equals('MOUNTFS1');
       verify("the path is correct"     , $fsPath   )->equals('sub-folder2');
     });

     $this->specify('find reference for path /folder1/sub-folder1/file20.jpg', function () use($vfs){
       list($mountedFs, $fsPath) = $vfs->findReference('/folder1/sub-folder1/file20.jpg');

       verify("a mounted FS has been found", $mountedFs)->notNull();
       verify("a path has been found"      , $fsPath   )->notNull();

       verify("mounted FS is '/'"   , $mountedFs->getName())->equals(MountedFs::ROOT_NAME);
       verify("the path is correct" , $fsPath   )->equals('folder1/sub-folder1/file20.jpg');
     });

     $this->specify('find reference for path /a/b/../c/d/../../end', function () use($vfs){
       list($mountedFs, $fsPath) = $vfs->findReference('/a/b/../c/d/../../end');

       verify("a mounted FS has been found", $mountedFs)->notNull();
       verify("a path has been found"      , $fsPath   )->notNull();

       verify("mounted FS is the root FS", $mountedFs->getName())->equals(MountedFs::ROOT_NAME);
       verify("the path is correct"      , $fsPath   )->equals('a/end');
     });
     
   }



   public function testFindReferenceNoMount()
   {

     $vfs = new VFS([
       'root' => [
         'type' => 'local',
         'options'  => [
           'rootPath' => '@tests/_work/folder1'
         ]
       ]
     ]);

     $this->specify('find reference for path /', function () use($vfs){
       list($mountedFs, $fsPath) = $vfs->findReference('/');

       verify("a mounted FS has been found", $mountedFs)->notNull();
       verify("a path has been found"      , $fsPath   )->notNull();

       verify("mounted FS is the root FS", $mountedFs->getName())->equals(MountedFs::ROOT_NAME);
       verify("the path is correct"      , $fsPath   )->equals('');
     });


     $this->specify('find reference for path /a/b/c', function () use($vfs){
       list($mountedFs, $fsPath) = $vfs->findReference('/a/b/c');

       verify("a mounted FS has been found", $mountedFs)->notNull();
       verify("a path has been found"      , $fsPath   )->notNull();

       verify("mounted FS is the root FS", $mountedFs->getName())->equals(MountedFs::ROOT_NAME);
       verify("the path is correct"      , $fsPath   )->equals('a/b/c');
     });

     $this->specify('find reference for path /a/b/../c/d/../../end', function () use($vfs){
       list($mountedFs, $fsPath) = $vfs->findReference('/a/b/../c/d/../../end');

       verify("a mounted FS has been found", $mountedFs)->notNull();
       verify("a path has been found"      , $fsPath   )->notNull();

       verify("mounted FS is the root FS", $mountedFs->getName())->equals(MountedFs::ROOT_NAME);
       verify("the path is correct"      , $fsPath   )->equals('a/end');
     });
   }

}
