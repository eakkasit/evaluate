<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
	<meta charset="utf-8"/>
	<title>ระบบแผนกลยุทธ์และงบประมาณ</title>

	<meta name="description" content="overview &amp; stats"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>

	<!-- bootstrap & fontawesome -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.css"/>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap-datetimepicker.css"/>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap-duallistbox.css"/>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap-editable.css"/>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap-multiselect.css"/>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap-timepicker.css"/>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/font-awesome.css"/>

	<!-- page specific plugin styles -->

	<!-- text fonts -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/ace-fonts.css"/>

	<!-- ace styles -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/ace.css" class="ace-main-stylesheet"
		  id="main-ace-style"/>

	<!--[if lte IE 9]>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/ace-part2.css" class="ace-main-stylesheet"/>
	<![endif]-->

	<!--[if lte IE 9]>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/ace-ie.css"/>
	<![endif]-->

	<!-- inline styles related to this page -->

	<!-- ace settings handler -->
	<script src="<?php echo base_url(); ?>assets/js/ace-extra.js"></script>

	<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

	<!--[if lte IE 8]>
	<script src="<?php echo base_url(); ?>assets/js/html5shiv.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/respond.js"></script>
	<![endif]-->

	<script src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/fuelux/fuelux.spinner.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/date-time/bootstrap-datepicker.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/date-time/bootstrap-timepicker.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/date-time/moment.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/date-time/daterangepicker.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/date-time/bootstrap-datetimepicker.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/fullcalendar.js"></script>

	<!--Fancybox-->
	<script src="<?php echo base_url(); ?>/tu_plan/shared/statics/js/fancybox/jquery.fancybox.pack.js"></script>
	<script src="<?php echo base_url(); ?>/tu_plan/shared/statics/js/fancybox/jquery.mousewheel-3.0.6.pack.js"></script>
	<link rel="stylesheet" href="<?php echo base_url(); ?>/tu_plan/shared/statics/js/fancybox/jquery.fancybox.css"
		  type="text/css" media="screen"/>

	<!--- sweetalert --->
	<script src="<?php echo base_url(); ?>/tu_plan/shared/statics/js/sweetalert/sweetalert.min.js"></script>
	<script src="<?php echo base_url(); ?>/tu_plan/shared/statics/js/sweetalert/sweetalert.js"></script>
	<link rel="stylesheet" type="text/css"
		  href="<?php echo base_url(); ?>/tu_plan/shared/statics/js/sweetalert/sweetalert.css"
		  media="screen"/>

	<!-- jquery.scrollTo.js -->
	<script src="<?php echo base_url(); ?>/tu_plan/shared/statics/js/jquery.scrollTo.js"></script>

	<!-- Custom -->
	<!--link rel="stylesheet" href="http://sbs-app.com/tu_plan/shared/layout/default/custom_style.css" type="text/css" media="screen"/-->

	<style>
		.img-thumbnail.img-custom {
			padding: 0;
		}

		/** =============== Custom CSS =============== **/

		.custom-content table.borderless, table.borderless td, table.borderless th {
			border: none !important;
		}

		.custom-content #search-filter .text-right .btn, #table-example.table td a > .btn, #table-example.table td a.label {
			margin-top: 5px;
		}

		.custom-content #search-filter {
			margin-bottom: 16px;
		}

		.page-header-custom #sbs-app-parent {
			color: #3aa75a !important;
		}

		.sidebar-custom .sidebar-sysname p {
			background-color: #4d4f56;
			color: #ffffff;
			margin: 0;
			font-weight: bold;
		}

		.sidebar-custom .sidebar-sysname p.sys-max {
			padding: 10px;
		}
	</style>
</head>

<body class="no-skin">
<!-- #section:basics/navbar.layout -->
<?php $this->load->view("template/top_menu"); ?>

<!-- /section:basics/navbar.layout -->
<div class="main-container" id="main-container">
	<script type="text/javascript">
        try {
            ace.settings.check('main-container', 'fixed')
        } catch (e) {
        }
	</script>

	<!-- #section:basics/sidebar -->
	<?php $this->load->view("template/left_menu"); ?>

	<!-- /section:basics/sidebar -->
	<div class="main-content">
		<div class="main-content-inner">
			<!-- #section:basics/content.breadcrumbs -->
			<?php $this->load->view("template/header"); ?>

			<!-- /section:basics/content.breadcrumbs -->

			<div class="col-md-12">
				<div class="main-box clearfix">
					<div class="main-box-body clearfix">
						<div class="col-md-12">
							<div class="main-box-body clearfix box_one_search search_list custom-content"
								 style="padding-bottom: 10px !important; margin-top: 10px !important;">
								<?php
								if (isset($content_text)) {
									echo $content_text;
								}
								if (isset($content_view)) {
									if (isset($content_data)) {
										$data = array();
										if (!empty($content_data)) {
											foreach ($content_data as $key => $value) {
												$data[$key] = $value;
											}
										}
										$this->load->view($content_view, $data);
									} else {
										$this->load->view($content_view);
									}
								}
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div><!-- /.page-content -->
	</div>
