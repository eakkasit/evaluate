<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Evaluate_datas extends CI_Controller
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
		redirect(base_url("evaluate_datas/dashboard_evaluate_datas"));
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

	public function dashboard_evaluate_datas()
	{
		$cond = $this->search_form(array('project_name'));
		$config_pager = $this->config->item('pager');
		$config_pager['base_url'] = base_url("evaluate_datas/dashboard_evaluate_datas");
		$count_rows = $this->Activities_model->countActivities($cond);
		$config_pager['total_rows'] = $count_rows;
		$this->pagination->initialize($config_pager);
		$page = 0;
		if (isset($_GET['per_page'])) $page = $_GET['per_page'];
		$data['content_data'] = array(
			'datas'=>$this->Activities_model->getActivities($cond, array('year'=>'DESC'), $config_pager['per_page'], $page),

			'pages' => $this->pagination->create_links(),
			'count_rows' => $count_rows,
		);

		$data['content_view'] = 'pages/dashboard_evaluate_data';
		// echo "<pre>";
		// print_r($data);
		// die();
		$this->load->view($this->theme, $data);
	}

	public function dashboard_evaluate_data_detail($id='')
	{
		// $cond = $this->search_form(array('project_name'));
		// $config_pager = $this->config->item('pager');
		// $config_pager['base_url'] = base_url("evaluate_datas/dashboard_evaluate_datas");
		// $count_rows = $this->Activities_model->countActivities($cond);
		// $config_pager['total_rows'] = $count_rows;
		// $this->pagination->initialize($config_pager);
		// $page = 0;
		// if (isset($_GET['per_page'])) $page = $_GET['per_page'];
		$result = array();
		$result_data = $this->CriteriaDatas_model->getResult(array('project_id'=>$id));
		if(isset($result_data) && !empty($result_data)){
			foreach ($result_data as $key => $value) {
				$result[$value->project_id][$value->task_id][$value->year] = $value->result;
			}
		}
		$data['content_data'] = array(
			'datas'=>$this->Activities_model->getTasks(array('project_id'=>$id)),
			'project_id'=>$id,
			'result' => $result
		);

		$data['content_view'] = 'pages/dashboard_evaluate_data_detail';
		$this->load->view($this->theme, $data);
	}

	public function view_evaluate_data($id = null)
	{
		$data['content_data'] = array(

		);
		$data['content_view'] = 'pages/view_evaluate_data';
		$this->load->view($this->theme, $data);
	}

	public function new_evaluate_data($id = null)
	{
		$data['content_data'] = array();
		$data['content_view'] = 'pages/form_evaluate_data';
		$this->load->view($this->theme, $data);
	}

	public function edit_evaluate_data($project_id,$id)
	{
		$result = array();
		$result_data = $this->CriteriaDatas_model->getResult(array('task_id'=>$id,'project_id'=>$project_id));
		if(isset($result_data[0])){
			$result = $result_data[0];
		}

		$data['content_data'] = array(
			'data' => $this->Activities_model->getTasks(array('task_id'=>$id,'project_id'=>$project_id))[0],
			'project_id' => $project_id,
			'id' => $id,
			'result' => $result
 		);
		$data['content_view'] = 'pages/form_evaluate_data';
		$this->load->view($this->theme, $data);
	}


		public function validate()
		{
			$this->form_validation->set_rules('result', 'ผลการประเมิน', 'required|trim');
			$this->form_validation->set_message('required', 'กรุณาระบุ {field}');

			return $this->form_validation->run();
		}

		public function save($result_id = null)
		{
			$error_page = false;
			$action = 'create';
			if ($result_id != null && $result_id != '') {
				$action = 'update';
			}
			$project_id = $_POST['project_id'];
			$id = $_POST['task_id'];
			if ($this->validate()) {
				$data = array();
				foreach ($_POST as $key => $value) {
					$data[$key] = $this->input->post($key);
				}
				if ($action == 'create') {
					$data_result_id = $this->CriteriaDatas_model->insertResult($data);
					redirect(base_url("evaluate_datas/dashboard_evaluate_data_detail/".$project_id));
					exit;
				}else {
					$data_result_id = $this->CriteriaDatas_model->updateResult($result_id,$data);
					redirect(base_url("evaluate_datas/dashboard_evaluate_data_detail/".$project_id));
					exit;
				}
			} else {
				$error_page = true;
			}

			if ($error_page) {
				$data['content_data'] = array(
					'data'=>$this->Activities_model->getTasks(array('task_id'=>$id,'project_id'=>$project_id))[0],
					'project_id'=>$project_id,
					'id'=> $id
				);
				$data['content_view'] = 'pages/form_evaluate_data';
				$this->load->view($this->theme, $data);
			}
		}


	public function delete_evaluate_data($id = null)
	{
		redirect(base_url("evaluate_datas/dashboard_evaluate_datas"));
		exit;
	}


}
