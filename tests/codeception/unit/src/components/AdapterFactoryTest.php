<?php

namespace tests\codeception\unit\src\components;

use Yii;
use Codeception\Specify;
use app\components\AdapterFactory;

class AdapterFactoryTest extends \Codeception\TestCase\Test
{
   use Specify;

   protected function _after()
   {
     \tests\codeception\unit\TestHelper::deleteFolders();
   }

  /**
   * Merge default argument with options, both being provided as array
   * and extracted as variables.
   */
  public function testMergeArrays()
  {
    $default = [
      'writeFlags'   => LOCK_EX,
      'linkHandling' => \League\Flysystem\Adapter\Local::DISALLOW_LINKS,
      'permissions'  =>  [
          'file' => [
              'public'  => '0644',
              'private' => '0600',
          ],
          'dir' => [
              'public'  => '0755',
              'private' => '0700',
          ]
      ]
    ];
    $options = [
      'rootPath' => '/a/b/c',
      'linkHandling' => 'VAL',
      'permissions'  =>  [
          'file' => [
              'public'  => '0666',
          ]
      ]
    ];

    $arg = \yii\helpers\ArrayHelper::merge($default, $options);
    //\Codeception\Util\Debug::debug($arg);
    extract($arg);

    expect('rootPath variable is set', $rootPath)->equals('/a/b/c');
    expect('linkHandling variable is set', $linkHandling)->equals('VAL');
    expect('permissions variable is set', isset($permissions))->true();
    expect('permissions to be overwrittent by options', $permissions['file']['public'])->equals('0666');
    //\Codeception\Util\Debug::debug($rootPath);
    //\Codeception\Util\Debug::debug($permissions);
  }

  public function testCreateGeneric()
  {

    $this->specify('creation fails if adaptater type is not known', function ()  {
      $f = new AdapterFactory();
      AdapterFactory::create('DUMMY',[]);
    },[
      'throws' => [
        'yii\base\InvalidParamException',
        'invalid adaptater type : DUMMY'
      ]
    ]);
  }
  /**
   * create local adapter test
   */
  public function testCreateLocal()
  {

    $this->specify('creation fails if rootPath is not provided', function ()  {
      $f = new AdapterFactory();
      AdapterFactory::create('local',[]);
    },[
      'throws' => [
        'yii\base\InvalidConfigException',
        'missing required option for local adaptater : rootPath'
      ]
    ]);


    $this->specify('creation fails if rootPath is not a valid directoy value', function ()  {
      $f = new AdapterFactory();
      AdapterFactory::create('local',[ 'rootPath' => '*NOT/FOUND/file.txt']);
    },[
      'throws' => [
        'League\Flysystem\Exception',
        'Impossible to create the root directory "*NOT/FOUND/file.txt".'
      ]
    ]);


    $this->specify('rootPath folder is created if not exist', function ()  {
      $path = Yii::getAlias('@tests/_work');
      if( is_dir($path)){
        \yii\helpers\FileHelper::removeDirectory($path);
      }

      verify('folder does not exist', is_dir($path))->false();

      $f = new AdapterFactory();
      AdapterFactory::create('local',[ 'rootPath' => $path]);

      expect('folder to exist', is_dir($path))->true();
    });


    $this->specify('rootPath can be an alias', function ()  {
      $f = new AdapterFactory();
      AdapterFactory::create('local',[ 'rootPath' => '@tests/_work']);
      expect('folder @tests/_work to exist', is_dir(Yii::getAlias('@tests/_work')))->true();
    });

  }
}
