<?php

namespace tests\codeception\unit;

use Yii;
use Codeception\Specify;
use app\components\Fs;

class FsTest extends \Codeception\TestCase\Test
{
   use Specify;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testCreate()
    {
      $this->specify('creation fails if base path does not exist', function () {
        $fs = Yii::createObject([
          'class'    => Fs::className(),
          'basePath' => "INVALID"
        ]);
      },['throws' => 'yii\base\InvalidConfigException']);

      $this->specify('creation fails if base path is not a directory', function () {
        $fs = Yii::createObject([
          'class'    => Fs::className(),
          'basePath' => __FILE__
        ]);
      },['throws' => 'yii\base\InvalidConfigException']);
    }

    public function testLs()
    {
      $this->specify('list folder content', function () {
        $fs = Yii::createObject([
          'class'    => Fs::className(),
          'basePath' => Yii::getAlias('@tests/_work')
        ]);

        $ls = $fs->ls();
        codecept_debug($ls);
      });
    }

}
