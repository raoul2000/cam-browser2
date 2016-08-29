<?php

namespace tests\codeception\unit\src\components;

use Yii;
use Codeception\Specify;
use app\components\MountedFs;

class MountedFsTest extends \Codeception\TestCase\Test
{
   use Specify;

  /**
   */
  public function testCreateSuccess()
  {
    $this->specify('creation success', function () {

      $mfs = new MountedFs('test', [
        'type' => 'local',
        'mount-point' => '/some/folder',
        'options' => []
      ]);
    });
  }

  public function testCreateFails()
  {
    $this->specify('the name argument is required', function () {
      $mfs = new MountedFs(NULL, [
        'type' => 'local',
        'mount-point' => '/some/folder',
        'options' => []
      ]);
    },[
      'throws' => [
        'yii\base\InvalidConfigException',
        "the 'name' property is missing"
      ]
    ]);

    $this->specify('the "type" property is required', function () {
      $mfs = new MountedFs('name', []);
    },[
      'throws' => [
        'yii\base\InvalidConfigException',
        "the 'type' property is missing"
      ]
    ]);

    $this->specify('the "mount-point" property is required', function () {
      $mfs = new MountedFs('name', [
        'type' => 'local'
      ]);
    },[
      'throws' => [
        'yii\base\InvalidConfigException',
        "the 'mount-point' property is missing"
      ]
    ]);

    $this->specify('the "options" property is required', function () {
      $mfs = new MountedFs('name', [
        'type' => 'local',
        'mount-point' => '/a/b/c'
      ]);
    },[
      'throws' => [
        'yii\base\InvalidConfigException',
        "the 'options' property is missing"
      ]
    ]);

    $this->specify('the "options" property must be an array', function () {
      $mfs = new MountedFs('name', [
        'type' => 'local',
        'mount-point' => '/a/b/c',
        'options' => 'some value'
      ]);
    },[
      'throws' => [
        'yii\base\InvalidConfigException',
        "the 'options' property must be an array"
      ]
    ]);

  }
}
