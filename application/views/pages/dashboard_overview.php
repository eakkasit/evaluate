<div class="col-sm-5">
	<?php $this->load->view('template/calendar');?>
</div><!-- /.col -->

<div class="col-sm-7">
	<?php $this->load->view('template/summary'); ?>
</div><!-- /.col -->

<div class="vspace-12-sm"></div>

<div class="col-sm-12">

	<?php $this->load->view("template/search"); ?>

	<div class="table-responsive">
		<table role="grid" id="table-example"
			   class="table table-bordered table-hover dataTable no-footer">
			<thead>
			<tr role="row">
				<th class="text-center" width="5%">ลำดับ</th>
				<th class="text-center" width="10%">สถานะ</th>
				<th class="text-center" width="20%">การประชุม</th>
				<th class="text-center" width="15%">วันที่ประชุม</th>
				<th class="text-center" width="30%">รายละเอียดการประชุม</th>
				<th class="text-center" width="20%"></th>
			</tr>
			</thead>
			<tbody>
			<?php
			if (isset($meetings) && !empty($meetings)) {
				foreach ($meetings as $key => $meeting) {
					?>
					<tr class="odd" role="row">
						<td class="text-center">
							<?php echo number_format($key + 1, 0); ?>
						</td>
						<td class="text-center">
							<?php echo $status_list[$meeting->meeting_status]; ?>
						</td>
						<td class="text-left">
							<?php echo $meeting->meeting_name; ?>
						</td>
						<td class="text-center">
							<?php echo date_thai($meeting->meeting_date, false, false); ?>
						</td>
						<td class="text-left text-justify">
							<?php echo $meeting->agenda_name; ?>
						</td>
						<td class="text-center white">
							<div>

							</div>
						</td>
					</tr>
					<?php
				}
			}
			?>
			</tbody>
		</table>
	</div>
	<div class="pagination pull-right">
		<?php $this->load->view("template/pagination"); ?>
	</div>
</div>
