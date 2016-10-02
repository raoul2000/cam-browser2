<?php
/* @var $this yii\web\View */
use \yii\helpers\Html;
use \yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
$this->registerJsFile(Yii::getAlias('@web/js/vfs.js'),[
  'position' => View::POS_END,
  'depends' => [\yii\web\JqueryAsset::className()]
]);
?>
<div class="row hidden"  >
  <div class="col-md-12">
    <?php
     $url = Url::to(['explorer/vfs','path' => $path ]);
      echo Html::a(
        'test ajax call',
        ['#'],
        ['onclick'=> "
      console.log('bing = $url');
      $.get('$url',function(data){
        console.log(data);
      });
      return false;
        "]
      );
    ?>
  </div>
</div>


<div class="row">
    <div class="col-md-12">
      <div>
        <ol class="breadcrumb">
          <?php

            $folders = explode('/',$path);
            $crumbs = [];
            $active = false; // is the current folder inserted ?
            while( ($folder = array_pop($folders)))
            {
              if( $active == false ) {
                $breadcrumb = '<li class="active">' . $folder . '</li>';
                $active = true;
              } else {
                $folderPath = implode('/', $folders);
                $breadcrumb = '<li>'
                . Html::a(
                  $folder,
                  ['explorer/vfs','path' => $folderPath . '/' . $folder]
                ). '</li>';
              }
              $crumbs[] = $breadcrumb;
            }
            // add the first crumb to the root folder
            $crumbs[] = '<li>'
              . '<span class="glyphicon glyphicon-home" aria-hidden="true"></span> '
              . Html::a(
                'home',
                ['explorer/vfs','path' => '/']
              ). '</li>';

            // render breadcrumbs
            $breadcrumb = array_reverse($crumbs);
            foreach ($breadcrumb as $item) {
              echo $item;
            }
          ?>
        </ol>
      </div>
    </div>
</div>

<div class="row">
  <div class="col-md-2">
    <table class="table table-hover">
      <?php
        if( $parent != $path) {
          $nameCol =  \yii\helpers\Html::a(
            'go to parent folder',
            ['explorer/vfs','path' => $parent ]
          );
          ?>
          <td style="border:0px;">
            <span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span>
            <?= $nameCol ?>
          </td>
          <?php
        }
        foreach ($content as $item) {
          //var_dump($item);
          //continue;
          if( $item['type'] === 'file') {
            $ext = $icon = '';
            $icon = '<span class="glyphicon glyphicon-file" aria-hidden="true"></span>';
            if( array_key_exists('extension',$item)) {
              $ext = strtolower($item['extension']);
              if( in_array( $ext,['jpg','jpeg','png','gif'])) {
                $icon = '<span class="glyphicon glyphicon-picture" aria-hidden="true"></span>';
              }
              elseif( in_array( $ext ,['mp4','mov','wmv'])) {
                $icon = '<span class="glyphicon glyphicon-film" aria-hidden="true"></span>';
              }
            } else {
              $item['extension'] = '';
            }
            $nameCol = $item['basename'];
            $nameCol =  \yii\helpers\Html::a(
              $item['basename'],
              ['#' ],
              [ 'class' => 'view-file-content' ,
                'data' => [
                  'path'      => $item['vfspath'],
                  'mimetype'  => $item['mimetype'],
                  'extension' => $item['extension']
                ]
              ]
            );

          } else {
            $icon = $item['type'] === 'mount'
              ? '<span class="text-danger glyphicon glyphicon-folder-close" aria-hidden="true"></span>'
              : '<span class="text-warning glyphicon glyphicon-folder-close" aria-hidden="true"></span>';

            $nameCol = \yii\helpers\Html::a(
              $item['basename'],
              ['explorer/vfs','path' => $item['vfspath'] ]
            ) . '<br/>';
          } ?>
          <tr>
            <td style="border:0px;"><?= $icon ?> <?= $nameCol ?></td>
          </tr>
          <?php
        }
        //var_dump($content)
      ?>
    </table>
  </div> <!-- end of nav panel -->

  <div class="col-md-10">
    <div id="file-content-container" class="">
      <div id="file-content" class="">

      </div>
    </div>
  </div><!-- end of main content -->
</div>
