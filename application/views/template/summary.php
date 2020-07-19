<div class="widget-box transparent">
	<div class="widget-header widget-header-flat">
		<h4 class="widget-title lighter">
			ข้อมูลสรุปของหน่วยงาน <?php echo $overview['organize_name']; ?>
		</h4>
	</div>

	<div class="widget-body">
		<div class="infobox infobox-green">
			<div class="infobox-icon">
				<i class="ace-icon fa fa-user"></i>
			</div>

			<div class="infobox-data">
				<span class="infobox-data-number"><?php echo number_format($overview['login_users'], 0); ?></span>
				<div class="infobox-content">ผู้ใช้งานระบบ</div>
			</div>

			<?php if(intval($overview['login_users_rate']) !== 0){ ?>
				<!-- #section:pages/dashboard.infobox.stat -->
				<div
					class="stat stat-<?php echo intval($overview['login_users_rate']) >= 0 ? 'success' : 'important'; ?>"><?php echo number_format($overview['login_users_rate'], 1); ?></div>
			<?php } ?>

			<!-- /section:pages/dashboard.infobox.stat -->
		</div>

		<div class="infobox infobox-blue2">
			<div class="infobox-progress">
				<!-- #section:pages/dashboard.infobox.easypiechart -->
				<div class="easy-pie-chart percentage" data-percent="100" data-size="46">
				</div>

				<!-- /section:pages/dashboard.infobox.easypiechart -->
			</div>

			<div class="infobox-data">
				<span class="infobox-text"><?php echo number_format($overview['sum_data'], 0); ?></span>

				<div class="infobox-content">
					จำนวนการประชุมปี <?php echo $overview['year'] + 543; ?>
				</div>
			</div>

			<?php if(intval($overview['sum_data_rate']) !== 0){ ?>
				<!-- #section:pages/dashboard.infobox.stat -->
				<div
					class="stat stat-<?php echo intval($overview['sum_data_rate']) >= 0 ? 'success' : 'important'; ?>"><?php echo number_format($overview['sum_data_rate'], 1); ?></div>
			<?php } ?>
		</div>

		<div class="infobox infobox-orange2">
			<!-- #section:pages/dashboard.infobox.sparkline -->
			<div class="infobox-chart">
				<span class="sparkline" data-values="196,128,202,177,154,94,100,170,224"></span>
			</div>

			<!-- /section:pages/dashboard.infobox.sparkline -->
			<div class="infobox-data">
				<span class="infobox-data-number"><?php echo number_format($overview['success_data'], 1); ?>%</span>
				<div class="infobox-content">ร้อยละที่ประชุมเสร็จสิ้น</div>
			</div>

			<?php if(intval($overview['success_data_rate']) !== 0){ ?>
				<!-- #section:pages/dashboard.infobox.stat -->
				<div
					class="stat stat-<?php echo intval($overview['success_data_rate']) >= 0 ? 'success' : 'important'; ?>"><?php echo number_format($overview['success_data_rate'], 1); ?></div>
			<?php } ?>
		</div>
	</div><!-- /.widget-body -->
</div><!-- /.widget-box -->
