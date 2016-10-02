<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
      'node_modules/codemirror/lib/codemirror.css',
      'node_modules/codemirror/addon/display/fullscreen.css',
        'css/site.css',
        'css/app.css'
    ];
    public $js = [
      'node_modules/codemirror/lib/codemirror.js',
      'node_modules/codemirror/mode/javascript/javascript.js',
      'node_modules/codemirror/addon/display/fullscreen.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
