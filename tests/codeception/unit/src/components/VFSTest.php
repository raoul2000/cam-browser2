<?php

namespace tests\codeception\unit\src\components;

use Yii;
use Codeception\Specify;
use app\components\VFS;

class VFSTest extends \Codeception\TestCase\Test
{
   use Specify;

  /**
   */
  public function testCreateSuccess()
  {
    $this->specify('minimal VFS creation success', function () {
      $vfs = new VFS([
        'root' => [
          'type' => 'local',
          'mount-point' => '/some/folder',
          'options' => []
        ]
      ]);
      verify("VFS contains the root mounted FS", $vfs->getRootMountedFs())->notNull();
      verify("VFS has an empty mount table"    , $vfs->getMountTable())->null();
    });

    $this->specify('VFS creation success', function () {
      $vfs = new VFS([
        'root' => [
          'type' => 'local',
          'mount-point' => '/some/folder',
          'options' => []
        ],
        'mount' => [
          [
            'name' => 'local1',
            'type' => 'local',
            'mount-point' => '/some/folder1',
            'options' => []
          ]
        ]
      ]);
      verify("VFS contains the root mounted FS", $vfs->getRootMountedFs())->notNull();
      verify("VFS has an empty mount table"    , $vfs->getMountTable())->notNull();
    });
  }

  public function testCreateFails()
  {
    $this->specify('VFS creation fails if no root fs is configured', function () {
      $vfs = new VFS([
        'mount' => []
      ]);
    },[
      'throws' => [
        'yii\base\InvalidConfigException',
        "root filesystem configuration is missing"
       ]
    ]);

    $this->specify('VFS creation fails if root mounted fs is also declared in the mount table', function () {
      $vfs = new VFS([
        'root' => [
          'type' => 'local',
          'mount-point' => '/some/folder',
          'options' => []
        ],
        'mount' => [
          [
            'name' => 'local',
            'type' => 'local',
            'mount-point' => '/some/folder',
            'options' => []
          ]
        ]
      ]);
    },[
      'throws' => [
        'yii\base\InvalidConfigException',
        "root filesystem duplicate declaration"
       ]
    ]);

  }
}
