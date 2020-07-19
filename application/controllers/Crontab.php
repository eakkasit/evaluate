<?php
// required is 1 day crontab
defined('BASEPATH') OR exit('No direct script access allowed');

class Crontab extends CI_Controller
{
	private $theme = 'default';
	private $system_configs = array();
	private $backup_path = APPPATH . '../db_backup/';// need to 0777

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('Configs_model', 'Logs_model', 'Users_model'));

		$this->system_configs = array(
			'backup_monthly' => $this->Configs_model->getConfigs(array('config_id' => 2))[0],
			'1year_user' => $this->Configs_model->getConfigs(array('config_id' => 3))[0],
		);
	}

	public function index()
	{
		echo '<h3>START CRONTAB.</h3>';
		if (isset($this->system_configs['backup_monthly']) && strtolower($this->system_configs['backup_monthly']->config_status) == 'active') {
			if (date('j') == 1) {
				$this->db_backup();
			}
			echo '<br>backup monthly success.';
		}
		if (isset($this->system_configs['1year_user']) && strtolower($this->system_configs['1year_user']->config_status) == 'active') {
			$users = $this->Logs_model->getUserDateLogin();
			if (!empty($users)) {
				foreach ($users as $user) {
					$this->Users_model->deleteUser($user->user_id);
					echo '<br>invoke 1year user : ' . $user->user_id;
				}
			}
		}
		echo '<br>success.';
		exit;
	}

	private function db_backup()
	{
		$DBUSER = $this->db->username;
		$DBPASSWD = $this->db->password;
		$DATABASE = $this->db->database;

		/*$filename = $DATABASE . '_' . date('Y-m-d_H-i-s') . '.sql.gz';
		$mime = 'application/x-gzip';

		header('Content-Type: ' . $mime);
		header('Content-Disposition: attachment; filename="' . $filename . '"');

		 $cmd = "mysqldump -u {$DBUSER} --password={$DBPASSWD} {$DATABASE} | gzip --best";
		$cmd = "mysqldump -u {$DBUSER} --password={$DBPASSWD} --no-create-info --complete-insert {$DATABASE} | gzip --best";
		$cmd = "mysqldump -u {$DBUSER} -p'{$DBPASSWD}' {$DATABASE} > {$filepath}{$filename}";

		passthru($cmd);

		exit();*/

		$filename = $DATABASE . '_' . date('Y-m-d_H-i-s') . '.sql';
		$filepath = $this->backup_path;
		if (!file_exists($filepath)) {
			mkdir($filepath, 0777, true);
		}

		$return_var = null;
		$output = null;
		$command = "mysqldump -u{$DBUSER} -p'{$DBPASSWD}' {$DATABASE} > {$filepath}{$filename}";
		exec($command, $output, $return_var);

		return $return_var;
	}
}
