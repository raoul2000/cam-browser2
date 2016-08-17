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
    'timezone' => "Europe/Paris",
    'fs' => [
      'basePath' => '@runtime/sample-data',
      'baseUrl'  => 'http://localhost/devws/lab/cam-browser2/runtime/sample-data',
      'mount' => [
          'mount1' => [
            'mount-point' => '/',
            'fstype' => 'local',
            'baseUrl'  => 'http://localhost/devws/lab/cam-browser2/runtime/sample-data',  // optionnal
            'options' => [  // see http://flysystem.thephpleague.com/adapter/local/
              'rootPath' => '/a/b/c',
              'links' => "skip", // 'skip', 'disallow',
              'permissions' => [
                'file' => [
                  'public' => 0744,
                  'private' => 0700
                ],
                'dir' => [
                  'public' => 0755,
                  'private' => 0700
                ]
              ]
            ]
          ],
          'mount2' => [
            'mount-point' => '/folder',
            'fstype' => 'ftp',
            'baseUrl'  => 'http://hostname/folder1',  // optionnal
            'options' => [
              'host' => 'ftp.example.com',
              'username' => 'username',
              'password' => 'password',
          
              /** optional config settings */
              'port' => 21,
              'root' => '/path/to/root',
              'passive' => true,
              'ssl' => true,
              'timeout' => 30,
            ]

          ]
      ]
    ]
];
