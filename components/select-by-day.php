<?php

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
