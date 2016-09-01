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
         'name' => "apple/file0.jpg",
         'mtime' => "2016/01/28 17:23"
       ],
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
      $result = $vfs->ls('/');

      //codecept_debug($result);

      expect("an array is returned", is_array($result))->true();
      expect("contains 3 elements", count($result))->equals(4);

      $found = array_filter($result,function($item){
        return $item['path'] === 'file0.jpg' && $item['type'] === "file";
      });
      expect("file0.jpg is found",is_array($found) && count($found) == 1 )->true();

      $found = array_filter($result,function($item){
        return $item['path'] === 'poire' && $item['type'] === 'dir';
      });
      expect("poire folder is found",is_array($found) && count($found) == 1 )->true();

      $found = array_filter($result,function($item){
        return $item['path'] === 'pomme' && $item['type'] === 'dir';
      });
      expect("pomme folder is found",is_array($found) && count($found) == 1)->true();

      $found = array_filter($result,function($item){
        return $item['basename'] === 'BANANA' && $item['type'] === 'mount';
      });
      expect("BANANA mount is found",is_array($found) && count($found) == 1)->true();
    });


    $this->specify('list /BANANA fs content', function () use ($vfs){
      $result = $vfs->ls('/BANANA');

      //codecept_debug($result);

      expect("an array is returned", is_array($result))->true();
      expect("contains 2 elements", count($result))->equals(2);

      $found = array_filter($result,function($item){
        return $item['path'] === 'fish' && $item['type'] === 'dir';
      });
      expect("fish folder is found",is_array($found) && count($found) == 1 )->true();

      $found = array_filter($result,function($item){
        return $item['path'] === 'nuts' && $item['type'] === 'dir';
      });
      expect("nuts folder is found",is_array($found) && count($found) == 1)->true();
    });
  }


}
