<?php

namespace app\components;

use Yii;
use \yii\base\Object;

/**
 *
 */
class Fs extends Object
{
    private $_basePath;
    private $_baseUrl;

    public function __construct($config = [])
    {
      if( isset($config['basePath'])) {
        $this->_basePath = trim($config['basePath']);
        unset($config['basePath']);
      }
      if( isset($config['baseUrl'])) {
        $this->_baseUrl = trim($config['baseUrl']);
        unset($config['baseUrl']);
      }
      parent::__construct($config);
    }

    public function init()
    {
      parent::init();        // ... initialization after configuration is applied
      if( ! file_exists($this->_basePath)) {
        throw new \yii\base\InvalidConfigException("folder not found : " . $this->_basePath);
      } elseif ( ! is_dir( $this->_basePath) ) {
        throw new \yii\base\InvalidConfigException("specified base path is not a valid folder : " . $this->_basePath);
      }
    }

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
      $file = trim($file);
      if( $file === '/' || empty($file)){
        return '/';
      }

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

    /**
     * Returns the local path used as root folder for this Fs instance.
     * @return string absolute local path
     */
    public function getBasePath()
    {
      return $this->_basePath;
    }

    public function getBaseUrl($path='/')
    {
      return $this->_baseUrl;
    }

    /**
     * Returns a list of files and folder inside a path.
     *
     * @param  string $folder the folder to list
     * @return array         list of object representing files and folders
     */
    public function ls($folder = "/", $filterExtension = null)
    {
      $result = [];
      $dirname = $this->getBasePath() . $folder;  //DIRECTORY_SEPARATOR
      $directory = new \DirectoryIterator($dirname);
      foreach ($directory as $fileinfo) {
        // never returns the '.'
        if( $fileinfo->getBasename() == '.') {
          continue;
        }
        // never include '..' when dealing with the root folder
        if( $fileinfo->getBasename() == '..' && $folder == '/') {
          continue;
        }
        // apply extension based filter
        if ( $fileinfo->getType() == 'file'
          && is_array($filterExtension)
          && ! in_array($fileinfo->getExtension(), $filterExtension) )
        {
          continue;
        }
        $entry = new \stdClass;
        $entry->extension = $fileinfo->getExtension();
        $entry->type = $fileinfo->getType();  // file, link, dir
        $entry->basename = $fileinfo->getBasename();
        $entry->path = $fileinfo->getPath();
        $entry->mtime = $fileinfo->getMTime();
        $entry->size = $fileinfo->getSize();

        $result[] = $entry;
      }
      return $result;
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
