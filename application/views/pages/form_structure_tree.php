<?php
$title_prefix = 'เพิ่ม';
// $action = base_url("criteria_assessments/save");
$prev = base_url("structure/dashboard_structure");
// if (isset($user_data->user_id) && $user_data->user_id != '') {
// 	$title_prefix = 'แก้ไข';
// 	$action .= "/{$user_data->user_id}";
// 	$prev = base_url("criteria_assessments/view_criteria_assessment/{$user_data->user_id}");
// }
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
		<a href="#" class="table-link" onclick="addTree()" >
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
					<div id="treedetail"></div>
		</div>
	</div>
</div>

<form method="post" action="" name="formmodal" id="formmodal">
<div class="modal fade" id="addKpi" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
									<span id="tree_list"></span>
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

		function getData(){
			$.ajax({
					url: '<?php echo base_url('structure/ajax_kpi_tree/'.$structure_id); ?>',
					type: "GET",
					success: function (data) {
						$('#left_box').html(data)
					},
			})
		}


		function showData(structure_id,tree_id) {
			$.ajax({
				url: '<?php echo base_url("structure/ajax_tree_detail/"); ?>' + structure_id+'/'+tree_id,
				type: "GET",
				success: function (data) {
					console.log(data);
					$('#treedetail').html(data);
				},
				error: function(err){

				}
			})
		}

		function editData(id) {
			// alert('edit data')
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
									// $.ajax({
									// 			url: '<?php //echo base_url("criteria_assessments/delete_criteria_assessment/"); ?>' + id,
									// 			type: "GET",
									// 			success: function (data) {
									// 				getData();
									// 				swal.close();
									// 			},
									//
									// 	})
							}
					});
		}
    jQuery(document).ready(function () {
				getData();

				jQuery("#formmodal").submit(function(e){
					$.ajax({
						url: '<?php echo base_url("structure/ajax_save_tree"); ?>',
						type: "POST",
						data:  $(this).serialize(),
						success: function (data) {
							// $('#variable-table tbody').append(data);
							// // addRowVariable(data)
							$('#addKpi').modal('hide')
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

	function addTree(){

		$.ajax({
				url: '<?php echo base_url("structure/ajax_tree_list/").$structure_id; ?>',
				success: function (data) {
					// $('#variable-table tbody').append(data);
					// // addRowVariable(data)
					$('#tree_list').html(data);
					$('#addKpi').modal('show');
					// $('#addKpi').modal('hide')
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
