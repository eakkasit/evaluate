<?php
$title_prefix = 'เพิ่ม';
$action = base_url("criteria_assessments/save");
$prev = base_url("structure/dashboard_structure");
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
		<a href="#" class="table-link" onclick="sss()" >
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
						<input type="hidden" name="profile_id" id="profile_id" value="<?php echo $structure_id; ?>">
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
													<textarea class="form-control" name="fomular" id="fomular" ></textarea>
													<label class="col-md-12 text-danger"><?php echo form_error("criteria_name"); ?></label>
												</div>
											</div>
											<div class="clearfix"></div>
											<div class="row">
												<div class="col-md-12">
													<table id="variable-table" class="table table-striped table-bordered table-hover">
														<thead>
															<tr>
																<th width="10%" class="center">ที่</th>
																<th width="25%">ชื่อตัวแปร</th>
																<th width="30%">ตัวแปร</th>
																<!-- <th>ค่าตัวแปร</th> -->
																<th width="25%">ตัวแปรก่อนหน้า</th>
																<th width="10%">
																	<button type="button" class="btn btn-sm btn-success"  data-toggle="modal" data-target="#add_variable_modal">
																		<i class="fa fa-plus"></i> เพิ่ม
																	</button>
																</th>
															</tr>
														</thead>
														<tbody>

														</tbody>
												</table>
												</div>
											</div>

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

