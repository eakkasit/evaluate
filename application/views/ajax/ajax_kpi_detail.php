<div class="panel-body">
<?php
if(isset($data) && !empty($data)){
						if($data->tree_type=='1'){
					?>
                	<div class="col-md-3">
                    	<label  class="col-md-6 control-label text-left" >เลขที่</label>
                    	<div class="col-md-6"><?php echo $data->tree_number?><br></div>
                    </div>
                    <div class="col-md-5">
                    	<label  class="col-md-5 control-label text-right" >หมวดหมู่</label>
                    	<div class="col-md-7"><?php echo $tree_parent_name?><br></div>
                    </div>
                    <div class="col-md-4">
                    	<label  class="col-md-6 control-label text-right" >หน่วยนับ</label>
                    	<div class="col-md-6"><?php echo $units_list[$data->unit_id]?><br></div>
                    </div>
                  <div class="col-md-12">
                    	<br>
                    	<b>กลุ่มตัวชี้วัดย่อย</b><hr>
                   	  <table width="100%" border="0" class="table">
                      <thead>
                      <tr>
                        <th width="6%">ประเภท</th>
                        <th width="14%">ลำดับ</th>
                        <th width="47%">รายการ</th>
                        <th width="23%">หน่วยนับ</th>
                        <th width="10%">&nbsp;</th>
                      </tr>
                      </thead>
                      <?php
						if(isset($parent_list)){
							foreach( $parent_list as $key => $value ){
								if($value->tree_type=='1'){
									$faicon = '<li class="fa fa-folder-o" title="หมวดหมู่"></li>';
								}else{
									$faicon = '<li class="fa fa-tachometer" title="เกณฑ์การประเมิน"></li>';
								}
					  ?>
                      <tr>
                        <td align="center"><?php echo $faicon?></td>
                        <td><?php echo $value->tree_number?></td>
                        <td><?php echo isset($tree_name[$value->tree_id])?$tree_name[$value->tree_id]:''?></td>
                        <td  align="center"><?php echo $units_list[$value->unit_id]?></td>
                        <td  align="center">
                        <!-- <a href="">
                        <li class="fa fa-trash"></li>
                        </a> -->
                        </td>
                      </tr>
                      <?php
							}
						}else{
					  ?>
                      <tr>
                        <td colspan="5">* ไม่มีกลุ่มตัวชี้วัดย่อย</td>
                      </tr>
                      <?php
						}
					 ?>

                    </table>

                    </div>
                  <?php
						}else{
				  ?>
                  <div class="col-md-12">
                    	<label  class="col-md-3 control-label text-right" >ตัวชี้วัดที่</label>
                    	<div class="col-md-8"><?php echo $data->tree_number?> <?php echo $kpi_data->kpi_name?><br></div>
                  </div>
                  <div class="col-md-6">
                    	<label  class="col-md-6 control-label text-right" style="margin-left:6px">หน่วยวัด</label>
                    	<div class="col-md-5"><?php echo $units_list[$kpi_data->unit_id]?><br></div>
                  </div>
                  <div class="col-md-6">
                  <div class="col-md-6">
                    	<label  class="col-md-7 control-label text-right" >เป้าหมาย</label>
                    	<div class="col-md-5"><?php echo $data->tree_target?><br></div>
                  </div>
                  <div class="col-md-6">
                    	<label  class="col-md-7 control-label text-right" >น้ำหนัก</label>
                    	<div class="col-md-5"><?php echo $data->tree_weight?><br></div>
                  </div>
                  </div>
                   <div class="col-md-12">
                    	<label  class="col-md-3 control-label text-right" >คำอธิบาย</label>
                    	<div class="col-md-9"><?php echo nl2br($kpi_data->kpi_detail)?><br></div>
                  </div>
                  <div class="col-md-12 form-group">
                    <label  class="col-md-3 control-label text-right" style="margin-top: 7px !important">เกณฑ์การให้คะแนน </label>
                    <label class="col-md-9 control-label" style="margin-top: 7px !important">
                        <?php if($kpi_data->kpi_standard_type=='1'){ echo 'รูปแบบที่ 1'; }else if($kpi_data->kpi_standard_type=='2'){ echo 'รูปแบบที่ 2'; } ?>

                    </label>
                    </div>
                    <div class="col-md-12 form-group">
                    <label  class="col-md-3 control-label text-right" > </label>
                    <div class="col-md-9" >
                         <?php
                                if($kpi_data->kpi_standard_type=='1'){
                            ?>
                          <table class="table">
                            <tr>
                              <th width="5%" class="text-center">ระดับ</th>
                              <th width="15%" class="text-center"><b>คำอธิบาย</b></th>
                            </tr>

                            <tr>
                                <td align="center">1</td>
                                <td  ><?php echo $kpi_data->kpi_standard_1?></td>
                            </tr>
                            <tr>
                                <td align="center">2</td>
                                <td  ><?php echo $kpi_data->kpi_standard_2?></td>
                            </tr>
                            <tr>
                                <td align="center">3</td>
                                <td  ><?php echo $kpi_data->kpi_standard_3?></td>
                            </tr>
                            <tr>
                                <td align="center">4</td>
                                <td  ><?php echo $kpi_data->kpi_standard_4?></td>
                            </tr>
                            <tr>
                                <td align="center">5</td>
                                <td  ><?php echo $kpi_data->kpi_standard_5?></td>
                            </tr>

                            </table>
                            <?php
                                }else{
                            ?>
                            <table class="table">
                            <tr>
                              <th width="10%" class="text-center">ระดับ</th>
                              <th class="text-center">1</th>
                              <th class="text-center">2</th>
                              <th class="text-center">3</th>
                              <th class="text-center">4</th>
                              <th class="text-center">5</th>
                            </tr>


                            <tr>
                                <td align="center"><strong>ร้อยละ</strong></td>
                                <td  class="text-center"><?php echo $kpi_data->kpi_standard_1?></td>
                                <td  class="text-center"><?php echo $kpi_data->kpi_standard_2?></td>
                                <td  class="text-center"><?php echo $kpi_data->kpi_standard_3?></td>
                                <td  class="text-center"><?php echo $kpi_data->kpi_standard_4?></td>
                                <td  class="text-center"><?php echo $kpi_data->kpi_standard_5?></td>
                            </tr>

                            </table>
                            <?php
                                }
                            ?>
                    </div>
                </div>
                 <div class="col-md-12 ">
                   <ul class="nav nav-tabs">
                      <li class="active"><a data-toggle="tab" href="#home" aria-expanded="false">ผู้กำกับดูแลตัวชี้วัด</a></li>
                      <li ><a data-toggle="tab" href="#home" aria-expanded="false">ผู้จัดทำข้อมูล</a></li>
                      <li ><a data-toggle="tab" href="#home" aria-expanded="false">ผู้รับรองตัวชี้วัด</a></li>
                    </ul>

                    <div class="tab-content">
                      <div id="home" class="tab-pane fade in active">
                       	<div class="row">
                        	<div class="col-md-12">
						              </div>
                        </div>
                      </div>
                    </div>
                   </div>
                  <?php
						}
          }
					?>
</div>
