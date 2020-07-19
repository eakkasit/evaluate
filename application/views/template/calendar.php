<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/fullcalendar.css" />

<div class="widget-box">
	<div class="widget-header widget-header-flat widget-header-small">
		<h5 class="widget-title">
			<i class="ace-icon fa fa-calendar"></i>
			ปฎิทิน
		</h5>
	</div>

	<div class="widget-body">
		<div class="widget-main">
			<!-- #section:plugins/charts.flotchart -->
			<div id="calendar"></div>
		</div><!-- /.widget-main -->
	</div><!-- /.widget-body -->
</div><!-- /.widget-box -->
<script>
	jQuery(document).ready(function(){

        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay',
            },
            events: {
                url: '<?php echo base_url("datas/get_calendar_events"); ?>',
                error: function() {

                }
            },
            eventLimit:true,
            lang: 'th'
        });

	});
</script>