<div id="add_variable_modal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">เพิ่มตัวแปร</h4>
			</div>
			<div class="modal-body">
				<form
					method="post" id="form_add_variable">
					<div class="row">
						<div class="col-md-12">
							<label for="stext">ชื่อตัวแปร</label>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<input type="text" class="form-control" name="variable_name" id="variable_name" value="">
							<input type="hidden" name="modal_criteria_id" id="modal_criteria_id" value="">
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<label for="stext">ตัวแปร</label>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<select class="form-control" name="variable_id">
								<?php foreach ($variable_lists as $variable_id => $variable) { ?>
									<option value="<?php echo $variable_id ?>"><?php echo $variable; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-xs btn-danger" data-dismiss="modal">
					<i class="fa fa-times"></i>
					ยกเลิก
				</button>
				<button type="button" class="btn btn-xs btn-success" onclick="submitVariable()">
					<i class="fa fa-save"></i>
					บันทึก
				</button>
			</div>
		</div>

	</div>
</div>


<form method="post" action="" name="formmodal" id="formmodal">
<div class="modal fade" id="addCriteria" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content ">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">จัดการรายการ</h4>
      </div>
      <div class="modal-body">
       	<div class="row">
        	<div class="col-md-12">
                <label for="tree_parent" class="col-md-2 control-label text-right" style="margin-top: 7px !important">รายการหลัก *</label>
                <div class="col-md-9">
								<input type="hidden" name="structure_id" id="structure_id" value="<?php echo $structure_id ?>">
                <select name="tree_parent" id="tree_parent" required class="form-control">
                	<option value="0">รายการหลัก</option>
									<?php echo $tree_list;  ?>
									<span id="test"></span>
                </select>
                <br></div>
           </div>
        	<div class="col-md-12">
                    <label for="tree_number" class="col-md-2 control-label text-right" style="margin-top: 7px !important">เลขที่ *</label>
                    <div class="col-md-9"><input type="text" class="form-control" name="tree_number" id="tree_number" value=""  required><br></div>
             	</div>
             <div class="col-md-12">
             	<label for="tree_type" class="col-md-2 control-label text-right" style="margin-top: 7px !important">เลือกรายการ *</label>
             	<div class="col-md-9">
                <label class="col-md-2 control-label "  style="margin-top: 7px !important;"><input type="radio" name="tree_type" value="1"  onClick="treeui(this.value)"> หมวดหมู่</label>
                <label class="col-md-2 control-label "  style="margin-top: 7px !important"><input type="radio" name="tree_type" value="2"  onClick="treeui(this.value)"> ตัวชี้วัด</label>
                </div>
             </div>
             <div id="treegroup"  style="display:none">
                <div class="col-md-12">
                    <label for="tree_name" class="col-md-2 control-label text-right" style="margin-top: 7px !important">กลุ่มตัวชี้วัด *</label>
                    <div class="col-md-9"><input type="text" class="form-control" name="tree_name" id="tree_name" value=""  required><br></div>
                </div>
                <div class="col-md-12">
                    <label for="var_unit_id" class="col-md-2 control-label text-right" style="margin-top: 7px !important">หน่วยวัด </label>
                    <div class="col-md-9" style="margin-left: 1px;">
                        <select class="form-control" name="unit_id" id="unit_id" >
                        <option value="">เลือกหน่วยวัด</option>
												<?php foreach ($units_list as $key => $value) { ?>
													<option value="<?php echo $key ?>"><?php echo $value; ?></option>
												<?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div  id="treekpi" style="display:none">
            	<div class="col-md-12">
                    <label for="tree_weight" class="col-md-2 control-label text-right" style="margin-top: 7px !important">น้ำหนัก *</label>
                    <div class="col-md-9"><input type="number" step="any" class="form-control" name="tree_weight" id="tree_weight" value=""><br></div>
                </div>
                <div class="col-md-12">
                    <label for="tree_target" class="col-md-2 control-label text-right" style="margin-top: 7px !important">เป้าหมาย *</label>
                    <div class="col-md-9"><input type="number" step="any" class="form-control" name="tree_target" id="tree_target" value=""><br></div>
                </div>
                 <div class="col-md-12" id="user_search_div" >
                    <label for="tree_number" class="col-md-2 control-label text-right" style="margin-top: 7px !important">ค้นหา *</label>
                    <div class="col-md-9"><input type="text" class="form-control" name="user_search" id="user_search" value=""  onKeyUp="SearchKPI(this.value);"><br></div>
                </div>

                    <div class="col-md-2 ">
                    </div>
                     <div class="col-md-9 ">
                     	<span id="SearchKPI">
                        </span>
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="savechange" value="savechange" class="btn btn-success" id="savechange" >บันทึก</button>
      </div>
    </div>
  </div>
</div>
</form>

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

		function submitVariable() {
			$('#form_add_variable').submit();
		}

		function getData(){
			$.ajax({
					url: '<?php echo base_url('criteria_assessments/ajax_get_data/'.$structure_id); ?>',
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
			$('#fomular').val('');
			$('#modal_criteria_id').val('');
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
			$('#fomular').val('');
			$('#modal_criteria_id').val('');
			$('#tab-weight span').removeClass('inactive');
		}

		function editData(id) {
			// alert('edit data')
			$('#criteria_form').show();
			load_variable_list(id);

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
							$('#modal_criteria_id').val(edit_data['id']);
							$('#fomular').val(edit_data['fomular']);
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

		function delete_variable(id) {
			$('#variable-table tbody tr:eq('+id+')').remove()
		}

		function load_variable_list(id) {
				$('#variable-table tbody').html('');
				$.ajax({
					url: '<?php echo base_url("criteria_assessments/ajax_get_variable"); ?>/'+id,
					type: "GET",
					// data:  $(this).serialize()+"&row="+$('#variable-table tbody tr').length,
					success: function (data) {
						console.log('data',data);
						$('#variable-table tbody').append(data);
						// addRowVariable(data)
						$('#add_variable_modal').modal('hide')
					},
				})
		}
    jQuery(document).ready(function () {
				// getData();
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
											if(data == "true"){
												getData();
												jQuery("#criteria_form").hide();
												$('#criteria_name').closest('.row').find('.text-danger').html('')
												$('#text-weight').closest('.row').find('.text-danger').html('')
											}else{
												swal({
				                    title: "ไม่สามารถบันทึกข้อมูลได้",
				                    text: "กรุณาตรวจสอบตัวแปร และข้อมูลต่างๆให้ครบถ้วนและไม่ซ้ำกัน",
				                    type: "error",
				                    showCancelButton: false,
				                    confirmButtonText: "ตกลง",
				                })
											}

										},
										error:function (e) {
											swal({
													title: "ไม่สามารถบันทึกข้อมูลได้",
													text: "กรุณาตรวจสอบตัวแปร และข้อมูลต่างๆให้ครบถ้วนและไม่ซ้ำกัน",
													type: "error",
													showCancelButton: false,
													confirmButtonText: "ตกลง",
											})
										}
								})
						}

						e.preventDefault()
        });
				jQuery("#form_add_variable").submit(function(e){
						// var invalid_form = false
						if($('#variable_name').val() == ''){
							$('#variable_name').closest('.row').find('.text-danger').html('กรุณาระบุ ชื่อตัวแปร')
						}else{
							$.ajax({
								url: '<?php echo base_url("criteria_assessments/ajax_save_variable"); ?>',
								type: "POST",
								data:  $(this).serialize()+"&row="+$('#variable-table tbody tr').length,
								success: function (data) {
									$('#variable-table tbody').append(data);
									// addRowVariable(data)
									$('#add_variable_modal').modal('hide')
								},
							})
						}

						e.preventDefault()
				})

				jQuery("#formmodal").submit(function(e){
					$.ajax({
						url: '<?php echo base_url("structure/ajax_save_tree"); ?>',
						type: "POST",
						data:  $(this).serialize(),
						success: function (data) {
							// $('#variable-table tbody').append(data);
							// // addRowVariable(data)
							$('#addCriteria').modal('hide')
							// aaaaa();
							window.location.reload();
							console.log('data',data)
						},
						error:function (error) {
							console.log('err',error)
						}
					})

					e.preventDefault();
				})
    });

		function treeui(str){
		if(str==1){
			document.getElementById("treegroup").style.display = '';
			document.getElementById("treekpi").style.display = 'none';
			document.getElementById("savechange").style.display = '';
		}else if(str==2){
			document.getElementById("treegroup").style.display = 'none';
			document.getElementById("treekpi").style.display = '';
			document.getElementById("savechange").style.display = 'none';
		}else{
			document.getElementById("treegroup").style.display = 'none';
			document.getElementById("treekpi").style.display = 'none';
			document.getElementById("savechange").style.display = 'none';
		}
	}

	function sss(){

		$.ajax({
				url: '<?php echo base_url("structure/test"); ?>',
				success: function (data) {
					// $('#variable-table tbody').append(data);
					// // addRowVariable(data)
					$('#test').html(data);
					$('#addCriteria').modal('show');
					// $('#addCriteria').modal('hide')
					console.log('data',data)
				},
				error:function (error) {
					console.log('err',error)
				}
		})
	}

	function aaaaa(){

		$.ajax({
				url: '<?php echo base_url("structure/test"); ?>',
				success: function (data) {
					// $('#variable-table tbody').append(data);
					// // addRowVariable(data)
					$('#test').html(data);
					// $('#addCriteria').modal('hide')
					console.log('data',data)
				},
				error:function (error) {
					console.log('err',error)
				}
		})
	}


	function SearchKPI(str) {

		document.getElementById("SearchKPI").innerHTML = "Loading . . . ";
		if (str.length == 0) {
			document.getElementById("SearchKPI").innerHTML = "";
			return;
		} else {
			document.getElementById("SearchKPI").style.display = '';

			$.ajax({
				url: "<?php echo base_url("structure/ajax_search_kpi"); ?>/?keyword="+str+"&structure_id=<?php echo $structure_id ?>&tree_id="+ document.getElementById("tree_parent").value+"&tree_number="+ document.getElementById("tree_number").value+"&tree_weight="+ document.getElementById("tree_weight").value+"&tree_target="+ document.getElementById("tree_target").value,
				success:function(data){
					console.log('data search',data);
					$('#SearchKPI').html(data);
				},
				error:function(error){

				}
			})

		}
	}
</script>
