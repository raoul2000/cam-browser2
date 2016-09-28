<?php

namespace tests\codeception\unit\src\components;

use Yii;
use Codeception\Specify;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local;
use tests\codeception\unit\TestHelper;

class FlySystemTest extends \Codeception\TestCase\Test
{
   use Specify;
   protected function _before()
   {
     TestHelper::createFolders( [
         [
           'name' => "folder/file1.jpg",
           'mtime' => "2016/01/28 17:23"
         ],
         [
           'name' => "file2.jpg",
           'mtime' => "2016/01/28 12:30"
         ],
         [
           'name' => "file2a.jpg",
           'mtime' => "2016/01/28 21:30"
         ],
         [
           'name' => "file3.jpg",
           'mtime' => "2015/12/01 22:54"
         ]
       ]
     );
   }

   protected function _after()
   {
     TestHelper::deleteFolders();
   }
  /**
   */
  public function testLocalAdapter()
  {
    /*
    $this->specify('play with a local adapter', function () {
      $path = Yii::getAlias('@tests/_fly-test');

      $adapter = new Local(TestHelper::getWorkFolderPath());
      $filesystem = new Filesystem($adapter);
      $contents = $filesystem->listContents('',true);
      codecept_debug($contents);
    });
*/
  }

  public function testFTPAdapter()
  {
    $this->specify('play with a local adapter', function () {

      $filesystem = new Filesystem(new \League\Flysystem\Adapter\Ftp([
         'host' => '127.0.0.1',
          'username' => 'username',
          'password' => 'password',
          /*
          'host' => 'u58396481.1and1-data.host',
          'username' => 'u58396481-sl-ctrl',
          'password' => '********',
*/
          'port' => 7002,
          'root' => '/'
          //'passive' => true,
          //'ssl' => false,
          //'timeout' => 30,
      ]));

      //$contents = $filesystem->listContents('codeception/_output',true);
      $contents = $filesystem->listContents('/');
      //codecept_debug($contents);
      //$filesystem->read('codeception/_output');
      //$filesystem->read('codeception/_output/file2.jpg');
      //$content = $filesystem->read('ping.db');
      //codecept_debug($content);
      //$filesystem->read('codeception.yml');
    });

  }
}
