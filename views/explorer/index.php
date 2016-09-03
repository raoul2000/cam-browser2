<?php
/* @var $this yii\web\View */
?>

<div class="row">
    <div class="col-md-12">
        <a href="index.php?r=explorer/vfs&path=/">VFS Explorer</a>
        <div class="list-group">
          <?php
            if( count($days) == 0 ) {
              // this should never occur as an empty folder should not be
              // listed in the image index page
              echo "<p>no image found</p>";
            } else {
              foreach ($days as $date => $countFiles) {
                $year  = substr($date,0,4);
                $month = substr($date,4,2);
                $day   = substr($date,6,2); //day number 01-31

                $dayHTML = "$year-$month-$day";
                $dateTime = new DateTime("$year/$month/$day");
                ?>

                  <a href="index.php?r=explorer/view-image&date=<?= $date ?>" class="list-group-item">
                    <!--input class="chk-date" type="checkbox" name="name" value=""-->
                    <span class="day"> <?= $dayHTML ?></span>
                    <span class="badge alert-info  day"><?= $countFiles ?></span>
                    <!--span class="badge alert-warning day">0</span-->
                  </a>

                <?php
              }
            }
          ?>
        </div>
    </div>
</div>
