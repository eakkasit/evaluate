

<div class="criteria_data_list" >
  <ol class="dd-list">
    <?php
    if (isset($datas) && !empty($datas)) {
      foreach ($datas as $key => $value) {
        ?>
        <li class="dd-item" data-id="<?php echo $key+1; ?>">
          <div class="row">
            <label class="col-md-2">หมวด</label>
            <div class="col-md-4">
              <input type="text" name="criteria_name[<?php echo $key; ?>]" title="หมวดเกณฑ์การประเมิน" alt="หมวดเกณฑ์การประเมิน" class="form-control"  value="<?php echo $value->criteria_name; ?>">
            </div>
          </div>
          <?php if (isset($value->data) && !empty($value->data)) {
            ?>
            <ol class="dd-list">
            <?php foreach ($value->data as $key_child => $child) { ?>
              <li class="dd-item">
              <div class="row">
                <label class="col-md-2">เกณฑ์การประเมิน</label>
                <div class="col-md-3">
                  <input type="text" class="form-control" name="criteria_name[<?php echo $key; ?>][<?php echo $key_child; ?>]" title="เกณฑ์การประเมิน" alt="เกณฑ์การประเมิน" value="<?php echo $child->criteria_name; ?>" >
                </div>
                <div class="col-md-3">
                  <select class="form-control" title="โครงการ / กิจจกรรม" name="project_id[<?php echo $key; ?>][<?php echo $key_child; ?>][]" >
                    <?php
                    foreach ($activities as $activity_id => $activity) { ?>
                      <option value="<?php echo $activity_id; ?>"><?php echo $activity ;?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="form-group col-md-2">
                  <div class="input-group ">
                    <input type="text" class="form-control mini-box" title="ผลลัพธ์" alt="ผลลัพธ์" name="result[<?php echo $key; ?>][<?php echo $key_child; ?>][]" value="" >
                    <input type="text" class="form-control mini-box" title="เปอร์เซนต์ผลลัพธ์ของโครงการที่ได้" alt="เปอร์เซนต์ผลลัพธ์ของโครงการที่ได้" name="percent[<?php echo $key; ?>][<?php echo $key_child; ?>][]" value="" >
                    <input type="text" class="form-control mini-box" title="ค่าน้ำหนัก" alt="ค่าน้ำหนัก" name="weight[<?php echo $key; ?>][<?php echo $key_child; ?>][]" value="<?php echo $child->weight ?>" readonly>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="pull-right action-buttons">
                    <a class="orange" href="#" title="เอกสารแนบ"  onclick="addtData('<?php echo $child->id; ?>')">
                      <i class="ace-icon fa fa-paperclip bigger-130"></i>
                    </a>
                    <a class="green" href="#" title="เพิ่มโครงการ" onclick="insert_row('<?php echo $child->id; ?>')">
                      <i class="ace-icon fa fa-plus bigger-130"></i>
                    </a>
                    <!-- <a class="blue" href="#"  onclick="editData('<?php echo $child->id; ?>')">
                      <i class="ace-icon fa fa-pencil bigger-130"></i>
                    </a> -->
                    <!-- <a class="red" href="#" title="ลบ"  onclick="deleteData('<?php echo $child->id; ?>')">
                      <i class="ace-icon fa fa-trash-o bigger-130"></i>
                    </a> -->
                  </div>
                </div>
              </div>

            </li>
            <?php }  ?>
            </ol>
            <div class="row">
              <div class="col-md-6 text-right">
                คะแนนเฉลี่ยรวมรายหมวด
              </div>
              <div class="col-md-4">
                <input type="text" class="form-control" name="" value="">
              </div>
            </div>
            <?php
          } ?>
        </li>
        <?php
      }
    }
    ?>
  </ol>
</div>
