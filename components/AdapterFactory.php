<?php

namespace app\components;

use Yii;
use \yii\base\Object;
use \yii\base\InvalidConfigException;
use \League\Flysystem\Adapter\Local;

/**
 *
 */
class AdapterFactory
{

  public function create($type, $options)
  {
    $adaptater = null;
    switch ($type) {

      case 'local':

        if(!isset($options['rootPath'])){
          throw new InvalidConfigException('missing required option for local adaptater : rootPath');
        }
        $defaults = [
          'writeFlags'   => LOCK_EX,
          'linkHandling' => \League\Flysystem\Adapter\Local::DISALLOW_LINKS,
          'permissions'  =>  [
              'file' => [
                  'public'  => 0644,
                  'private' => 0600,
              ],
              'dir' => [
                  'public'  => 0755,
                  'private' => 0700,
              ]
          ]
        ];
        $arg = array_merge($defaults, $options);
        list($rootPath, $writeFlags, $linkHandling, $permissions) = $arg;
        $adaptater = new \League\Flysystem\Adapter\Local($rootPath, $writeFlags, $linkHandling, $permissions);
        break;

      case 'ftp':

        # code...
        break;

      default:
        throw new \yii\base\InvalidParamException("invalid adaptater type : $type");
        break;
    }
    return $adaptater;
  }
}
