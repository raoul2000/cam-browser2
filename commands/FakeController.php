<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use Yii;

use yii\console\Controller;
use yii\helpers\FileHelper;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class FakeController extends Controller
{
    private $sampleFiles = [
      [
        'name' => "folder/file1.jpg",
        'mtime' => "2016/01/28 17:23"
      ],
      [
        'name' => "folder/file1a.jpg",
        'mtime' => "2016/01/28 17:23"
      ],
      [
        'name' => "folder/sub-folder/file3.jpg",
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
      ],
      [
        'name' => "file4.jpg",
        'mtime' => "2016/08/01 22:54"
      ],
      [
        'name' => "file4a.jpg",
        'mtime' => "2016/08/01 22:55"
      ],
    ];
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";
    }

    public function actionImageFolder ()
    {
        echo "creating fake folder\n";

        $path = Yii::getAlias('@runtime/sample-data');
        $refFilename = __DIR__ . "/img-example.jpg";
        $timezone = "Europe/Paris";

        echo "path       : $path\n";
        echo "timezone   : $timezone\n";

        @mkdir($path, 0755);
        foreach($this->sampleFiles as $file) {
          $destFile = $path . '/' . $file['name'];
          if( ! file_exists(dirname($destFile))){
            FileHelper::createDirectory(
              dirname($destFile)
            );
          }
          touch( $destFile, strtotime($file['mtime']));
          copy($refFilename, $destFile);
          echo ":: $destFile\n";
        }
    }


}
