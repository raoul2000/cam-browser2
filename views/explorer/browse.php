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
                if( $item->type === 'file') {
                  $icon = 'glyphicon-file';
                }
                elseif( $item->type === 'dir') {
                  $icon = 'glyphicon-folder-close';
                }
                if( $item->basename == '..' ) {
                  $paramPath = $parent;
                } else {
                  $paramPath = $item->path != '/'
                    ? $item->path . '/' . $item->basename
                    : $item->path . $item->basename;
                }
                ?>
                  <a href="index.php?r=explorer/browse&path=<?= $paramPath ?>" class="list-group-item">
                    <?= var_dump($item)  ?>
                    <span class="day">
                      <span class="glyphicon <?= $icon ?>" aria-hidden="true"></span>
                       <?= $item->basename ?>
                     </span>
                  </a>

                <?php
              }
            }
          ?>
        </div>
    </div>
</div>
