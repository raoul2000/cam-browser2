<?php
/* @var $this yii\web\View */
?>

<div class="row">
    <div class="col-md-12">
      <div>
        <ol class="breadcrumb">
          <?php
            $folders = explode('/',$path);
            $toEnd = count($folders);
            foreach ($folders as $folder) {
              if( 0 === --$toEnd ) {
                $breadcrumb = '<li class="active">' . $folder . '</li>';
              } else {
                $breadcrumb = '<li><a href="#">' . $folder .'</a></li>';
              }
              echo $breadcrumb;
            }
          ?>
        </ol>
      </div>

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
</div>
