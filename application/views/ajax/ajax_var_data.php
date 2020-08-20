<?php
  if(isset($fomular) && !empty($fomular)){
    // echo "<pre>";
    // print_r($fomular);
  	foreach ($fomular as $key_fomular => $value_fomular) {
      ?>
  		<div class="row">
        <div class="col-md-1">
          <?php
            if($kpi_standard_type == 1){
              ?>
              <input type="checkbox">
              <?php
            }
          ?>
        </div>
  			<label class="col-md-5"><?php echo $value_fomular->var_name; ?></label>
  			<div class="col-md-3">
          <?php
          if($kpi_standard_type == 2){
            if($value_fomular->var_import_id == 1){
              ?>
              <input type="text" name="" class="form-control">
              <?php
            }elseif ($value_fomular->var_import_id == 2) {
              ?>
              <select class="form-control" title="โครงการ / กิจจกรรม" name="criteria_data['.$key_fomular.'][project_id]" >
                <option>----- เลือกโครงการ / กิจกรรม -----</option>
              </select>
              <?php
            }else{
              ?>
              <input type="text" name="" readonly>
              <?php
            }
          }
          ?>

  			</div>
        <label class="col-md-3 text-right"><?php echo $units_list[$value_fomular->var_unit_id] ?></label>
        <label class="col-md-12"></label>
  		</div>
      <?php
  	}
}
?>
