

<div class="dd" id="nestable">
  <ol class="dd-list">
    <?php
    if (isset($datas) && !empty($datas)) {
      foreach ($datas as $key => $value) {
        ?>
        <li class="dd-item" data-id="<?php echo $key+1; ?>">
          <div class="dd-handle">
            <?php echo $value->criteria_name; ?>
            <div class="pull-right action-buttons">
              <a class="blue" href="#">
                <i class="ace-icon fa fa-pencil bigger-130"></i>
              </a>
              <a class="red" href="#">
                <i class="ace-icon fa fa-trash-o bigger-130"></i>
              </a>
            </div>
          </div>
          <?php if (isset($value['data'] && empty($value['data']))) {
            // code...
          } ?>
        </li>
        <?php
      }
    }
    ?>
  </ol>
</div>
