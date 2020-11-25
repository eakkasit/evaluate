<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_criteria extends CI_Controller
{
	private $theme = 'default';

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session', 'pagination', 'form_validation'));
		$this->load->model(array('Commons_model','Structure_model','KpiTree_model','Formula_model','Kpi_model'));
		$this->load->helper(array('Commons_helper', 'form', 'url'));

		if ($this->session->userdata('user_id') == '') {
			redirect(base_url("authentications"));
			exit;
		}
	}

	public function index()
	{
		redirect(base_url("report_criteria/dashboard_report_criteria"));
		exit;
	}

	public function search_form($fields = array())
	{
		$cond = array();
		if ($this->input->get('search') && !empty($fields)) {
			$search_text = explode(' ', $this->input->get('search'));
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

	public function dashboard_report_criteria()
	{

		$cond = $this->search_form(array('profile_name', 'year', 'detail', 'status'));

		$config_pager = $this->config->item('pager');
		$config_pager['base_url'] = base_url("criteria/dashboard_criteria");
		$count_rows = $this->Structure_model->countStructure($cond);
		$config_pager['total_rows'] = $count_rows;
		$this->pagination->initialize($config_pager);
		$page = 0;
		if (isset($_GET['per_page'])) $page = $_GET['per_page'];

		$data['content_data'] = array(
			'search_url' => base_url("criteria/dashboard_criteria"),
			'status_list' => $this->Commons_model->getActiveList(),
			'datas' => $this->Structure_model->getStructure($cond, array(), $config_pager['per_page'], $page),
			'pages' => $this->pagination->create_links(),
			'count_rows' => $count_rows,
		);


		// $data['content_view'] = 'pages/dashboard_report_assessment';
		$this->load->view($this->theme, $data);
	}

	public function view_reports_assessment($id = null)
	{
		$data['content_data'] = array(
			'structure_id' => $id,
			'tree' => $this->KpiTree_model->getKpiTree(array('structure_id' => $id,'tree_parent' => 0)),
			'tree_db' => $this->KpiTree_model,
			'kpi_db' => $this->Kpi_model,
			'formula_db' => $this->Formula_model

		);
		// echo "<pre>";
		// print_r($data);
		// die();
		$data['content_view'] = 'pages/view_reports_assessment';
		$this->load->view($this->theme, $data);
	}

	public function FunctionName($value='')
	{
		// code...
	}


}
