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
		$this->load->helper(array('Commons_helper', 'form', 'url','text'));

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

		// if($this->input->post('year_start')){
		// 	// echo '<pre/> ';
		// 	// print_r($cond);
		// 	// die();
		// }
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
			'year_list' => $this->Commons_model->getYearList(),
			'pages' => $this->pagination->create_links(),
			'count_rows' => $count_rows,
		);

		$data['content_view'] = 'pages/dashboard_evaluate_data';
		$this->load->view($this->theme, $data);
	}

	public function dashboard_evaluate_data_detail($id='')
	{
		$result = array();
		$project_data = $this->Activities_model->getActivities(array('id'=>$id));
		$result_data = $this->CriteriaDatas_model->getResult(array('project_id'=>$id),array('year'=>'ASC'));
		$count_result = count($result_data);
		$year_list = array();
		$btn_add = true;
		$year_len = 0;
		if($project_data[0]->year_start == $project_data[0]->year_end){
			$year_len = 1;
		}else{
			$year_len = $project_data[0]->year_end - $project_data[0]->year_start;
			$year_len += 1;
		}
		if($count_result != 0 && $count_result >=  $year_len){
			$btn_add = false;
		}
		$data['content_data'] = array(
			// 'datas'=>$this->Activities_model->getTasks(array('project_id'=>$id)),
			'project_data' => $project_data,
			'project_id'=>$id,
			'datas' => $result_data,
			'btn_add' => $btn_add
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

	public function new_evaluate_data($project_id)
	{
		$project_data = $this->Activities_model->getActivities(array('id'=>$project_id))[0];
		$year_list = array();
		if($project_data->year_start == $project_data->year_end){
			$year_list[$project_data->year_start] = $project_data->year_start+543;
		}else{
			$year_len = $project_data->year_end - $project_data->year_start;
			for ($i=0; $i <= $year_len; $i++) {
				$year_list[$project_data->year_start+$i] = $project_data->year_start+$i+543;
			}
		}
		$data['content_data'] = array(
			'project_id' => $project_id,
			'data' => $project_data,
			'year_list' => $year_list
		);
		$data['content_view'] = 'pages/form_evaluate_data';
		$this->load->view($this->theme, $data);
	}

	public function edit_evaluate_data($project_id,$id)
	{
		// $result = array();
		// $result_data = $this->CriteriaDatas_model->getResult(array('id'=>$id));
		$project_data = $this->Activities_model->getActivities(array('id'=>$project_id))[0];
		$year_list = array();
		if($project_data->year_start == $project_data->year_end){
			$year_list[$project_data->year_start] = $project_data->year_start+543;
		}else{
			$year_len = $project_data->year_end - $project_data->year_start;
			for ($i=0; $i <= $year_len; $i++) {
				$year_list[$project_data->year_start+$i] = $project_data->year_start+$i+543;
			}
		}

		$data['content_data'] = array(
			'data' => $project_data,
			'project_id' => $project_id,
			'id' => $id,
			'result' => $this->CriteriaDatas_model->getResult(array('id'=>$id))[0],
			'year_list' => $year_list
 		);
		$data['content_view'] = 'pages/form_evaluate_data';
		$this->load->view($this->theme, $data);
	}

		public function validate($result_id)
		{
			$action = 'create';
			if ($result_id != null && $result_id != '') {
				$action = 'update';
			}
			if($action == 'create'){
					$this->form_validation->set_rules('year', 'ปีงบประมาณ', 'callback__check_year['.$this->input->post('project_id').']');
			}
			$this->form_validation->set_rules('assessment_results', 'ผลการประเมิน', 'required|trim');
			$this->form_validation->set_message('required', 'กรุณาระบุ {field}');

			return $this->form_validation->run();
		}

		function _check_year($year,$project_id) {
			$data_result_id = $this->CriteriaDatas_model->getResult(array('year'=>$year,'project_id'=>$project_id));
			if(isset($data_result_id[0])){
				$this->form_validation->set_message('_check_year', 'มีข้อมูลปีงบประมาณ {field} แล้ว');
				return FALSE;
			}else{
				return TRUE;
			}
		}

		public function save($result_id = null)
		{
			$error_page = false;
			$action = 'create';
			$project_id = $_POST['project_id'];
			$project_data = $this->Activities_model->getActivities(array('id'=>$project_id))[0];
			$year_list = array();
			if($project_data->year_start == $project_data->year_end){
				$year_list[$project_data->year_start] = $project_data->year_start+543;
			}else{
				$year_len = $project_data->year_end - $project_data->year_start;
				for ($i=0; $i <= $year_len; $i++) {
					$year_list[$project_data->year_start+$i] = $project_data->year_start+$i+543;
				}
			}
			if ($result_id != null && $result_id != '') {
				$action = 'update';
			}
			// echo "$action";
			// die();
			// $id = $_POST['id'];
			if ($this->validate($result_id)) {
				$data = array();
				foreach ($_POST as $key => $value) {
					$data[$key] = $this->input->post($key);
				}
				if ($action == 'create') {
					// $data_result_id = $this->CriteriaDatas_model->getResult(array('year'=>$data['year'],'project_id'=>$project_id));
					// if(isset($data_result_id[0])){
					// 	$this->form_validation->set_message('required', 'กรุณาระบุ {field}');
					// 	$error_page = true;
					// }else{
						$data_result_id = $this->CriteriaDatas_model->insertResult($data);
						redirect(base_url("evaluate_datas/dashboard_evaluate_data_detail/".$project_id));
						exit;
					// }

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
					'data' =>  $project_data,
					'project_id' => $project_id,
					'id' => $result_id,
					'result' => (object)array(
						'year' =>  $this->input->post('year'),
						'project_result' =>  $this->input->post('project_result'),
						'product' =>  $this->input->post('product'),
						'result' =>  $this->input->post('result'),
						'id' =>  $result_id,
						'assessment_results' =>  $this->input->post('assessment_results'),
					),
					'year_list' => $year_list
				);
				$data['content_view'] = 'pages/form_evaluate_data';
				$this->load->view($this->theme, $data);
			}
		}


	public function delete_evaluate_data($project_id,$id = null)
	{
		$this->CriteriaDatas_model->deleteResult($id);
		redirect(base_url("evaluate_datas/dashboard_evaluate_data_detail/{$project_id}"));
		exit;
	}


}
