<?php
$title_prefix = 'เพิ่ม';
$action = base_url("kpi/save");
$prev = base_url("kpi/dashboard_kpi");
if (isset($data->kpi_id) && $data->kpi_id != '') {
	$title_prefix = 'แก้ไข';
	$action .= "/{$data->kpi_id}";
	$prev = base_url("kpi/view_kpi/{$data->kpi_id}");
}
?>
<p class="h4 header text-success">
	<i class="fa fa-file-text-o"></i> <?php echo $title_prefix; ?>รายการเกณฑ์การประเมิน
</p>

<div id="search-filter" class="widget-box">
	<div class="widget-body">
		<div class="widget-main">
			<form method="post" enctype="multipart/form-data" action="<?php echo $action; ?>">
				<div class="row">

					<div class="col-md-8">

						<div class="row">
							<div class="col-md-12">
								<label for="stext">ชื่อเกณฑ์การประเมิน</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<input type="text" name="kpi_name" class="form-control"
									   value="<?php if (isset($data->kpi_name)) { echo $data->kpi_name;	} ?>" placeholder="ระบุ">
							</div>
							<label 	class="col-md-12 text-danger"><?php echo form_error("kpi_name"); ?></label>
						</div>

						<div class="row">
							<div class="col-md-12">
								<label for="stext">หน่วยวัด</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<select class="form-control" name="unit_id">
									<?php foreach ($units_list as $key => $value) { ?>
										<option
											value="<?php echo $key; ?>" <?php if (isset($data->unit_id) && $data->unit_id == $key) {
											echo 'selected="selected"';
										} ?>><?php echo $value; ?></option>
									<?php } ?>
								</select>
							</div>
							<label 	class="col-md-12 text-danger"><?php echo form_error("unit_id"); ?></label>
							</div>
						<div class="row">
							<div class="col-md-12">
								<label for="stext">ระดับเกณฑ์การประเมิน</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<select class="form-control" name="level_id">
									<?php foreach ($level_list as $key => $value) { ?>
										<option
											value="<?php echo $key; ?>"<?php if (isset($data->level_id) && $data->level_id == $key) {
											echo 'selected="selected"';
										} ?>><?php echo $value; ?></option>
									<?php } ?>
								</select>
							</div>
							<label
								class="col-md-12 text-danger"><?php echo form_error("level_id"); ?></label>
						</div>

						<div class="row">
							<div class="col-md-12">
								<label for="stext">คำอธิบาย</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<textarea type="text" name="kpi_detail" cols="4" rows="5" class="form-control" placeholder="ระบุ"><?php if (isset($data->kpi_detail)) { echo $data->kpi_detail;	} ?></textarea>
							</div>
							<label
								class="col-md-12 text-danger"><?php echo form_error("kpi_detail"); ?></label>
						</div>
						<div class="row">
							<div class="col-md-12">
								<ul class="nav nav-tabs">
                  <li class="active"><a data-toggle="tab" href="#tab1" aria-expanded="true">ตัวแปรและสูตร</a></li>
                  <li class=""><a data-toggle="tab" href="#tab2" aria-expanded="false">เกณฑ์การให้คะแนน</a></li>
                  <li class=""><a data-toggle="tab" href="#tab3" aria-expanded="false">หมายเหตุ</a></li>
                  <li class=""><a data-toggle="tab" href="#tab4" aria-expanded="false">เงื่อนไข</a></li>
                  <li class=""><a data-toggle="tab" href="#tab5" aria-expanded="false">แหล่งข้อมูลและวิธีการจัดเก็บข้อมูล</a></li>
                </ul>
								<div class="tab-content">
                  <div id="tab1" class="tab-pane fade in active">
                  		<div class="row">
                         <div class="col-md-12">
                            <label for="kpi_formula" class="col-md-2 control-label text-right" style="margin-top: 7px !important">สูตรคำนวน</label>
                            <div class="col-md-9">
                                <textarea name="kpi_formula" id="kpi_formula" class="form-control" rows="3"><?php if(isset($data->kpi_formula)) { echo $data->kpi_formula; } ?></textarea>
                            <br>
                            </div>
                        </div>
                        <div class="col-md-12">

                       	  <div class="col-md-12 form-inline" align="">
                            <button type="button" onClick="listModal()"  class="btn btn-info btn-xs"  /><i class="fa fa-plus"></i> เพิ่มตัวแปร</button>
                            <input type="button" value="ลบตัวแปร" onclick="deleteRow('dataTable')" class="btn btn-danger btn-xs"  />
                            <br><br>
                            </div>
                            <table class="table" id="dataTable">
                            <tr>
                              <th class="text-center" width="5%">#</th>
                              <th width="5%" class="text-center">ลำดับ</th>
                              <th width="10%" class="text-center"><b>ตัวแปร</b></th>
                              <th width="" class="text-center"><b>ชื่อตัวแปร</b></th>
                              <th width="10%" class="text-center"><b>หน่วย</b></th>
                              <th width="15%" class="text-center"><b>ตัวแปรก่อนหน้า</b></th>
                            </tr>
                           <?php
													 			if(isset($formulas)){
																	foreach ($formulas as $key => $value) {
																		?>
																		<tr>
			                                <td><input type="checkbox" name="chk[]"></td>
			                                <td align="center"><?php echo $key+1?></td>
			                                <td><input type="text" class="form-control" name="formula_value[<?php echo $value->var_id?>]" value="<?php echo $value->formula_value?>"></td>
			                                <td><?php echo $variable_lists[$value->var_id]?></td>
			                                <td align="center"><?php echo $units_list[$variable_unit_lists[$value->var_id]]?></td>
			                                <td><input type="text" class="form-control" name="formula_depend[<?php echo $value->var_id?>]" value="<?php echo $value->depend?>"></td>
			                              </tr>
																		<?php
																	}
																}
														?>
                            </table>
                            <?php //if(count($get)==0){ ?>
                            <!-- <span id="textvar"></span> -->
                            <?php //} ?>
                            <br>
                      </div>
                        </div>
                  </div>
                  <div id="tab2" class="tab-pane fade">

                  	<div class="row">
                    		<div class="col-md-12">
                               <label for="kpi_standard_type"  class="col-md-2 control-label text-right" style="margin-top: 7px !important">จัดเรียง</label>
                                <div class="col-md-9">
                                   <select name="kpi_standard_type" id="kpi_standard_type" class="form-control"  onChange="getUnitType(this.value,2)">
                                   <option value="">รูปแบบ</option>
                                    <option value="1" <?php if(isset($data->kpi_standard_type) && $data->kpi_standard_type=='1'){ echo 'selected'; } ?>>รูปแบบที่ 1</option>
                                    <option value="2" <?php if(isset($data->kpi_standard_type) && $data->kpi_standard_type=='2'){ echo 'selected'; } ?>>รูปแบบที่ 2</option>
                                   </select>
                                <br>
                                </div>
                            </div>
                        	<span id="getUnitTypetext">
                            	<div class="col-md-12">
																		<?php
																		if(isset($data->kpi_standard_type)){
                                        if($data->kpi_standard_type=='1'){
                                    ?>
                                  <table class="table">
                                    <tr>
                                      <th width="100" class="text-center">ระดับ</th>
                                      <th width="100" class="text-center"><b>ค่าคะแนนถ่วงน้ำหนัก</b></th>
                                      <th width="" class="text-center"><b>คำอธิบาย</b></th>
                                    </tr>

                                    <tr>
                                        <td align="center">1</td>
                                        <td><input type="number"  step="any" class="form-control" name="kpi_standard_label1" id="kpi_standard_label1" value="<?php echo $data->kpi_standard_label1 ?>" style="width:100px"></td>
                                        <td><input type="text" class="form-control" name="kpi_standard_1" id="kpi_standard_1" value="<?php echo $data->kpi_standard_1 ?>" ></td>
                                    </tr>
                                    <tr>
                                        <td align="center">2</td>
                                        <td><input type="number"  step="any" class="form-control" name="kpi_standard_label2" id="kpi_standard_label2" value="<?php echo $data->kpi_standard_label2 ?>" style="width:100px"></td>
                                        <td><input type="text" class="form-control" name="kpi_standard_2" id="kpi_standard_2" value="<?php echo $data->kpi_standard_2 ?>" ></td>

                                    </tr>
                                    <tr>
                                        <td align="center">3</td>
                                        <td><input type="number"  step="any" class="form-control" name="kpi_standard_label3" id="kpi_standard_label3" value="<?php echo $data->kpi_standard_label3 ?>" style="width:100px"></td>
                                        <td><input type="text" class="form-control" name="kpi_standard_3" id="kpi_standard_3" value="<?php echo $data->kpi_standard_3 ?>"></td>

                                    </tr>
                                    <tr>
                                        <td align="center">4</td>
                                        <td><input type="number"  step="any" class="form-control" name="kpi_standard_label4" id="kpi_standard_label4" value="<?php echo $data->kpi_standard_label4 ?>" style="width:100px"></td>
                                        <td><input type="text" class="form-control" name="kpi_standard_4" id="kpi_standard_4" value="<?php echo $data->kpi_standard_4 ?>"></td>

                                    </tr>
                                    <tr>
                                        <td align="center">5</td>
                                        <td><input type="number" step="any" class="form-control" name="kpi_standard_label5" id="kpi_standard_label5" value="<?php echo $data->kpi_standard_label5 ?>" style="width:100px"></td>
                                        <td><input type="text" class="form-control" name="kpi_standard_5" id="kpi_standard_5" value="<?php echo $data->kpi_standard_5 ?>"></td>

                                    </tr>

                                    </table>
                                    <?php
                                        }else  if($data->kpi_standard_type=='2'){
                                    ?>
                                    <table class="table">
                                    <tr>
                                      <th width="25%" class="text-center">ระดับ</th>
                                      <th class="text-center">1</th>
                                      <th class="text-center">2</th>
                                      <th class="text-center">3</th>
                                      <th class="text-center">4</th>
                                      <th class="text-center">5</th>
                                    </tr>

                                    <tr>
                                      <td align="center"><b>ค่าคะแนนถ่วงน้ำหนัก</b></td>
                                      <td><input type="number"  step="any" class="form-control" name="kpi_standard_label1" id="kpi_standard_label1" value="<?php if(isset($data->kpi_standard_label1)){ echo $data->kpi_standard_label1; } ?>"></td>
														          <td><input type="number"  step="any" class="form-control" name="kpi_standard_label2" id="kpi_standard_label2" value="<?php if(isset($data->kpi_standard_label2)){ echo $data->kpi_standard_label2; } ?>"></td>
														          <td><input type="number"  step="any" class="form-control" name="kpi_standard_label3" id="kpi_standard_label3" value="<?php if(isset($data->kpi_standard_label3)){ echo $data->kpi_standard_label3; } ?>"></td>
														          <td><input type="number"  step="any" class="form-control" name="kpi_standard_label4" id="kpi_standard_label4" value="<?php if(isset($data->kpi_standard_label4)){ echo $data->kpi_standard_label4; } ?>"></td>
														          <td><input type="number"  step="any" class="form-control" name="kpi_standard_label5" id="kpi_standard_label5" value="<?php if(isset($data->kpi_standard_label5)){ echo $data->kpi_standard_label5; } ?>"></td>
                                    </tr>
                                    <tr>
                                        <td align="center"><strong>เกณฑ์การให้คะแนน</strong></td>
                                        <td><input type="number" step="any" class="form-control" name="kpi_standard_1" id="kpi_standard_1" value="<?php if(isset($data->kpi_standard_label1)){ echo $data->kpi_standard_1; } ?>"></td>
                                        <td><input type="number" step="any" class="form-control" name="kpi_standard_2" id="kpi_standard_2" value="<?php if(isset($data->kpi_standard_label1)){ echo $data->kpi_standard_2; } ?>"></td>
                                        <td><input type="number" step="any" class="form-control" name="kpi_standard_3" id="kpi_standard_3" value="<?php if(isset($data->kpi_standard_label1)){ echo $data->kpi_standard_3; } ?>"></td>
                                        <td><input type="number" step="any" class="form-control" name="kpi_standard_4" id="kpi_standard_4" value="<?php if(isset($data->kpi_standard_label1)){ echo $data->kpi_standard_4; } ?>"></td>
                                        <td><input type="number" step="any" class="form-control" name="kpi_standard_5" id="kpi_standard_5" value="<?php if(isset($data->kpi_standard_label1)){ echo $data->kpi_standard_5; } ?>"></td>
                                    </tr>

                                    </table>
                                    <?php
                                        }

																			}
                                    ?>
                                    <br>
                              </div>
                       		</span>
                    </div>

                  </div>
                  <div id="tab3" class="tab-pane fade">
                    <div class="row">
                             <div class="col-md-12">
                             	<b>ระบุรายละเอียด</b><br><br>
                             	<textarea name="kpi_note" class="form-control"  id="kpi_note"><?php if(isset($data->kpi_note)){ echo $data->kpi_note; }?></textarea>
                             </div>
                     </div>
                  </div>
                  <div id="tab4" class="tab-pane fade">
                    <div class="row">
                             <div class="col-md-12">
                             	<b>ระบุเงื่อนไข</b><br><br>
                             	<textarea name="kpi_condition" class="form-control"  id="kpi_condition"><?php if(isset($data->kpi_condition)){ echo $data->kpi_condition; }?></textarea>
                             </div>
                     </div>
                  </div>
                  <div id="tab5" class="tab-pane fade">
                  		<div class="row">
                             <div class="col-md-12">
                             	<b>ระบุแหล่งข้อมูลและวิธีการจัดเก็บข้อมูล</b><br><br>
                             	<textarea name="kpi_source" class="form-control"  id="kpi_source"><?php if(isset($data->kpi_source)){ echo $data->kpi_source; }?></textarea>
                             </div>
                     </div>
                  </div>
                </div>

							</div>
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

