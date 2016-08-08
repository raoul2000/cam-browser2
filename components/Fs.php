<?php

namespace app\components;

use Yii;
use \yii\base\Object;

/**
 *
 */
class Fs extends Object
{
    public $prop1;
    public $prop2;
    private $_basePath;

    public function __construct($basePath, $param2, $config = [])
    {
          // ... initialization before configuration is applied
      $this->_basePath = $basePath;
      parent::__construct($config);
    }

    public function init()
    {
      parent::init();        // ... initialization after configuration is applied
      if( ! file_exists($this->_basePath)) {
        throw new yii\base\InvalidConfigException("folder not found : " . $this->_basePath);
      } elseif ( ! is_dir( $this->_basePath) ) {
        throw new yii\base\InvalidConfigException("specified base path is not a valid folder : " . $this->_basePath);
      }
    }

    public function getBasePath()
    {
      return $this->_basePath;
    }

    public function ls($folder = "")
    {
      $dirname = $this->_basePath . 
      $directory = new DirectoryIterator(__DIR__);
      foreach ($directory as $fileinfo) {
          if ($fileinfo->isFile()) {
              echo $fileinfo->getExtension() . "\n";
          }
      }
    }
    /**
     * List all files matching the pattern and returns an index by last modification time.
     * Array index are the day of file last modification date, and the value is the count of
     * files for the corresponding days.
     * For example :
     * Array
     *(
     *  [20160729] => 2
     *  [20100429] => 1
     *  [20050921] => 1
     * )
     * Note that days are
     * @param  string $pattern glob pattern (e.g "/folder/*.txt")
     * @param  string $timezone the timezone to use to process the file date. If not
     * set, the default timezone will be used. More on timezone https://codepen.io/nxworld/pen/ZYNOBZ
     * @return array          day index array
     */
    function getIndexByDay($pattern, $timezone = null) {
      $index = [];
      $files = glob($pattern, GLOB_NOSORT);
      if( $files !== FALSE ) {
        foreach($files as $file) {
          $date=new DateTime('@'.filemtime($file));
          if($timezone != null){
            $date->setTimezone(new DateTimeZone($timezone));
          }
          $day = $date->format('Ymd');
          $index[$day] = isset($index[$day]) ? $index[$day] + 1 : 1 ;
        }
        krsort($index);
      }
      return $index;
    }


    /**
     * Find all files matching a pattern, and with a particular last modification day.
     * The returned array keys are filenames, and values are last modification timestamp.
     * Example :
     * (
     *    [D:\dev\cam-browser/data-sample/img-1.jpg] => 1469873997
     *    [D:\dev\cam-browser/data-sample/img-2.jpg] => 1469873997
     * )
     * @param  string $day      the day value in format yyyymmdd
     * @param  string $pattern  the file search pattern
     * @param  string $timezone timezone (more on timezone https://codepen.io/nxworld/pen/ZYNOBZ)
     * @return array           list of files
     */
    function getFilesByDay($day, $pattern, $timezone = null)
    {
      $result = [];
      $files = glob($pattern, GLOB_NOSORT);
      if( $files !== FALSE ) {
        foreach($files as $file) {
          $date=new DateTime('@'.filemtime($file));
          if($timezone != null){
            $date->setTimezone(new DateTimeZone($timezone));
          }
          if ( $day === $date->format('Ymd')){
            $result[$file] = $date->getTimestamp();
          }
        }
      }
      return $result;
    }

  }
