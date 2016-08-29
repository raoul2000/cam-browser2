<?php

namespace app\components;

use Yii;
use \yii\base\Object;
use \yii\base\InvalidConfigException;
use \League\Flysystem\Adapter\Local;

/**
 * Create flysystem adapters
 */
class AdapterFactory
{

  /**
   * Creates and return a fly system Adapter based on the type and options passed as argument
   *
   * @param  string $type    the type of flysystem adapter (e.g. 'local', 'ftp', etc.)
   * @param  array $options  adapter initialization settings
   * @return \League\Flysystem\Adapter | null      the adpater instance or NULL if the adapter could not be created
   * @throws Exception
   *
   */
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
        extract($arg);
        $adaptater = new \League\Flysystem\Adapter\Local(Yii::getAlias($rootPath), $writeFlags, $linkHandling, $permissions);
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
