<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authentications extends CI_Controller
{
	private $theme = 'default';

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session'));
		$this->load->model(array('Commons_model', 'Logs_model'));
		$this->load->helper();
	}

	public function index()
	{
		$content_text = "<h3 class='header'>ลงชื่อเข้าใช้ด้วยผู้ใช้</h3><ol>";
		$users = $this->db->select('*')->from('user')->get()->result();
		if (!empty($users)) {
			$prefix_list = $this->Commons_model->getPrefixList();
			$url = base_url("authentications/set_auth/");
			foreach ($users as $user) {
				$label = "{$prefix_list[$user->prename]} {$user->name}   {$user->surname}";
				if ($user->position_code != '') $label .= " ({$user->position_code})";
				$content_text .= "<li><a href='{$url}{$user->user_id}'>{$label}</a></li>";
			}
		}
		$content_text .= "</ul>";
		$data['content_text'] = $content_text;
		$this->load->view($this->theme, $data);
	}

	public function set_auth($user_id = 0)
	{
		$user = (array)$this->db->select('*')->from('user')->where('user_id', $user_id)->get()->result()[0];
		$this->session->set_userdata($user);
		$this->Logs_model->insertLog('login');
		redirect(base_url("criteria_themes/dashboard_criteria_themes"));
	}

	public function logout()
	{
		$this->Logs_model->insertLog('logout');
		$this->session->sess_destroy();
		redirect(base_url("authentications"));
	}
}
