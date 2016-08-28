<?php

namespace tests\codeception\unit;

use Yii;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local;
use yii\helpers\FileHelper;
use Codeception\Specify;

class TestHelper
{

  public $sampleFiles = [
    [
      'name' => "folder/file1.jpg",
      'mtime' => "2016/01/28 17:23"
    ],
    [
      'name' => "file2.jpg",
      'mtime' => "2016/01/28 12:30"
    ],
    [
      'name' => "file2a.jpg",
      'mtime' => "2016/01/28 21:30"
    ],
    [
      'name' => "file3.jpg",
      'mtime' => "2015/12/01 22:54"
    ]
  ];

  static public function getWorkFolderPath()
  {
    return Yii::getAlias('@tests/_work');
  }
  /**
   * Creates a test files and folder set under @tests/_work
   *
   * @param  array $files set of files
   */
  static public function createFolders($files)
  {
    $path = self::getWorkFolderPath();

    @mkdir($path, 0755);
    foreach($files as $file) {
      $destFile = $path . '/' . $file['name'];
      if( ! file_exists(dirname($destFile))){
        FileHelper::createDirectory(
          dirname($destFile)
        );
      }
      touch( $destFile, strtotime($file['mtime']));
    }
  }

  /**
   * Deletes the test files and folder structure previously created in
   * @tests/_work
   */
  static public function deleteFolders()
  {
    $path = self::getWorkFolderPath();
    \yii\helpers\FileHelper::removeDirectory($path);
  }
}
