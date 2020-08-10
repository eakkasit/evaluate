<p class="h4 header text-success">
	<i class="fa fa-file-text-o"></i> เกณฑ์การประเมิน
</p>

<div id="search-filter" class="widget-box">
	<div class="widget-body">
		<div class="widget-main">
			<div class="row">
				<div class="col-md-12 text-right">
					<a href="<?php echo base_url("kpi/dashboard_kpi"); ?>"
					   class="table-link" title="ย้อนกลับ">
						<button type="button" class="btn btn-xs btn-info">
							<i class="fa fa-arrow-left"></i> ย้อนกลับ
						</button></a>

					<a href="<?php echo base_url("kpi/edit_kpi/{$data->kpi_id}"); ?>"
					   class="table-link" title="แก้ไข">
						<button type="button" class="btn btn-xs btn-warning">
							<i class="fa fa-edit"></i> แก้ไข
						</button></a>

					<?php // if (in_array(strtolower($data->user_status), array('active'))) { ?>
						<a href="#"
						   class="table-link"
						   onclick="delete_user(<?php echo $data->kpi_id; ?>);" title="ลบ">
							<button type="button" class="btn btn-xs btn-danger">
								<i class="fa fa-trash-o"></i> ลบ
							</button></a>
					<?php //} ?>
				</div>
			</div>

			<div class="row">
							<div class="col-md-12">
                	<div class="col-md-12 form-group">
                    	<label  class="col-md-2 control-label text-right" style="margin-top: 7px !important">ชื่อเกณฑ์การประเมิน </label>
											<label class="col-md-9 control-label" style="margin-top: 7px !important"><?php if(isset($data->kpi_name)){ echo $data->kpi_name; }?><br></label>
                  </div>
									<div class="col-md-12">
                      <label  class="col-md-2 control-label text-right" style="margin-top: 7px !important">หน่วยวัด </label>
											<label class="col-md-6 control-label" style="margin-top: 7px !important"><?php echo $units_list[$data->unit_id]; ?></label>
											<label  class="col-md-2 control-label text-right" style="margin-top: 7px !important" >ระดับเกณฑ์การประเมิน </label>
											<label class="col-md-2 control-label" style="margin-top: 7px !important"><?php echo $level_list[$data->level_id]; ?><br></label>
                  </div>
                  <div class="col-md-12">
                  	<label  class="col-md-2 control-label text-right" style="margin-top: 7px !important">คำอธิบาย</label>
                  	<label class="col-md-9 control-label" style="margin-top: 7px !important"><?php echo nl2br($data->kpi_detail); ?></label>
                  </div>
                	<div class="col-md-12 form-group">
                    	<label  class="col-md-2 control-label text-right" style="margin-top: 7px !important">ตัวแปรและสูตร </label>
											<label class="col-md-9 control-label" style="margin-top: 7px !important">
                        	สูตรคำนวน : <?php if(isset($data->kpi_formula)){ echo $data->kpi_formula; } ?>
											</label>
                  </div>
                  <div class="col-md-12 form-group">
	                  <label  class="col-md-2 control-label text-right" style="margin-top: 7px !important"> </label>
										<div class="col-md-9" style="margin-top: 7px !important">
	                      <table class="table" id="dataTable">
	                        <tr>
	                          <th width="10%" class="text-center"><b>ตัวแปร</b></th>
		                        <th width="" class="text-center"><b>ชื่อตัวแปร</b></th>
		                        <th width="10%" class="text-center"><b>หน่วย</b></th>
		                        <th width="15%" class="text-center"><b>ต้องบันทึก ตัวแปรก่อนหน้า</b></th>
	                        </tr>
													<?php
															 if(isset($formulas)){
																 foreach ($formulas as $key => $value) {
																	 ?>
																	 <tr>
																		 <td align="center"><?php echo $key+1?></td>
																		 <td><?php echo $variable_lists[$value->var_id]?></td>
																		 <td align="center"><?php echo $units_list[$variable_unit_lists[$value->var_id]]?></td>
																		 <td align="center"><?php echo $value->depend?></td>
																	 </tr>
																	 <?php
																 }
															 }
													 ?>
												 </table>
			                  </div>
		              	</div>
                    <div class="col-md-12 form-group">
                    	<label  class="col-md-2 control-label text-right" style="margin-top: 7px !important">เกณฑ์การให้คะแนน </label>
											<label class="col-md-9 control-label" style="margin-top: 7px !important"></label>
                    </div>
                  	<div class="col-md-12 form-group">
                        <label  class="col-md-2 control-label text-right" style="margin-top: 7px !important"> </label>
												<div class="col-md-9" style="margin-top: 7px !important">
                          <?php
														if($data->kpi_standard_type=='1'){
													?>
												  <table class="table">
													<tr>
													  <th width="100" class="text-center">ระดับ</th>
													  <th width="100" class="text-center">ค่าคะแนนถ่วงน้ำหนัก</th>
													  <th width="" class="text-center"><b>คำอธิบาย</b></th>
													</tr>

													<tr>
														<td align="center">1</td>
														<td  class="text-center"><?php if(isset($data->kpi_standard_label1)){ echo $data->kpi_standard_label1; }?></td>
														<td  class=""><?php if(isset($data->kpi_standard_1)){ echo $data->kpi_standard_1; }?></td>
													</tr>
													<tr>
														<td align="center">2</td>
														<td  class="text-center"><?php if(isset($data->kpi_standard_label2)){ echo $data->kpi_standard_label2; }?></td>
														<td  class=""><?php if(isset($data->kpi_standard_2)){ echo $data->kpi_standard_2; }?></td>
													</tr>
													<tr>
														<td align="center">3</td>
														<td  class="text-center"><?php if(isset($data->kpi_standard_label3)){ echo $data->kpi_standard_label3; }?></td>
														<td  class=""><?php if(isset($data->kpi_standard_3)){ echo $data->kpi_standard_3; }?></td>
													</tr>
													<tr>
														<td align="center">4</td>
														<td  class="text-center"><?php if(isset($data->kpi_standard_label4)){ echo $data->kpi_standard_label4; }?></td>
														<td  class=""><?php if(isset($data->kpi_standard_4)){ echo $data->kpi_standard_4; }?></td>
													</tr>
													<tr>
														<td align="center">5</td>
														<td  class="text-center"><?php if(isset($data->kpi_standard_label5)){ echo $data->kpi_standard_label5; }?></td>
														<td  class=""><?php if(isset($data->kpi_standard_5)){ echo $data->kpi_standard_5; }?></td>
													</tr>

													</table>
													<?php
														}else{
													?>
													<table class="table">
													<tr>
													  <th width="20%" class="text-center">ระดับ</th>
													  <th class="text-center">1</th>
													  <th class="text-center">2</th>
													  <th class="text-center">3</th>
													  <th class="text-center">4</th>
													  <th class="text-center">5</th>
													</tr>


													<tr>
													  <td align="center"><strong>ค่าคะแนนถ่วงน้ำหนัก</strong></td>
													  <td  class="text-center"><?php if(isset($data->kpi_standard_label1)){ echo $data->kpi_standard_label1; }?></td>
													  <td  class="text-center"><?php if(isset($data->kpi_standard_label2)){ echo $data->kpi_standard_label2; }?></td>
													  <td  class="text-center"><?php if(isset($data->kpi_standard_label3)){ echo $data->kpi_standard_label3; }?></td>
													  <td  class="text-center"><?php if(isset($data->kpi_standard_label4)){ echo $data->kpi_standard_label4; }?></td>
													  <td  class="text-center"><?php if(isset($data->kpi_standard_label5)){ echo $data->kpi_standard_label5; }?></td>
													  </tr>
													<tr>
														<td align="center"><strong>ร้อยละ</strong></td>
														<td  class="text-center"><?php if(isset($data->kpi_standard_1)){ echo $data->kpi_standard_1; }?></td>
														<td  class="text-center"><?php if(isset($data->kpi_standard_2)){ echo $data->kpi_standard_2; }?></td>
														<td  class="text-center"><?php if(isset($data->kpi_standard_3)){ echo $data->kpi_standard_3; }?></td>
														<td  class="text-center"><?php if(isset($data->kpi_standard_4)){ echo $data->kpi_standard_4; }?></td>
														<td  class="text-center"><?php if(isset($data->kpi_standard_5)){ echo $data->kpi_standard_5; }?></td>
													</tr>

													</table>
													<?php
														}
													?>
                        </div>
                    </div>
                    <div class="col-md-12 form-group">
                        <label  class="col-md-2 control-label text-right" style="margin-top: 7px !important">หมายเหตุ </label>
                        <div class="col-md-9 control-label" style="margin-top: 7px !important">
                            <?php if(isset($data->kpi_note)){ echo $data->kpi_note; }?>

                        </div>
                    </div>

                    <div class="col-md-12 form-group">
                        <label  class="col-md-2 control-label text-right" style="margin-top: 7px !important">เงื่อนไข </label>
                        <div class="col-md-9 control-label" style="margin-top: 7px !important">
                            <?php if(isset($data->kpi_condition)){echo $data->kpi_condition; }?>

                        </div>
                    </div>

                    <div class="col-md-12 form-group">
                        <label  class="col-md-2 control-label text-right" style="margin-top: 7px !important">แหล่งข้อมูลและ <br>วิธีการจัดเก็บข้อมูล </label>
                        <div class="col-md-9 control-label" style="margin-top: 7px !important">
                            <?php if(isset($data->kpi_source)){echo $data->kpi_source; }?>

                        </div>
                    </div>
							</div>
						</div>
		</div>
	</div>
</div>

<script type="text/javascript">
    function delete_user(id) {
        swal({
                title: "แจ้งเตือน",
                text: "ต้องการลบเกณฑ์การประเมิน",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "ลบ",
                cancelButtonText: "ยกเลิก",
                closeOnConfirm: false,
                closeOnCancel: true
            },
            function (isConfirm) {
                if (isConfirm) {
                    location.href = '<?php echo base_url("kpi/delete_kpi/"); ?>' + id;
                }
            });
    }
</script>
