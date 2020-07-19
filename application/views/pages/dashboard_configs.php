<div class="table-responsive">
	<form method="post" enctype="multipart/form-data"
		  action="<?php echo base_url("configs/save"); ?>">
		<table role="grid" id="table-example"
			   class="table table-bordered table-hover dataTable no-footer">
			<thead>
			<tr role="row">
				<th class="text-right start_no" colspan="3">
					<button type="submit" class="btn btn-sm btn-success" title="บันทึก">
						<i class="fa fa-floppy-o"></i> บันทึก
					</button>
				</th>
			</tr>
			</thead>
			<tbody>
			<?php
			if (isset($configs) && !empty($configs)) {
				foreach ($configs as $key => $config) {
					?>
					<tr class="odd" role="row">
						<td class="text-center" width="10%">
							<input type="checkbox" name="configs[]"
								   value="<?php echo $config->config_id; ?>" <?php if (isset($config->config_status) && in_array(strtolower($config->config_status), array('active'))) {
								echo 'checked="checked"';
							} ?>/>
						</td>
						<td class="text-left" width="80%">
							<label class="control-label">
								<?php echo $config->config_name; ?>
							</label>
							<p class="text-justify text-muted">
								<?php echo $config->config_description; ?>
							</p>
						</td>
						<td class="text-center" width="10%">
							<i class="fa fa-star-o" aria-hidden="true"></i>

							<i class="fa fa-info-circle" aria-hidden="true"></i>
						</td>
					</tr>
					<?php
				}
			}
			?>
			</tbody>
		</table>
	</form>
</div>
