<?php

namespace tests\codeception\unit\src\components;

use Yii;
use Codeception\Specify;
use app\components\VFS;
use tests\codeception\unit\TestHelper;

class VFSListContentTest extends \Codeception\TestCase\Test
{
   use Specify;
   protected function _before()
   {
     TestHelper::createFolders( [
       [
         'name' => "apple/pomme/file1.jpg",
         'mtime' => "2016/01/28 17:23"
       ],
       [
         'name' => "apple/poire/file2.jpg",
         'mtime' => "2016/01/28 12:30"
       ],
       [
         'name' => "banana/nuts/file2a.jpg",
         'mtime' => "2016/01/28 21:30"
       ],
       [
         'name' => "banana/fish/file3.jpg",
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
  public function testCreateSuccess()
  {
    $vfs = Yii::createObject([
      'class'    => VFS::className(),
      'root' => [
        'type' => 'local',
        'options'  => [
          'rootPath' => '@tests/_work/apple'
        ]
      ],
      'mount' => [
        [
          'name' => 'BANANA',
          'type' => 'local',
          'mount-point' => '/',
          'options' => [
            'rootPath' => '@tests/_work/banana'
          ]
        ]
      ]
    ]);


    $this->specify('list root fs content', function () use ($vfs){
      //$result = $vfs->ls('/BANANA');
      $result = $vfs->ls('/');
      codecept_debug($result);
    });
  }


}
