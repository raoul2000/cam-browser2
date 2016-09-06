<?php
/* @var $this yii\web\View */
use \yii\helpers\Html;
use yii\web\View;

/* @var $this yii\web\View */
$this->registerJsFile(Yii::getAlias('@web/js/vfs.js'),[
  'position' => View::POS_END,
  'depends' => [\yii\web\JqueryAsset::className()]
]);
?>

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
  <div class="col-md-3">
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
          if( $item['type'] === 'file') {
            $icon = '<span class="glyphicon glyphicon-file" aria-hidden="true"></span>';
            $nameCol = $item['basename'];
            $nameCol =  \yii\helpers\Html::a(
              $item['basename'],
              ['#' ],
              [ 'class' => 'view-file-content' ,'data' => [ 'path' => $item['vfspath'] ] ]
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

  <div class="col-md-9">
    <div id="file-content" class="">

    </div>
      Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
  </div><!-- end of main content -->
</div>
