<?php
/* @var $this yii\web\View */
?>

<div class="row">
    <div class="col-md-12">
        <div class="list-group">
          <?php
            if( count($list) == 0 ) {
              // this should never occur as an empty folder should not be
              // listed in the image index page
              echo "<p>no file found</p>";
            } else {
              foreach ($list as  $item) {
                if( $item->basename == '..') {
                  $paramPath = $parent;
                } elseif( $path == '/') {
                  $paramPath = '/' . $item->basename;
                } else {
                  $paramPath = $path . '/' . $item->basename;
                }
                ?>
                  <a href="index.php?r=explorer/browse&path=<?= $paramPath ?>" class="list-group-item">
                    <img src="<?= \app\components\MimeType::getIconUrl($item->basename) ?>" alt="" />
                    <span><?= \app\components\MimeType::getIconUrl($item->basename) ?></span>
                    <span class="day"> <?= $item->basename ?></span>
                  </a>

                <?php
              }
            }
          ?>
        </div>
    </div>
</div>
