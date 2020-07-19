<?php
$config['protocol'] = 'sendmail';
$config['mailpath'] = '/usr/sbin/sendmail';
$config['charset'] = 'iso-8859-1';
$config['wordwrap'] = true;

/*$config['sendmail_smtp'] = array(
	'protocol' => 'smtp', // you can use 'mail' instead of 'sendmail or smtp'
	'smtp_host' => 'smtp.googlemail.com',// you can use 'smtp.googlemail.com' or 'smtp.gmail.com' instead of 'ssl://smtp.googlemail.com'
	'smtp_user' => '*****', // client email gmail id
	'smtp_pass' => '*****', // client password
	'smtp_port' => 465,
	'smtp_timeout' => 5,
	'smtp_crypto' => 'ssl',
	'mailtype' => 'html',
	'charset' => 'iso-8859-1',
	'newline' => '\r\n',
	'wordwrap' => true,
	'validate' => false,
);*/

$config['sender_email'] = 'noreply@jarvittechnology.co.th';
$config['sender_name'] = 'ระบบบริหารจัดการข้อมูลดิจิทัล';
