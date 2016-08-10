<?php
/* @var $this yii\web\View */
?>

<div id="grid" class="row">
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

      $imageUrl = $baseUrl . $paramPath;
      ?>

        <div class="col-md-4">
          <div class="thumbnail">
            <img src="<?= $imageUrl ?>" class="img-responsive clickable">
            <button
              type="button"
              class="btn btn-default btn-lg btn-delete"
              data-path="<?= $paramPath ?>">
              <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
            </button>
            <h4><?= $item->basename ?></h4>
          </div>
        </div>

      <?php
    }
  }
  ?>
</div>
