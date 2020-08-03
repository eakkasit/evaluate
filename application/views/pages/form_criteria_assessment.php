<?php
$title_prefix = 'เพิ่ม';
$action = base_url("criteria_assessments/save");
$prev = base_url("criteria_assessments/dashboard_criteria_assessments");
if (isset($user_data->user_id) && $user_data->user_id != '') {
	$title_prefix = 'แก้ไข';
	$action .= "/{$user_data->user_id}";
	$prev = base_url("criteria_assessments/view_criteria_assessment/{$user_data->user_id}");
}
?>

<div class="row">
	<div class="col-md-6">
		<p class="h4  text-success">
			<i class="fa fa-file-text-o"></i> <?php echo $title_prefix; ?>หมวดหมู่ / เกณฑ์การประเมิน
		</p>
	</div>
	<div class="col-md-6 text-right">
		<a href="<?php echo $prev; ?>" class="table-link">
			<button type="button" class="btn btn-xs btn-info">
				<i class="fa fa-arrow-left"></i> ย้อนกลับ
			</button>
		</a>
		<a href="#" class="table-link"  onclick="addMainData()">
			<button type="button" class="btn btn-xs btn-success">
				<i class="fa fa-plus"></i>หมวดหมู่ / เกณฑ์การประเมิน
			</button>
		</a>
	</div>
</div>

<div id="search-filter" class="widget-box">
	<div class="widget-body">
		<div class="widget-main">
				<div class="row">
				<div class="col-md-5">
					<div class="" id="left_box"></div>
				</div>
				<div class="col-md-7" style="border-left: 1px solid #cccccc;">
					<form method="post" enctype="multipart/form-data" id="criteria_form" action="<?php echo $action; ?>" style="display:none">
						<input type="hidden" name="profile_id" id="profile_id" value="<?php echo $profile_id; ?>">
						<input type="hidden" name="parent_id" id="parent_id" value="0">
						<div class="row">
							<div class="col-md-12">
								<p class="h2 text-primary">หมวดหมู่ / เกณฑ์การประเมิน</p>
							</div>
						</div>
						<input type="hidden" name="criteria_id" id="criteria_id" value=""/>
						<div class="row">
							<div class="col-md-12">
								<label for="stext">ชื่อหมวด / เกณฑ์การประเมิน</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<input type="text" class="form-control" id="criteria_name" name="criteria_name" value=""/>
							</div>
							<label
								class="col-md-12 text-danger"><?php echo form_error("criteria_name"); ?></label>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="tabbable">
									<ul class="nav nav-tabs padding-12 " id="myTab4">
										<li class="active">
											<a data-toggle="tab" href="#variable" aria-expanded="true">ค่าตัวแปร</a>
										</li>

										<li class="">
											<a data-toggle="tab" href="#weight" id="tab-weight" aria-expanded="false"><span class="red">*</span> ค่าน้ำหนัก</a>
										</li>

									</ul>

									<div class="tab-content">
										<div id="variable" class="tab-pane active">
											<div class="row">
												<label class="col-md-4">สูตรคำนวณ</label>
												<div class="col-md-6">
													<textarea class="form-control" name="" id="" ></textarea>
												</div>
											</div>
											<div class="clearfix"></div>
											<table id="variable-table" class="table table-striped table-bordered table-hover">
											<thead>
												<tr>
													<th class="center">ลำดับที่</th>
													<th>ชื่อตัวแปร</th>
													<th>ตัวแปร</th>
													<th>ค่าตัวแปร</th>
													<th>ตัวแปรก่อนหน้า</th>
													<th>
														<button type="button" class="btn btn-sm btn-success">
															<i class="fa fa-plus"></i> เพิ่ม
														</button>
													</th>
												</tr>
											</thead>

											<tbody>
												<tr>
													<td class="center">1</td>
													<td><a href="#">v1</a></td>
													<td>1.1 ทดสอบตัวแปร</td>
													<td>1</td>
													<td></td>
													<td>
														<a href="#"
														   class="table-link"
														   onclick="delete_criteria_data('');" title="ลบ">
															<button type="button" class="btn btn-xs btn-danger">
																<i class="fa fa-trash-o"></i> ลบ
															</button>
														</a>
													</td>

												</tr>
												<tr>
													<td class="center">2</td>
													<td><a href="#">v2</a></td>
													<td>1.2 ทดสอบตัวแปร2</td>
													<td>1</td>
													<td>v1</td>
													<td>
														<a href="#"
														 class="table-link"
														 onclick="delete_criteria_data('');" title="ลบ">
														<button type="button" class="btn btn-xs btn-danger">
															<i class="fa fa-trash-o"></i> ลบ
														</button>
													</a>
												</td>
												</tr>
											</tbody>
										</table>
										</div>
										<div id="weight" class="tab-pane">
											<div class="row">
												<label class="col-md-4" for="weight">ค่าน้ำหนัก</label>
												<div class="col-md-6">
													<input type="text" name="weight" class="form-control" id="text-weight">
												</div>
												<div class="col-md-4"></div>
												<label
													class="col-md-6 text-danger"><?php echo form_error("weight"); ?></label>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>


						<div class="row">
							<div class="col-md-12 text-center">
								<a href="#" class="btn btn-sm btn-danger" onclick="reset()">
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
				</div>
			</div>
			</form>
		</div>
	</div>
