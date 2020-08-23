<?php $this->load->view("template/search"); ?>

<div class="table-responsive">
	<table role="grid" id="table-example"
		   class="table table-bordered table-hover dataTable no-footer">
		<thead>
		<tr role="row">
			<th class="text-center start_no" width="5%" rowspan="2">ลำดับ</th>
			<th class="text-center" width="35%" rowspan="2">ชื่อโครงการ</th>
			<th class="text-center" width="10%" rowspan="2">ระยะเวลาดำเนินการ</th>
			<th class="text-center" width="25%" colspan="5">ค่าน้ำหนักแต่ละปีงบประมาณ</th>
			<th class="text-center" width="25%" colspan="5">คะแนนแต่ละปีงบประมาณ</th>
		</tr>
		<tr>
			<?php
				$year = (date('Y')+543);
				for ($i=0; $i < 5; $i++) {
					?>
					<th class="text-center"><?php echo $year+$i; ?></th>
					<?php
				}
				$year = (date('Y')+543);
				for ($i=0; $i < 5; $i++) {
					?>
					<th class="text-center"><?php echo $year+$i; ?></th>
					<?php
				}
			?>
		</tr>
		</thead>
		<tbody>
		<?php
		$no = 1;
		// if (isset($_GET['per_page'])) $no += intval($_GET['per_page']);
		if (isset($datas) && !empty($datas)) {
			foreach ($datas as $key => $data) {
				?>
				<tr class="odd" role="row">
					<td class="text-center">
						<?php
						echo number_format($no + $key, 0);
						?>
					</td>
					<td class="text-left">
						<?php echo $data->project_name; ?>
					</td>
					<td class="text-left">
						<?php echo $data->year; ?>
					</td>
					<?php
						for ($i=0; $i < 5; $i++) {
							?>
							<th class="text-center"></th>
							<?php
						}
						for ($i=0; $i < 5; $i++) {
							?>
							<th class="text-center"></th>
							<?php
						}
					?>
				</tr>
				<?php
			}
		}
		?>
		</tbody>
	</table>
</div>
<div class="pagination pull-right">
	<?php //$this->load->view("template/pagination"); ?>
</div>
<script type="text/javascript">
    function delete_criteria_assessment(user_id) {
        swal({
                title: "แจ้งเตือน",
                text: "ต้องการระงับผู้ใช้งานนี้",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "ระงับ",
                cancelButtonText: "ยกเลิก",
                closeOnConfirm: false,
                closeOnCancel: true
            },
            function (isConfirm) {
                if (isConfirm) {
                    location.href = '<?php echo base_url("criteria_assessments/delete_criteria_assessment/"); ?>' + user_id;
                }
            });
    }
</script>
