<?php

namespace tests\codeception\unit\src\components;

use Yii;
use Codeception\Specify;
use app\components\AdapterFactory;

class AdapterFactoryTest extends \Codeception\TestCase\Test
{
   use Specify;
   public $adapterFactory;

    protected function _before()
    {
      $this->adapterFactory = new AdapterFactory();
    }

    protected function _after()
    {
    }

    // tests
    public function testCreateGeneric()
    {

      $this->specify('creation fails if adaptater type is not known', function ()  {
        $f = new AdapterFactory();
        $f->create('DUMMY',[]);
      },[
        'throws' => [
          'yii\base\InvalidParamException',
          'invalid adaptater type : DUMMY'
        ]
      ]);
    }
    /**
     * create local adapter test
     *
     * @return [type] [description]
     */
    public function testCreateLocal()
    {

      $this->specify('creation fails if rootPath is not provided', function ()  {
        $f = new AdapterFactory();
        $f->create('local',[]);
      },[
        'throws' => [
          'yii\base\InvalidConfigException',
          'missing required option for local adaptater : rootPath'
        ]
      ]);

      $this->specify('creation fails if rootPath is not a directory', function ()  {
        $f = new AdapterFactory();
        $f->create('local',[ 'rootPath' => '/NOT/FOUND']);
      },[
        'throws' => [
          'yii\base\InvalidConfigException',
          'missing required option for local adaptater : rootPath'
        ]
      ]);

    }
}
