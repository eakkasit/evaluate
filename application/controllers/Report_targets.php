<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports_assessments extends CI_Controller
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
		redirect(base_url("reports_assessments/dashboard_reports_assessments"));
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

	public function dashboard_reports_assessments()
	{

		$data['content_data'] = array(

		);

		$data['content_view'] = 'pages/dashboard_reports_assessment';
		$this->load->view($this->theme, $data);
	}

	public function view_reports_assessment($id = null)
	{
		$data['content_data'] = array(

		);
		$data['content_view'] = 'pages/view_reports_assessment';
		$this->load->view($this->theme, $data);
	}

	public function new_reports_assessment($id = null)
	{
		$data['content_data'] = array(
			
		);
		$data['content_view'] = 'pages/form_reports_assessment';
		$this->load->view($this->theme, $data);
	}

	public function edit_reports_assessment($id = null)
	{
		$data['content_data'] = array(

		);
		$data['content_view'] = 'pages/form_reports_assessment';
		$this->load->view($this->theme, $data);
	}



	public function delete_reports_assessment($id = null)
	{
		redirect(base_url("reports_assessments/dashboard_reports_assessments"));
		exit;
	}


}