<!-- Modal -->
	 <div id="myModal" class="modal fade" role="dialog">
		 <div class="modal-dialog">

			 <!-- Modal content-->
			 <div class="modal-content">
				 <div class="modal-header">
					 <button type="button" class="close" data-dismiss="modal">&times;</button>
					 <h4 class="modal-title">ตัวแปร</h4>
				 </div>
				 <div class="modal-body">
						 <div class="row">
						 <div class="col-md-12">
							 <label>ชื่อตัวแปร</label>
								<input type="text" id="formula_value"  class=" form-control" >
								<br>
							 </div>
						 <div class="col-md-12">
							 <div id="listModal">
							 <label>ตัวแปร</label>
								<select name="var_id" id="var_id" class="form-control selectpicker"    style="width:100%" onchange="checklist(this.value);" data-live-search="true">
								 <option value="" id="def"  >**เลือกตัวแปร**</option>
								 <?php
								 if(isset($variable)){
									 foreach ($variable as $key => $value) {
 										?>
 										<option value="<?php echo $value->var_id; ?>:<?php echo $value->var_name; ?>:<?php echo $units_list[$value->var_unit_id]; ?>" id="<?php echo $value->var_name; ?>:<?php echo $value->var_id; ?>"> <?php echo $value->var_name; ?></option>
 										<?php
 								 	}
								 }
								 ?>
								 </select>
								 </div>
							 </div>
						 </div>


				 </div>
				 <div class="modal-footer">
					 <input type="button" value="Save" onclick="addRow('dataTable')" class="btn btn-info "  id="addpro" style="display:none"  data-dismiss="modal"/>
					 <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				 </div>
			 </div>

		 </div>
	 </div>
