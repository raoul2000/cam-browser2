<?php

namespace tests\codeception\unit\src\components;

use Yii;
use Codeception\Specify;
use app\components\VFS;
use tests\codeception\unit\TestHelper;

class VFSListContent2Test extends \Codeception\TestCase\Test
{
   use Specify;
   protected function _before()
   {
     TestHelper::createFolders( [
       [
         'name' => "blue/file0.jpg",
         'mtime' => "2016/01/28 17:23"
       ],
       [
         'name' => "blue/apple/cow/cow_1.jpg",
         'mtime' => "2016/01/28 17:23"
       ],
       [
         'name' => "blue/apple/apple_1.jpg",
         'mtime' => "2016/01/28 12:30"
       ],
       [
         'name' => "green/banana/file2a.jpg",
         'mtime' => "2016/01/28 21:30"
       ],
       [
         'name' => "black/fish/file3.jpg",
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
    // being given the folder structure created on _before
    // this definition produces the following VFS
    $vfs = Yii::createObject([
      'class'    => VFS::className(),
      'root' => [
        'type' => 'local',
        'options'  => [
          'rootPath' => '@tests/_work'
        ]
      ],
      'mount' => [
        [
          'name' => 'BlackMounted',
          'type' => 'local',
          'mount-point' => '/blue',
          'options' => [
            'rootPath' => '@tests/_work/black'
          ]
        ]
      ]
    ]);


    $this->specify('list root fs content', function () use ($vfs){
      $result = $vfs->ls('/blue/BlackMounted/fish');

      codecept_debug($result);

      expect("an array is returned", is_array($result))->true();
      expect("contains 3 elements", count($result))->equals(4);
    });

  }


}
