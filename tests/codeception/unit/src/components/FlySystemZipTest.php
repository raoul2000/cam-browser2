<?php

namespace tests\codeception\unit\src\components;

use Yii;
use Codeception\Specify;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local;
use tests\codeception\unit\TestHelper;

class FlySystemZipTest extends \Codeception\TestCase\Test
{
   use Specify;
   protected function _before()
   {   }

   protected function _after()
   {   }

  public function testZipAdapter()
  {
    $this->specify('play with a zip adapter', function () {
      $zipFile = Yii::getAlias('@tests/codeception/unit/fixtures/fruits.zip');
      codecept_debug($zipFile);

      $filesystem = new Filesystem(new \League\Flysystem\ZipArchive\ZipArchiveAdapter($zipFile));

      $contents = $filesystem->listContents('');
      $this->assertTrue(is_array($contents));
      $this->assertTrue(count($contents) === 1);
      $this->assertTrue($contents[0]['path'] === 'fruits');
      $this->assertTrue($contents[0]['type'] === 'dir');

      $contents = $filesystem->listContents('/fruits');
      //codecept_debug($contents);
      $this->assertTrue(is_array($contents));
      $this->assertTrue(count($contents) === 2);
      $this->assertTrue($contents[0]['path'] === 'fruits/apple');
      $this->assertTrue($contents[0]['type'] === 'dir');
      $this->assertTrue($contents[0]['basename'] === 'apple');

      $this->assertTrue($contents[1]['type'] === 'file');
      $this->assertTrue($contents[1]['basename'] === 'mango.txt');
      $this->assertTrue($contents[1]['extension'] === 'txt');
    });

  }
}
