<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE') OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE') OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE') OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ') OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE') OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE') OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE') OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE') OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE') OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT') OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT') OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS') OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR') OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG') OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE') OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS') OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT') OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE') OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN') OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX') OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

/**
 * Custom defines
 */
define('SYSTEM_MENU', json_encode(// เมนูระบบ
	array(
		array(
			'name' => 'โครงการ / กิจกรรม',
			'icon' => 'fa fa-book',
			'base_url' => '',
			'menu' => array(
				'activities' => array(
					'name' => 'บันทึกโครงการ / กิจกรรม',
					'icon' => 'fa  fa-book',
					'base_url' => 'activities',
				),
			),
		),
		array(
			'name' => 'ประเมินองค์กรรายปี',
			'icon' => 'fa fa-users',
			'base_url' => '',
			'menu' => array(
				// 'criteria_variable' => array(
				// 	'name' => 'ตัวแปรเกณฑ์การประเมิน',
				// 	'icon' => 'fa fa-users',
				// 	'base_url' => 'criteria_variables',
				// ),
				'structure' => array(
					'name' => 'แม่แบบเกณฑ์การประเมิน',
					'icon' => 'fa fa-users',
					'base_url' => 'structure',
				),
				'kpi' => array(
					'name' => 'เกณฑ์การประเมิน',
					'icon' => 'fa fa-users',
					'base_url' => 'kpi',
				),
				'variable' => array(
					'name' => 'ตัวแปรเกณฑ์การประเมิน',
					'icon' => 'fa fa-users',
					'base_url' => 'variable',
				),
				// 'criteria_main' => array(
				// 	'name' => 'แม่แบบเกณฑ์การประเมิน',
				// 	'icon' => 'fa fa-users',
				// 	'base_url' => 'criteria_themes',
				// ),

				'criteria' => array(
					'name' => 'บันทึกการประเมินองค์กรรายปี',
					'icon' => 'fa fa-users',
					'base_url' => 'criteria',
				),
				'reports' => array(
					'name' => 'รายงานเป้าหมายการดำเนินงานตามตัวชี้วัด',
					'icon' => 'fa fa-file-text-o',
					'base_url' => 'report_assessments',
				),
			),
		),
		array(
			'name' => 'ประเมินผลรายปี',
			'icon' => 'fa fa-calendar',
			'base_url' => '',
			'menu' => array(
				'evaluate_targets' => array(
					'name' => 'บันทึกเป้าหมายโครงการ',
					'icon' => 'fa fa-calendar',
					'base_url' => 'evaluate_targets',
				),
				'evaluate_datas' => array(
					'name' => 'บันทึกรายงานประเมินผล',
					'icon' => 'fa fa-calendar',
					'base_url' => 'evaluate_datas',
				),
				'evaluate_five_years' => array(
					'name' => 'บันทึกรายงานโครงการ 5 ปี',
					'icon' => 'fa fa-calendar',
					'base_url' => 'evaluate_five_years',
				),
				// 'report_target' => array(
				// 	'name' => 'รายงานเป้าหมายโครงการ',
				// 	'icon' => 'fa fa-file-text-o',
				// 	'base_url' => 'report_targets',
				// ),
				'report_evaluates' => array(
					'name' => 'รายงานประเมินผล',
					'icon' => 'fa fa-file-text-o',
					'base_url' => 'report_evaluates',
				),
				'report_five_years' => array(
					'name' => 'รายงานโครงการ 5 ปี',
					'icon' => 'fa fa-file-text-o',
					'base_url' => 'report_five_years',
				),
				// 'report_criteria' => array(
				// 	'name' => 'รายงานเป้าหมายการดำเนินงานตามตัวชี้วัด',
				// 	'icon' => 'fa fa-file-text-o',
				// 	'base_url' => 'report_criteria',
				// )
				// 'evaluate_five_years' => array(
				// 	'name' => 'บันทึกรายงานโครงการระยะ 5 ปี',
				// 	'icon' => 'fa fa-calendar',
				// 	'base_url' => 'evaluate_five_years',
				// ),
			),
		),
		// array(
		// 	'name' => 'รายงาน',
		// 	'icon' => 'fa fa-file-text-o',
		// 	'base_url' => '',
		// 	'menu' => array(
		// 		'reports' => array(
		// 			'name' => 'รายงานการประเมินองค์กรรายปี',
		// 			'icon' => 'fa fa-file-text-o',
		// 			'base_url' => 'report_assessments',
		// 		),
		// 		'report_target' => array(
		// 			'name' => 'รายงานเป้าหมายโครงการ',
		// 			'icon' => 'fa fa-file-text-o',
		// 			'base_url' => 'report_targets',
		// 		),
		// 		'report_result' => array(
		// 			'name' => 'รายงานประเมินผล',
		// 			'icon' => 'fa fa-file-text-o',
		// 			'base_url' => 'report_evaluates',
		// 		),
		// 		'report_total' => array(
		// 			'name' => 'รายงานโครงการ 5 ปี',
		// 			'icon' => 'fa fa-file-text-o',
		// 			'base_url' => 'report_five_years',
		// 		),
		// 		'report_criteria' => array(
		// 			'name' => 'รายงานเป้าหมายการดำเนินงานตามตัวชี้วัด',
		// 			'icon' => 'fa fa-file-text-o',
		// 			'base_url' => 'report_criteria',
		// 		)
		// 	),
		// ),
		array(
			'name' => 'ตั้งค่าระบบ',
			'icon' => 'fa fa-cog',
			'base_url' => '',
			'menu' => array(
				'units' => array(
					'name' => 'หน่วยวัดเกณฑ์การประเมิน',
					'icon' => 'fa fa-cog',
					'base_url' => 'units',
				),
				'config_variables' => array(
					'name' => 'ตัวแปรจากระบบ',
					'icon' => 'fa fa-cog',
					'base_url' => 'config_variables',
				),
			)
			// 	'agenda_defaults' => array(
			// 		'name' => 'ค่าเริ่มต้นวาระการประชุม',
			// 		'icon' => 'fa fa-cog',
			// 		'base_url' => 'agenda_defaults',
			// 	),
			// ),
		),
	)
));

define('DEFAULT_AGENDAS', json_encode(// ค่าตั้งต้นวาระการประชุม
	array(
		array(
			'name' => 'แจ้งเพื่อทราบ',
			'story' => 'แจ้งเพื่อทราบ',
			'detail' => 'แจ้งเพื่อทราบ',
		),
		array(
			'name' => 'การรับรองรายงานการประชุม ',
			'story' => 'การรับรองรายงานการประชุม',
			'detail' => 'การรับรองรายงานการประชุม',
		),
		array(
			'name' => 'สืบเนื่อง',
			'story' => 'สืบเนื่อง',
			'detail' => 'สืบเนื่อง',
		),
		array(
			'name' => 'เพื่อพิจารณา',
			'story' => 'เพื่อพิจารณา',
			'detail' => 'เพื่อพิจารณา',
		),
		array(
			'name' => 'อื่นๆ',
			'story' => 'อื่นๆ',
			'detail' => 'อื่นๆ',
		),
	)
));
