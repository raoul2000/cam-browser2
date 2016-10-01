<?php

return [
    'adminEmail' => 'admin@example.com',
    // name of the folder where images are located. It must be relative
    // to the current folder
    'folder' => __DIR__ . "/../runtime/sample-data",
    'baseUrl' => 'http://localhost/dev/cam-browser2/runtime/sample-data',
    //'baseUrl' => 'http://localhost/devws/lab/cam-browser2/runtime/sample-data',
    // file pattern to search for in the folder
    'filePattern' => "*.jpg",
    // timezone adjustment applied to the file last modification date
    // timezone support in php : http://php.net/manual/fr/timezones.php
    'timezone' => "Europe/Paris"
];
