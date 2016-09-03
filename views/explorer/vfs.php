<?php
/* @var $this yii\web\View */
?>

<div class="row">
    <div class="col-md-12">
      <h3><?= $path ?></h3>
      <hr/>
      <?php

        foreach ($content as $item) {
          if( $item['type'] === 'file') {
            echo '(f) - ' . $item['basename']. '<br/>';
          }elseif ($item['type'] === 'dir') {
            echo \yii\helpers\Html::a(
              'd - ' . $item['basename'],
              ['explorer/vfs','path' => $item['vfspath'] ]
            ) . '<br/>';
          } elseif ($item['type'] === 'mount') {
            echo \yii\helpers\Html::a(
            'm - ' . $item['basename'],
            ['explorer/vfs','path' => $item['vfspath']  ]
            ) . '<br/>';
          }
        }
        var_dump($content)
      ?>
    </div>
</div>
