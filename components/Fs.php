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

    public function __construct($param1, $param2, $config = [])
    {
          // ... initialization before configuration is applied
      parent::__construct($config);
    }

    public function init()
    {
      parent::init();        // ... initialization after configuration is applied
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

  }
