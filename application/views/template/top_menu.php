<div id="navbar" class="navbar navbar-default">
	<script type="text/javascript">
        try {
            ace.settings.check('navbar', 'fixed')
        } catch (e) {
        }
	</script>

	<div class="navbar-container" id="navbar-container">
		<!-- #section:basics/sidebar.mobile.toggle -->
		<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
			<span class="sr-only">Toggle sidebar</span>

			<span class="icon-bar"></span>

			<span class="icon-bar"></span>

			<span class="icon-bar"></span>
		</button>

		<!-- /section:basics/sidebar.mobile.toggle -->
		<div class="navbar-header pull-left">
			<!-- #section:basics/navbar.layout.brand -->
			<a href="#" class="navbar-brand">
				<small>
					<i class="fa fa-database"></i>
					ระบบแผนกลยุทธ์และงบประมาณ
				</small>
			</a>

			<!-- /section:basics/navbar.layout.brand -->

			<!-- #section:basics/navbar.toggle -->

			<!-- /section:basics/navbar.toggle -->
		</div>

		<!-- #section:basics/navbar.dropdown -->
		<div class="navbar-buttons navbar-header pull-right" role="navigation">
			<ul class="nav ace-nav">

				<!-- #section:basics/navbar.user_menu -->
				<?php if ($this->session->userdata('user_id') != '') { ?>
					<li class="light-blue">
						<a data-toggle="dropdown" href="#" class="dropdown-toggle">
							<?php
							$profile_picture = base_url("assets/images/no_images.jpg");
							if ($this->session->userdata('profile_picture') != '') {
								$profile_picture = base_url("assets/uploads/{$this->session->userdata('profile_picture')}");
							}
							?>
							<img class="nav-user-photo" src="<?php echo $profile_picture; ?>" alt="User Photo"/>
							<span class="user-info">
									<small>ผู้ใช้</small>
									<?php //echo $this->Commons_model->getPrefixList($this->session->userdata('prename')) . ' ' . $this->session->userdata('name') . '   ' . $this->session->userdata('surname'); ?>
								</span>

							<i class="ace-icon fa fa-caret-down"></i>
						</a>

						<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
							<li>
								<a href="<?php echo base_url("authentications/logout"); ?>">
									<i class="ace-icon fa fa-power-off"></i>
									ออกจากระบบ
								</a>
							</li>
						</ul>
					</li>
				<?php } ?>
				<!-- /section:basics/navbar.user_menu -->
			</ul>
		</div>

		<!-- /section:basics/navbar.dropdown -->
	</div><!-- /.navbar-container -->
</div>
