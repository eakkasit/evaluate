<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Criteria_themes extends CI_Controller
{
	private $theme = 'default';

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session', 'pagination', 'form_validation'));
		// $this->load->model(array('Commons_model', 'Logs_model', 'Configs_model', 'Users_model'));
		$this->load->helper(array('Commons_helper', 'form', 'url'));

		if ($this->session->userdata('user_id') == '') {
			redirect(base_url("authentications"));
			exit;
		}
	}

	public function index()
	{
		redirect(base_url("criteria_theme/dashboard_criteria"));
		exit;
	}

	public function search_form($fields = array())
	{
		$cond = array();
		if ($this->input->post('form_search_element') && !empty($fields)) {
			$search_text = explode(' ', $this->input->post('form_search_element')['text']);
			$cond_str = "( ";
			foreach ($search_text as $text) {
				$text = trim($text);
				if ($text != '') {
					foreach ($fields as $field) {
						$cond_str .= "{$field} LIKE '%{$text}%' OR ";
					}
				}
			}
			$cond = array(substr($cond_str, 0, -3) . " )");
		}
		return $cond;
	}

	public function dashboard_criteria_themes()
	{

		$data['content_data'] = array(

		);

		$data['content_view'] = 'pages/dashboard_criteria_theme';
		$this->load->view($this->theme, $data);
	}

	public function view_criteria_theme($user_id = null)
	{
		$data['content_data'] = array(

		);
		$data['content_view'] = 'pages/view_criteria_theme';
		$this->load->view($this->theme, $data);
	}

	public function new_criteria_theme($user_id = null)
	{
		$data['content_data'] = array(

		);
		$data['content_view'] = 'pages/form_criteria_theme';
		$this->load->view($this->theme, $data);
	}

	public function edit_criteria_theme($user_id = null)
	{
		$data['content_data'] = array(

		);
		$data['content_view'] = 'pages/form_criteria_theme';
		$this->load->view($this->theme, $data);
	}



	public function delete_user($user_id = null)
	{
		// $this->Users_model->deleteUser($user_id);
		redirect(base_url("criteria_theme/dashboard_criteria_themes"));
		exit;
	}


}
