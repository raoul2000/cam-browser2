<?php

namespace app\components;

use Yii;
use \yii\base\Object;

/**
 *
 */
class VFSHelper
{
    /**
     * Returns a parent directory path.
     * The path separator is '/'.
     * Example :
     * dirname('/folder/file.txt') returns '/folder'
     * dirname('/file.txt') returns '/'
     *
     * @param  string $file a path
     * @return string       the parent path of $path
     */
    static public function dirname($file)
    {
      $file = Fs::normalizePath($file);
      if( $file === '/' ){
        return '/'; // root folder has no parent
      }
      // split path on '/' and ignore empty tokens
      $tokens = array_filter(explode('/',$file),function($token){
        return ! empty($token);
      });
      if(count($tokens) == 1) {
        return '/';
      } else {
        array_pop($tokens);
        return '/' . implode('/',$tokens);
      }
    }

    public static function normalizePath($path)
    {
      $path = trim($path);
      if( empty($path) || $path == '/') {
        return '/';
      } else {
        $norm = \yii\helpers\FileHelper::normalizePath($path,'/');
        if( strpos($norm,'/') !== 0 ) {
          $norm = '/' . $norm;
        }
        return $norm;
      }
    }
  }
