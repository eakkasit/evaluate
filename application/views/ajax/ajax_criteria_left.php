

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
              <a class="green" href="#" onclick="addData('<?php echo $value->id; ?>')">
                <i class="ace-icon fa fa-plus bigger-130"></i>
              </a>
              <a class="blue" href="#" onclick="editData('<?php echo $value->id; ?>')">
                <i class="ace-icon fa fa-pencil bigger-130"></i>
              </a>
              <a class="red" href="#" onclick="deleteData('<?php echo $value->id; ?>')">
                <i class="ace-icon fa fa-trash-o bigger-130"></i>
              </a>
            </div>
          </div>
          <?php if (isset($value->data) && !empty($value->data)) {
            ?>
            <ol class="dd-list">
            <?php foreach ($value->data as $key_child => $child) { ?>
              <li class="dd-item" data-id="<?php echo $key_child+1; ?>">
                <div class="dd-handle">
                  <?php echo $child->criteria_name; ?>
                  <div class="pull-right action-buttons">
                    <a class="blue" href="#"  onclick="editData('<?php echo $child->id; ?>')">
                      <i class="ace-icon fa fa-pencil bigger-130"></i>
                    </a>
                    <a class="red" href="#"  onclick="deleteData('<?php echo $child->id; ?>')">
                      <i class="ace-icon fa fa-trash-o bigger-130"></i>
                    </a>
                  </div>
                </div>
              </li>
            <?php }  ?>
            </ol>
            <?php
          } ?>
        </li>
        <?php
      }
    }
    ?>
  </ol>
</div>
