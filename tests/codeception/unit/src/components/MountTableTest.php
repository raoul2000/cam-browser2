<?php

namespace tests\codeception\unit\src\components;

use Yii;
use Codeception\Specify;
use app\components\MountTable;

class MountTableTest extends \Codeception\TestCase\Test
{
   use Specify;

  /**
   */
  public function testCreateSuccess()
  {
    $this->specify('creation success', function () {
      $mfs = new MountTable([
        [
          'name' => 'local1',
          'type' => 'local',
          'mount-point' => '/some/folder',
          'options' => []
        ]
      ]);
      verify("mount table contains only one mounted FS",        count($mfs->getAll()))->equals(1);
      verify("mount table contains the 'local1' mounted FS",    $mfs->find('local1','/some/folder'))->notNull();
      verify("mount table does not contains extra mounted FS",  $mfs->find('extra','/some/folder'))->null();
    });

    $this->specify('creation success with empty mounted fs list provided', function () {
      $mfs = new MountTable([]);
      verify("mount table does not contains any mounted FS"     , count($mfs->getAll()))->equals(0);
      verify("mount table does not contains extra mounted FS"   , $mfs->find('extra','/some/folder'))->null();
    });

    $this->specify('creation success with more than one mounted fs ', function () {
      $mfs = new MountTable([
        [
          'name' => 'local1',
          'type' => 'local',
          'mount-point' => '/some/folder1',
          'options' => []
        ],
        [
          'name' => 'local2',
          'type' => 'local',
          'mount-point' => '/some/folder2',
          'options' => []
        ]
      ]);
      verify("mount table contains only 2 mounted FS",        count($mfs->getAll()))->equals(2);
      verify("mount table contains the 'local1' mounted FS",    $mfs->find('local1','/some/folder1'))->notNull();
      verify("mount table contains the 'local2' mounted FS",    $mfs->find('local2','/some/folder2'))->notNull();
      verify("mount table does not contains extra mounted FS",  $mfs->find('extra','/some/folder'))->null();
    });
  }

  public function testCreateFails()
  {
    $this->specify('the name property must be configured ', function () {
      $mfs = new MountTable([
        [
          'type' => 'local',
          'mount-point' => '/some/folder',
          'options' => []
        ]
      ]);
    },[
      'throws' => [
        'yii\base\InvalidConfigException',
        "mount name is missing"
      ]
    ]);


    $this->specify('duplicate mounted FS are forbidden ', function () {
      $mfs = new MountTable([
        [
          'name' => 'myLocalFs',
          'type' => 'local',
          'mount-point' => '/some/folder',
          'options' => []
        ],
        [
          'name' => 'myLocalFs',
          'type' => 'local',
          'mount-point' => '/some/folder',
          'options' => []
        ]
      ]);
    },[
      'throws' => [
        'yii\base\InvalidConfigException',
        "duplicate mounted fs name : myLocalFs on /some/folder"
      ]
    ]);
  }
}
