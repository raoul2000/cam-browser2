<?php

use yii\web\View;

/* @var $this yii\web\View */
$this->registerJsFile(Yii::getAlias('@web/js/view-image.js'),[
  'position' => View::POS_END,
  'depends' => [\yii\web\JqueryAsset::className()]
]);
?>

<!-- FULLSCREEN IMAGE VIEW -->
<div id="fullscreen" class="row" style="display:none;">
  <div class="col-md-12">
    <button id="btn-back" type="button" class="btn btn-primary btn-block">Close</button>
    <hr/>
    <img id="img-fullscreen" src="" class="img-responsive">
  </div>
</div>


<!-- TOOLBAR -->
<div id="toolbar" class="row">
  <div class="col-lg-12">
    <div class="clearfix">
      <div class="btn-toolbar pull-right" role="toolbar" aria-label="...">
        <div class="btn-group " role="group" aria-label="...">
          <button  id="btn-back-to-index" type="button" class="btn btn-default">
            <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Back To Hall
          </button>
          <button id="btn-delete-all" type="button" class="btn btn-danger" data-date="<?= $date ?>">
            <span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Delete All
          </button>
        </div>
      </div>
    </div>
    <hr/>
  </div>
  <div id="wrap-progress-delete-bar" class="col-lg-12" style="display:none">
    <div class="progress">
      <div id="progress-delete-bar" class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
        <span id="progress-delete-msg"></span>
      </div>
    </div>
  </div>
</div>


<!-- IMAGE GRID -->
<div id="grid" class="row">
  <?php
    if( count($files) === 0) {
      echo "no image to display";
    } else {
      foreach ($files as $filename => $filemtime)
      {
        $fileUrl = Yii::$app->params['baseUrl'] .'/' . basename($filename);
        $fileRelativePath = $config['folder'] .'/' . basename($filename);
        $encodedFilePath = urlencode(basename($filename));

        $dateTime = new DateTime('@'.$filemtime);
        if($config['timezone'] != null){
          $dateTime->setTimezone(new DateTimeZone($config['timezone']));
        }
        $dateFmt = $dateTime->format("D j Y - H:i:s");
        ?>

          <div class="col-md-4">
            <div class="thumbnail">
              <img src="<?= $fileUrl ?>" class="img-responsive clickable">
              <button
                type="button"
                class="btn btn-default btn-lg btn-delete"
                data-path="<?= $encodedFilePath ?>">
                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
              </button>
              <h4><?= $dateFmt ?></h4>
            </div>
          </div>

        <?php
      }
    }
   ?>
</div>
