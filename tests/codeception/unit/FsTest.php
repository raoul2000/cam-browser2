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

    public function testDirname()
    {
      expect('returns parent folder', Fs::dirname('/a/b/c'))->equals('/a/b');
      expect('returns parent folder', Fs::dirname('/a/b/c/d/e/file.txt'))->equals('/a/b/c/d/e');
      expect('returns parent folder', Fs::dirname('/a'))->equals('/');
      expect('returns parent folder', Fs::dirname('/'))->equals('/');
      expect('returns parent folder', Fs::dirname('/  '))->equals('/');
      expect('returns parent folder', Fs::dirname('az '))->equals('/');
      expect('returns parent folder', Fs::dirname('//az '))->equals('/');
    }


    public function testNormalizePath()
    {
      expect('returns normalized folder', Fs::normalizePath('/a/b/c'))->equals('/a/b/c');
      expect('returns normalized folder', Fs::normalizePath('/a/b/c/d/e/file.txt'))->equals('/a/b/c/d/e/file.txt');
      expect('returns normalized folder', Fs::normalizePath('/'))->equals('/');
      expect('returns normalized folder', Fs::normalizePath(' /a '))->equals('/a');
      expect('returns normalized folder', Fs::normalizePath(''))->equals('/');
      expect('returns normalized folder', Fs::normalizePath('az '))->equals('/az');
      expect('returns normalized folder', Fs::normalizePath('//az '))->equals('/az');
    }

}
