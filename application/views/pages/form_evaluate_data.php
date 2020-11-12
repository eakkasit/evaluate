<?php
$title_prefix = 'เพิ่ม';
$action = base_url("evaluate_datas/save");
$prev = base_url("evaluate_datas/dashboard_evaluate_data_detail/".$project_id);
if (isset($result->id) && $result->id != '') {
	$title_prefix = 'แก้ไข';
	$action .= "/{$result->id}";
	// $prev = base_url("evaluate_datas/dashboard_evaluate_data_detail/{$result->id}");
}
?>
<p class="h4 header text-success">
	<i class="fa fa-file-text-o"></i> <?php echo $title_prefix; ?>บันทึกรายงานประเมินผล
</p>

<div id="search-filter" class="widget-box">
	<div class="widget-body">
		<div class="widget-main">
			<form method="post" enctype="multipart/form-data" action="<?php echo $action; ?>">
				<div class="row">

					<div class="col-md-8">
						<div class="row">
							<div class="col-md-4 text-right">
								<label for="stext">ชื่อโครงการ :</label>
							</div>
							<div class="col-md-8 text-left">
								<label for="stext"><?php echo $data->project_name; ?></label>
							</div>
						</div>

						<div class="row">
							<div class="col-md-4 text-right">
								<label for="stext">ปีงบประมาณ :</label>
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
							<label class="col-md-12 text-danger"><?php echo form_error("year"); ?></label>
						</div>

						<div class="row">
							<div class="col-md-4 text-right">
								<label for="stext">ผู้รับผิดชอบ :</label>
							</div>
							<div class="col-md-8 text-left">
								<label for="stext"><?php echo  $responsible_person != ''?$responsible_person:'-'; ?></label>
							</div>
						</div>

						<div class="row">
							<div class="col-md-4 text-right">
								<label for="stext">วัตถุประสงค์ :</label>
							</div>
							<div class="col-md-8 text-left">
								<label for="stext"><?php  echo $target != ''?strip_tags($target):'-'; ?></label>
							</div>
						</div>

						<div class="row">
							<div class="col-md-4 text-right">
								<label for="stext">ผลการดำเนินโครงการ :</label>
							</div>
							<div class="col-md-8 text-left">
								<textarea name="project_result" class="form-control"  rows="4" cols="5"><?php if(isset($result->project_result)){echo $result->project_result;}  ?></textarea>
							</div>
							<label class="col-md-12 text-danger"><?php echo form_error("project_result"); ?></label>
						</div>

						<div class="row">
							<div class="col-md-4 text-right">
								<label for="stext">ผลผลิต :</label>
							</div>
							<div class="col-md-8 text-left">
								<textarea name="product" class="form-control" rows="4" cols="5"><?php if(isset($result->product)){echo $result->product;}  ?></textarea>
							</div>
							<label class="col-md-12 text-danger"><?php echo form_error("product"); ?></label>
						</div>

						<div class="row">
							<div class="col-md-4 text-right">
								<label for="stext">ผลลัพธ์ :</label>
							</div>
							<div class="col-md-8 text-left">
								<textarea name="result" class="form-control"  rows="4" cols="5"><?php if(isset($result->result)){echo $result->result;}  ?></textarea>
							</div>
							<label class="col-md-12 text-danger"><?php echo form_error("result"); ?></label>
						</div>

						<div class="row">
							<div class="col-md-4 text-right">
								<label for="stext">ผลการประเมิน <span class="red">*</span> :</label>
							</div>
							<div class="col-md-8 text-left">
								<input type="hidden" name="id" class="form-control" value="<?php  if(isset($result->id)){echo $result->id;}; ?>" >
								<input type="hidden" name="project_id" class="form-control" value="<?php echo $project_id; ?>" >
								<!-- <input type="hidden" name="task_id" class="form-control" value="<?php //echo $id; ?>" > -->
								<!-- <input type="hidden" name="year" class="form-control" value="<?php //echo $data->task_year; ?>" > -->
								<input type="number" name="assessment_results" class="form-control" value="<?php if (isset($result->assessment_results)) { echo $result->assessment_results;	} ?>" placeholder="ระบุ">
							</div>
							<label class="col-md-12 text-danger"><?php echo form_error("result"); ?></label>
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
