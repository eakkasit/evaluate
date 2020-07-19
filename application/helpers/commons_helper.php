<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('date_thai')) {
	function date_thai($str_date = '', $full_datetime = true, $full_month = true)
	{
		$date = strtotime($str_date);
		$str_year = date('Y', $date) + 543;
		$str_month = date('n', $date);
		$str_day = date('j', $date);
		$str_hour = date('H', $date);
		$str_minute = date('i', $date);
		$str_seconds = date('s', $date);
		if ($full_month == true) {
			$arr_month = Array('', 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม');
		} else {
			$arr_month = Array('', 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.');
		}
		$str_month_thai = $arr_month[$str_month];
		if ($full_datetime == true) {
			return "{$str_day} {$str_month_thai} {$str_year} เวลา {$str_hour}:{$str_minute} น.";
		} else {
			return "{$str_day} {$str_month_thai} {$str_year}";
		}
	}
}

if (!function_exists('time_thai')) {
	function time_thai($str_time = '', $label = true)
	{
		$time = explode(':', $str_time);
		if ($label == true) {
			return "{$time[0]}:{$time[1]} น.";
		} else {
			return "{$time[0]}:{$time[1]}";
		}
	}
}

if (!function_exists('phone_number')) {
	function phone_number($str_phone = '')
	{
		$str_phone = str_replace(array('-', ' ', '.'), '', $str_phone);
		return substr($str_phone, 0, 3) . '-' . substr($str_phone, 3);
	}
}

if (!function_exists('class_file_type')) {
	function class_file_type($file_name = '')
	{
		$file_ext = explode('.', $file_name);
		$file_ext = $file_ext[count($file_ext) - 1];
		if (in_array($file_ext, array('jpg', 'png'))) {
			return 'success';
		} else if (in_array($file_ext, array('pdf'))) {
			return 'danger';
		} else if (in_array($file_ext, array('doc', 'docx', 'xlsx'))) {
			return 'primary';
		} else if (in_array($file_ext, array('csv'))) {
			return 'warning';
		} else {
			return 'default';
		}
	}
}

if (!function_exists('thai_number')) {
	function thai_number($str = '')
	{
		$ar_num = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
		$th_num = array('๐', '๑', '๒', '๓', '๔', '๕', '๖', '๗', '๘', '๙');
		return str_replace($ar_num, $th_num, $str);

	}
}

?>