</div><!-- /.main-content -->

<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
	<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
</a>
</div><!-- /.main-container -->

<!-- basic scripts -->

<!--[if !IE]> -->
<script type="text/javascript">
    window.jQuery || document.write("<script src='<?php echo base_url(); ?>assets/js/jquery.js'>" + "<" + "/script>");
</script>

<!-- <![endif]-->

<!--[if IE]>
<script type="text/javascript">
	window.jQuery || document.write("<script src='<?php echo base_url(); ?>assets/js/jquery1x.js'>" + "<" + "/script>");
</script>
<![endif]-->
<script type="text/javascript">
    if ('ontouchstart' in document.documentElement) document.write("<script src='<?php echo base_url(); ?>assets/js/jquery.mobile.custom.js'>" + "<" + "/script>");
</script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap.js"></script>

<!-- page specific plugin scripts -->

<!--[if lte IE 8]>
<script src="<?php echo base_url(); ?>assets/js/excanvas.js"></script>
<![endif]-->
<script src="<?php echo base_url(); ?>assets/js/jquery-ui.custom.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.ui.touch-punch.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.easypiechart.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.sparkline.js"></script>
<script src="<?php echo base_url(); ?>assets/js/flot/jquery.flot.js"></script>
<script src="<?php echo base_url(); ?>assets/js/flot/jquery.flot.pie.js"></script>
<script src="<?php echo base_url(); ?>assets/js/flot/jquery.flot.resize.js"></script>

<!-- ace scripts -->
<script src="<?php echo base_url(); ?>assets/js/ace/elements.scroller.js"></script>
<script src="<?php echo base_url(); ?>assets/js/ace/elements.colorpicker.js"></script>
<script src="<?php echo base_url(); ?>assets/js/ace/elements.fileinput.js"></script>
<script src="<?php echo base_url(); ?>assets/js/ace/elements.typeahead.js"></script>
<script src="<?php echo base_url(); ?>assets/js/ace/elements.wysiwyg.js"></script>
<script src="<?php echo base_url(); ?>assets/js/ace/elements.spinner.js"></script>
<script src="<?php echo base_url(); ?>assets/js/ace/elements.treeview.js"></script>
<script src="<?php echo base_url(); ?>assets/js/ace/elements.wizard.js"></script>
<script src="<?php echo base_url(); ?>assets/js/ace/elements.aside.js"></script>
<script src="<?php echo base_url(); ?>assets/js/ace/ace.js"></script>
<script src="<?php echo base_url(); ?>assets/js/ace/ace.ajax-content.js"></script>
<script src="<?php echo base_url(); ?>assets/js/ace/ace.touch-drag.js"></script>
<script src="<?php echo base_url(); ?>assets/js/ace/ace.sidebar.js"></script>
<script src="<?php echo base_url(); ?>assets/js/ace/ace.sidebar-scroll-1.js"></script>
<script src="<?php echo base_url(); ?>assets/js/ace/ace.submenu-hover.js"></script>
<script src="<?php echo base_url(); ?>assets/js/ace/ace.widget-box.js"></script>
<script src="<?php echo base_url(); ?>assets/js/ace/ace.settings.js"></script>
<script src="<?php echo base_url(); ?>assets/js/ace/ace.settings-rtl.js"></script>
<script src="<?php echo base_url(); ?>assets/js/ace/ace.settings-skin.js"></script>
<script src="<?php echo base_url(); ?>assets/js/ace/ace.widget-on-reload.js"></script>
<script src="<?php echo base_url(); ?>assets/js/ace/ace.searchbox-autocomplete.js"></script>



<!-- the following scripts are used in demo only for onpage help and you don't need them -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/ace.onpage-help.css"/>
<link rel="stylesheet" href="<?php echo base_url(); ?>docs/assets/js/themes/sunburst.css"/>

<script type="text/javascript"> ace.vars['base'] = '..'; </script>
<script src="<?php echo base_url(); ?>assets/js/ace/elements.onpage-help.js"></script>
<script src="<?php echo base_url(); ?>assets/js/ace/ace.onpage-help.js"></script>
<script src="<?php echo base_url(); ?>docs/assets/js/rainbow.js"></script>
<script src="<?php echo base_url(); ?>docs/assets/js/language/generic.js"></script>
<script src="<?php echo base_url(); ?>docs/assets/js/language/html.js"></script>
<script src="<?php echo base_url(); ?>docs/assets/js/language/css.js"></script>
<script src="<?php echo base_url(); ?>docs/assets/js/language/javascript.js"></script>
</body>
</html>
