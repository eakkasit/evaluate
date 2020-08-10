<table width="100%" class="table">
		<thead>
			<tr>
				<th width="5%" class=" text-center">ลำดับ</th>
				<th width="46%" class=" text-center">ชื่อตัวชี้วัด</th>
				<th width="23%" class=" text-center">หน่วยวัด</th>
			</tr>
		</thead>
		<tbody>
                        	<?php
				// $strSQL = "SELECT * FROM kpi_data WHERE kpi_id!='' AND kpi_name LIKE '%".$_GET['keyword']."%' ORDER BY kpi_id DESC ";
				// $objQuery  = $kpi->get_results($strSQL);
				// if(count($objQuery)>0){
        if(isset($data)){
					// $i=$Per_Page*$Page-$Per_Page;
					foreach( $data as $key => $value ){
						// $i++;
						// $unit_name = $kpi->get_var("SELECT unit_name FROM kpi_unit WHERE unit_id='$row->unit_id' ");
            //
						// $kpicheck = $kpi->get_var("SELECT kpi_id FROM kpi_tree WHERE kpi_id='$row->kpi_id' AND  structure_id='".$_GET['structure_id']."' ");

				// if($kpicheck==''){
			?>
			<tr class="tr-link">
				<td onclick="document.location = '<?php echo base_url('structure/ajax_save_kpi/'); ?>?tree_id=<?php echo $tree_id;?>&structure_id=<?php echo $structure_id?>&kpi_id=<?php echo $value->kpi_id ?>&tree_number=<?php echo $tree_number?>&tree_weight=<?php echo $tree_weight?>&tree_target=<?php echo $tree_target?>&unit_id=<?php echo $value->unit_id?>'">
                                <?php echo $key+1 ?>
                                </td>
				<td  >
                               <?php echo $value->kpi_name?>
                                </td>
				<td style="text-align:center"  >
                                <?php echo $units_list[$value->unit_id]?>
                                </td>
			</tr>
                            <?php
				// }else{
			?>
                            <!-- <tr >
				<td style="text-align:center"  >
                                <?php //echo $i?>
                                </td>
				<td  >
                               <?php //echo $row->kpi_name?> <i><font color="#FF0004">(ถูกใช้งานแล้ว)</font></i>
                                </td>
				<td style="text-align:center">
                                <?php //echo $unit_name?>
                                </td>
			</tr> -->
                            <?
				// }
					}
				}
			?>
		</tbody>
		<tfoot>
		</tfoot>
	</table>