<script type="text/javascript">
function listModal() {
			$('#myModal').modal('show');

}

function checklist(value){
	if(value!=''){
		document.getElementById("addpro").style.display = '';

	}else{
		document.getElementById("addpro").style.display = 'none';

	}
}

function addRow(tableID) {

		var table = document.getElementById(tableID);

		var rowCount = table.rows.length;

		var row = table.insertRow(rowCount);

		var mystr = document.getElementById("var_id").value;
		if(mystr!=''){
			for (var i = document.getElementById("var_id").length - 1; i >= 0; --i) {
			if (document.getElementById("var_id").options[i].value == mystr) {
			 // document.getElementById("var_id").remove(i);
			// alert('ซ้ำ');
			 //return false;
			}
		  }


		document.getElementById("def").selected = "true";
		//	document.getElementById("product_id_select").value.style.display = 'none';


		var myarr = mystr.split(":");
		for(var i=0; i<rowCount; i++) {
			var rowCheck = table.rows[i];


			if(myarr[1]==rowCheck.cells[3].innerHTML){

			/*	swal({
							title:"รายการตัวแปรซ้ำ",
							type: "warning",
							confirmButtonColor: "#DD6B55",
							confirmButtonText: "ตกลง!"
						});

						return false;*/
			}
			//rowCount--;
			//i--;


		}
			var cell1 = row.insertCell(0);
			var element1 = document.createElement("input");
			element1.type = "checkbox";
			element1.name = "chk[]";

			cell1.appendChild(element1);

			var cell2 = row.insertCell(1);
			cell2.innerHTML = '<center>'+rowCount+'</center>';


			var cell3 = row.insertCell(2);
			var element2 = document.createElement("input");
			element2.type = "text";
			element2.name = "formula_value["+myarr[0]+"]";
			element2.className  = "form-control";
			element2.value= document.getElementById("formula_value").value;
			cell3.appendChild(element2);

			var cell4 = row.insertCell(3);
			cell4.innerHTML = myarr[1];

			var cell5 = row.insertCell(4);
			cell5.innerHTML = '<center>'+myarr[2]+'</center>';

      var cell6 = row.insertCell(5);
      var element3 = document.createElement("input");
      element3.type = "text";
      element3.name = "formula_depend["+myarr[0]+"]";
      element3.className  = "form-control";
      element3.value= '';
      cell6.appendChild(element3);

			document.getElementById("addpro").style.display = 'none';
			document.getElementById("formula_value").value = '';
		}


	}

	function deleteRow(tableID) {
		try {
		var table = document.getElementById(tableID);
		var rowCount = table.rows.length;

		for(var i=0; i<rowCount; i++) {
			var row = table.rows[i];
			var chkbox = row.cells[0].childNodes[0];
			if(null != chkbox && true == chkbox.checked) {
				table.deleteRow(i);
				rowCount--;
				i--;
			}

		}
		}catch(e) {
			alert(e);
		}
	}

	function getUnitType(str,type) {

	if (str.length == 0) {
		document.getElementById("getUnitTypetext").innerHTML = "";
		return;
	} else {
		if(type==1){
			/*if(str==1){
				document.getElementById('kpi_standard_type').value = '2';
				document.getElementById('unit_id').value = '1';
			}else{
				document.getElementById('kpi_standard_type').value = '1';
				document.getElementById('unit_id').value = '2';
			}*/
		}else{
			if(str==2){
				document.getElementById('kpi_standard_type').value = '2';
				//document.getElementById('unit_id').value = '1';
			}else{
				document.getElementById('kpi_standard_type').value = '1';
				//document.getElementById('unit_id').value = '2';
			}

		}
		$.ajax({
				url: '<?php echo base_url('kpi/ajax_get_unit_type/'); ?>'+$('#kpi_standard_type').val()+'/',
				type: "GET",
				success: function (data) {
					$('#getUnitTypetext').html(data)
				},
		})
	}
}

</script>
