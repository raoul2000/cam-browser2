<?php

namespace tests\codeception\unit;

use Yii;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local;
use yii\helpers\FileHelper;
use Codeception\Specify;
use tests\codeception\unit\TestHelper;

class ExplorerTest extends \Codeception\TestCase\Test
{
  use Specify;

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

    /**
     * @var \UnitTester
     */
    protected $tester;

    private $fs;

    protected function _before()
    {
      TestHelper::createFolders($this->sampleFiles);
      $this->fs = new Filesystem(new Local(TestHelper::getWorkFolderPath()));
    }

    protected function _after()
    {
      TestHelper::deleteFolders();
    }

    // tests
    public function testMe()
    {
      $this->specify('folder content should containt 1 item', function () {

        $list = $this->fs->listContents('/folder');
        codecept_debug($list);
        expect('list to contain one item', count($list))->equals(1);
        expect('item to be a file', $list[0]['type'])->equals('file');
      });

      $this->specify('folder content should containt 4 items', function () {

        $list = $this->fs->listContents();
        //codecept_debug($list);
        expect('list to contain one item', count($list))->equals(4);
      });


    }

}
