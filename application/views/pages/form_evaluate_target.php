<?php
$title_prefix = 'เพิ่ม';
$action = base_url("evaluate_targets/save");
$prev = base_url("evaluate_targets/dashboard_evaluate_targets");
if (isset($result->id) && $result->id != '') {
	$title_prefix = 'แก้ไข';
	$action .= "/{$result->id}";
	// $prev = base_url("evaluate_datas/dashboard_evaluate_data_detail/{$result->id}");
}
?>
<p class="h4 header text-success">
	<i class="fa fa-file-text-o"></i> <?php echo $title_prefix; ?>เป้าหมายโครงการ
</p>

<div id="search-filter" class="widget-box">
	<div class="widget-body">
		<div class="widget-main">
			<form method="post" enctype="multipart/form-data" action="<?php echo $action; ?>">
				<div class="row">

					<div class="col-md-8">

						<div class="row">
							<div class="col-md-4 text-right">
								<label for="stext">ชื่อโครงการ</label>
							</div>
							<div class="col-md-8">
								<label for="stext"><?php echo $data->project_name; ?></label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4 text-right">
								<label for="stext">ปี</label>
							</div>
						<?php
							if(isset($result->id)){
								?>
								<div class="col-md-8 text-left">
									<label for="stext"><?php echo $result->year+543 ; ?></label>
									<input type="hidden" name="year" class="form-control" value="<?php echo $result->year ; ?>" >
								</div>
								<?php
							}else{
						?>
						<div class="col-md-4 text-left">
							<select name="year" class="form-control" >
								<?php
								 if(isset($year_list)){
									 foreach ($year_list as $key => $value) {
										 $selected = "";
										 if(isset($result->year)){
											 if($result->year == $key){
												 $selected = "selected";
											 }
										 }
											?>
											<option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $value; ?></option>
											<?php
									 }
								 }
								?>
							</select>
						</div>
					<?php } ?>

						</div>
						<div class="row">
							<div class="col-md-4"></div>
							<label class="col-md-8 text-danger"><?php echo form_error("year"); ?></label>
						</div>
						<div class="row">
							<div class="col-md-4 text-right">
								<label for="stext">รายละเอียด</label>
							</div>
							<div class="col-md-8">
								<label for="stext"><?php echo $data->detail; ?></label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4 text-right">
								<label for="stext">เป้าหมายโครงการ <span class="red">*</span> :</label>
							</div>
							<div class="col-md-8 text-left">
								<input type="hidden" name="id" class="form-control" value="<?php  if(isset($result->id)){echo $result->id;}; ?>" >
								<input type="hidden" name="project_id" class="form-control" value="<?php echo $project_id; ?>" >
								<input type="number" name="target" class="form-control" value="<?php if (isset($result->target)) { echo $result->target;	} ?>" placeholder="ระบุ">
							</div>

						</div>
						<div class="row">
							<div class="col-md-4"></div>
							<label class="col-md-8 text-danger"><?php echo form_error("target"); ?></label>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 text-center">
						<a href="<?php echo $prev; ?>" class="btn btn-sm btn-danger">
							<i class="fa fa-times"></i>
							ยกเลิก
						</a>
						&nbsp;&nbsp;
						<button class="btn btn-sm btn-success" type="submit" id="submit">
							<i class="fa fa-floppy-o"></i>
							บันทึก
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">

    jQuery(document).ready(function () {

    });
</script>
