<?php
/* @var $this yii\web\View */
use \yii\helpers\Html;
use \yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
$this->registerJsFile(Yii::getAlias('@web/js/vfs.js'),[
  'position' => View::POS_END,
  'depends'  => [\yii\web\JqueryAsset::className()]
]);
$updateFileUrl = Url::to(['explorer/update']);
?>

<div class="row hidden"  >
  <div class="col-md-12">
    <?php
     $url = Url::to(['explorer/update']);
      echo Html::a(
        'test ajax call',
        ['#'],
        ['onclick'=> "
      console.log('bing = $url');
      $.post('$url',{
        'filepath' : '/dummy.xml',
        'content' : 'this is the content ddd'
      }, function(data){
        console.log(data);
      });
      return false;
        "]
      );
    ?>
  </div>
</div>

<?php
echo Html::tag('div','',[
  'id' => 'pageVar',
  'data' => [
    "update-file-url" => $updateFileUrl
  ]
])
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
  <div class="col-md-2">
    <div data-spy="___affix" data-offset-top="120">


    <table class="table table-hover table-condensed">
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
          $data = [
            'path'      => $item['vfspath']
          ];
          if( isset($item['mimetype'])) {
            $data['mimetype'] = $item['mimetype'];
          }
          if( $item['type'] === 'file') {
            $icon = '<span class="glyphicon glyphicon-file" aria-hidden="true"></span>';
            if( isset($item['extension'])) {
              // add extension to the data object
              $data['extension'] = $item['extension'];

              // base on thee file extension, define the way it is displayed.
              // First let's check if it is an image or a video
              if( in_array( $item['extension'] ,Yii::$app->params['imageExtension'])) {
                $icon = '<span class="glyphicon glyphicon-picture" aria-hidden="true"></span>';
              }
              elseif( in_array( $item['extension'] ,Yii::$app->params['videoExtension'])) {
                $icon = '<span class="glyphicon glyphicon-film" aria-hidden="true"></span>';
              }
              elseif( isset(Yii::$app->params['editor'])) {

                // check if it is an editable text content.
                // An editable content is configured based on the file extension
                foreach (Yii::$app->params['editor'] as $ext => $cmOption) {
                  // $ext can be a comma separated list of extensions
                  $extList = array_map(function($it){
                    return trim($it);
                  }, explode(',',$ext));

                  if( in_array($item['extension'],$extList)) {
                    $data['cm-options'] = $cmOption;
                    break;
                  }
                }
              }
            }
            $nameCol =  \yii\helpers\Html::a(
              $item['basename'],
              ['#' ],
              [
                'class' => 'view-file-content' ,
                'data' => $data
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
    </div>
  </div> <!-- end of nav panel -->

  <div class="col-md-10">
    <div class="row hidden" style="margin-bottom:10px;">
      <div class="col-md-10">
        <div class="btn-toolbar" role="toolbar" aria-label="...">
          <div class="btn-group btn-group-xs" role="group" aria-label="...">
            <button type="button" class="btn btn-default">
              <span class="glyphicon glyphicon-save" aria-hidden="true"></span> Save
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-10">
        <div id="file-content-container" class="">
          <div id="file-content" class="">

          </div>
        </div>
      </div>
    </div>
  </div><!-- end of main content -->
</div>
