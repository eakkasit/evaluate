<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Evaluate_five_years extends CI_Controller
{
	private $theme = 'default';

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session', 'pagination', 'form_validation'));
		$this->load->model(array('Commons_model', 'Activities_model','CriteriaDatas_model'));
		$this->load->helper(array('Commons_helper', 'form', 'url'));

		if ($this->session->userdata('user_id') == '') {
			redirect(base_url("authentications"));
			exit;
		}
	}

	public function index()
	{
		redirect(base_url("evaluate_five_years/dashboard_evaluate_five_years"));
		exit;
	}

	public function search_form($fields = array())
	{
		$cond = array();
		if ($this->input->post('form_search_element')['text'] && !empty($fields)) {
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

	public function dashboard_evaluate_five_years()
	{
		$cond = $this->search_form(array('project_name'));
		$config_pager = $this->config->item('pager');
		$config_pager['base_url'] = base_url("evaluate_datas/dashboard_evaluate_datas");
		$search_year_start = date('Y');
		$search_year_end = date('Y')+4;
		if(isset($_POST['search_year_start']) &&  isset($_POST['search_year_end'])){
			$search_year_start = $_POST['search_year_start'];
			$search_year_end = $_POST['search_year_end'];
			$con = "year BETWEEN '{$search_year_start}' AND '{$search_year_end}'";
			array_push($cond,$con);

		}else if(isset($_POST['search_year_start'])){
			$search_year_start = $_POST['search_year_start'];
			$con = "year >= '{$search_year_start}'";
			array_push($cond,$con);
		}else if(isset($_POST['search_year_end'])){
			$search_year_end = $_POST['search_year_end'];
			$con = "year <= '{$search_year_end}'";
			array_push($cond,$con);
		}else{

		}
		$count_rows = $this->Activities_model->countActivities($cond);
		$config_pager['total_rows'] = $count_rows;
		$this->pagination->initialize($config_pager);
		$page = 0;
		if (isset($_GET['per_page'])) $page = $_GET['per_page'];

		$target_data_temp = $this->CriteriaDatas_model->getTarget();
		$target_data = array();
		if(isset($target_data_temp) && !empty($target_data_temp)){
			foreach ($target_data_temp as $key => $value) {
				$target_data[$value->project_id][$value->year] = $value->target;
			}
		}
		$result_data_temp = $this->CriteriaDatas_model->getResult();
		$result_data = array();
		if(isset($result_data_temp) && !empty($result_data_temp)){
			foreach ($result_data_temp as $key => $value) {
				$result_data[$value->project_id][$value->year] = $value->assessment_results;
			}
		}

		$data['content_data'] = array(
			'datas'=>$this->Activities_model->getActivities($cond, array('year'=>'DESC')),
			'pages' => $this->pagination->create_links(),
			'count_rows' => $count_rows,
			'year_list' => $this->Commons_model->getYearList(),
			'search_year_start' => $search_year_start,
			'search_year_end' => $search_year_end,
			'target_data' => $target_data,
			'result_data' => $result_data
		);

		$data['content_view'] = 'pages/dashboard_evaluate_five_year';
		$this->load->view($this->theme, $data);
	}

	public function view_evaluate_five_year($id = null)
	{
		$data['content_data'] = array(

		);
		$data['content_view'] = 'pages/view_evaluate_five_year';
		$this->load->view($this->theme, $data);
	}

	public function new_evaluate_five_year($id = null)
	{
		$data['content_data'] = array(
		);
		$data['content_view'] = 'pages/form_evaluate_five_year';
		$this->load->view($this->theme, $data);
	}

	public function edit_evaluate_five_year($id = null)
	{
		$data['content_data'] = array(

		);
		$data['content_view'] = 'pages/form_evaluate_five_year';
		$this->load->view($this->theme, $data);
	}



	public function delete_evaluate_five_year($id = null)
	{
		redirect(base_url("evaluate_five_years/dashboard_evaluate_five_years"));
		exit;
	}


}
