<?php

return [
  'root' => [
    'type' => 'local',
    'options'  => [
      'rootPath' => '@webroot'
    ]
  ],
  'mount' => [
    [
      'name' => 'Mounted Folder 1',
      'type' => 'local',
      'mount-point' => '/assets',
      'options' => [
        'rootPath' => '@webroot/assets'
      ]
    ],
    [
      'name' => 'WEB',
      'type' => 'local',
      'mount-point' => '/sample-data',
      'options' => [
        'rootPath' => '@webroot'
      ]
    ],
    [
      'name' => 'FTP',
      'type' => 'ftp',
      'mount-point' => '/sample-data',
      'options' => [
        'host' => '127.0.0.1',
        'username' => 'username',
        'password' => 'password',

        /** optional config settings */
        'port' => 7002,
        'passive' => true,
        'timeout' => 30,
      ]
    ]
  ]
];
