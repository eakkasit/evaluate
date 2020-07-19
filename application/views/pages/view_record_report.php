<p class="h4 header text-success">
	<i class="fa fa-file-text-o"></i> รายงานการเข้าประชุม
</p>

<div id="search-filter" class="widget-box">
	<div class="widget-body">
		<div class="widget-main">
			<div class="row">
				<div class="col-md-12 text-right">

					<a href="<?php echo base_url("report_attends/record_reports"); ?>"
					   class="table-link" title="ย้อนกลับ">
						<button type="button" class="btn btn-xs btn-info">
							<i class="fa fa-arrow-left"></i> ย้อนกลับ
						</button></a>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="table-responsive">
	<table role="grid" id="table-example"
		   class="table table-bordered table-hover dataTable no-footer">
		<thead>
		<tr role="row">
			<th class="text-center" width="7%">วาระที่</th>
			<th class="text-center" width="15%">เรื่อง</th>
			<th class="text-center" width="10%">ประเภทวาระ</th>
			<th class="text-center" width="15%">เรื่องเดิม</th>
			<th class="text-center" width="23%">เนื้อหา</th>
			<th class="text-center" width="20%"></th>
		</tr>
		</thead>
		<tbody>
		<?php
		if (isset($agendas) && !empty($agendas)) {
			foreach ($agendas as $agenda) {
				?>
				<tr class="odd" role="row">
					<td class="text-center">
						<?php echo number_format($agenda->agenda_no, 0); ?>
					</td>
					<td class="text-left">
						<?php echo $agenda->agenda_name; ?>
					</td>
					<td class="text-center">
						<?php echo $type_list[$agenda->agenda_type_id]; ?>
					</td>
					<td class="text-left">
						<?php echo $agenda->agenda_story; ?>
					</td>
					<td class="text-left">
						<?php echo $agenda->agenda_detail; ?>
					</td>
					
					<td class="text-center white">
						<div>
							<a href="<?php echo base_url("report_attends/view_attend_report/{$meeting_data->meeting_id}/$agenda->agenda_id") ?>" title="แสดงรายงานการเข้าประชุม">
								<button type="button" class="btn btn-xs btn-primary">
									<i class="fa fa-file-text-o"></i> แสดงรายงานการเข้าประชุม
								</button>
							</a>
						</div>
					</td>
				</tr>
				<?php
			}
		} else {
			?>
			<tr class="odd" role="row">
				<td class="text-center" colspan="7">ไม่มีข้อมูลวาระการประชุมให้แสดง</td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
</div>