</div>
<style>
	#left_box{
		height: 400px;
		overflow-x: hidden;
		overflow-y: auto;
	}
	.inactive{
		display: none;
	}
</style>
<script type="text/javascript">

		function getData(){
			$.ajax({
					url: '<?php echo base_url('criteria_assessments/ajax_get_data/'.$profile_id); ?>',
					type: "GET",
					success: function (data) {
						$('#left_box').html(data)
					},
			})
		}



		function addMainData() {
			$('#criteria_form').show();
			$('#parent_id').val(0);
			$('#criteria_name').val('');
			$('#criteria_id').val('');
			if(!$('#tab-weight span.inactive')[0]){
				$('#tab-weight span').addClass('inactive')
			}
		}

		function saveData() {
			// alert('a');
		}

		function reset() {
			$('#criteria_form').hide();
			$('#parent_id').val(0);
		}

		function addData(id) {
			$('#criteria_form').show();
			$('#parent_id').val(id)
			$('#criteria_name').val('')
			$('#criteria_id').val('');
			$('#tab-weight span').removeClass('inactive');
		}

		function editData(id) {
			// alert('edit data')
			$('#criteria_form').show();

			$.ajax({
						url: '<?php echo base_url("criteria_assessments/ajax_get_criteria_data/"); ?>' + id,
						type: "GET",
						success: function (data) {
							console.log('data',data);
							var edit_data = JSON.parse(data);
							console.log('edit data',edit_data);
							$('#criteria_name').val(edit_data['criteria_name'])
							$('#parent_id').val(edit_data['parent_id'])
							$('#criteria_id').val(edit_data['id'])
							$('#text-weight').val(edit_data['weight'])
							if($('#parent_id').val() == 0){
								if(!$('#tab-weight span.inactive')[0]){
									$('#tab-weight span').addClass('inactive')
								}
							}else{
								$('#tab-weight span').removeClass('inactive');
							}
						},

				})
		}

		function deleteData(id) {
			swal({
							title: "แจ้งเตือน",
							text: "ต้องการลบ หมวดหมู่ / เกณฑ์การประเมินนี้",
							type: "warning",
							showCancelButton: true,
							confirmButtonText: "ลบ",
							cancelButtonText: "ยกเลิก",
							closeOnConfirm: false,
							closeOnCancel: true
					},
					function (isConfirm) {
							if (isConfirm) {
									$.ajax({
												url: '<?php echo base_url("criteria_assessments/delete_criteria_assessment/"); ?>' + id,
												type: "GET",
												success: function (data) {
													getData();
													swal.close();
												},

										})
							}
					});
		}
    jQuery(document).ready(function () {
				getData();
				jQuery("#criteria_form").hide();
				jQuery("#criteria_name").blur(function(){
					if($(this).val() == ''){
						$(this).closest('.row').find('.text-danger').html('กรุณาระบุ ชื่อตัวแปรเกณฑ์การประเมิน')
					}else{
						$(this).closest('.row').find('.text-danger').html('')
					}
				})
        jQuery("#criteria_form").submit(function (e) {
						var invalid_form = false
						if($('#criteria_name').val() == ''){
							$('#criteria_name').closest('.row').find('.text-danger').html('กรุณาระบุ ชื่อตัวแปรเกณฑ์การประเมิน')
						}else if ($('#parent_id').val() !='0' && $('#text-weight').val() == '') {
							$('#text-weight').closest('.row').find('.text-danger').html('กรุณาระบุ ค่าน้ำหนัก')
						}else{
							$.ajax({
										url: '<?php echo $action; ?>',
										type: "POST",
										data:  $(this).serialize(),
										success: function (data) {
											getData();
											jQuery("#criteria_form").hide();
											$('#criteria_name').closest('.row').find('.text-danger').html('')
											$('#text-weight').closest('.row').find('.text-danger').html('')
										},
								})
						}

						e.preventDefault()
        });
    });
</script>
