<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Evaluate_targets extends CI_Controller
{
	private $theme = 'default';

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session', 'pagination', 'form_validation'));
		$this->load->model(array('Commons_model', 'TargetProfiles_model','Targets_model','Activities_model','CriteriaDatas_model'));
		$this->load->helper(array('Commons_helper', 'form', 'url'));

		if ($this->session->userdata('user_id') == '') {
			redirect(base_url("authentications"));
			exit;
		}
	}

	public function index()
	{
		redirect(base_url("evaluate_targets/dashboard_evaluate_targets"));
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

	public function dashboard_evaluate_targets()
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

		$data['content_view'] = 'pages/dashboard_evaluate_target';
		$this->load->view($this->theme, $data);
	}

	public function dashboard_evaluate_target_detail($project_id='')
	{
		$data['content_data'] = array(
			'project_data'=>$this->Activities_model->getActivities(array('id'=>$project_id)),
			'datas'=>$this->CriteriaDatas_model->getTarget(array('project_id'=>$project_id), array('year'=>'ASC')),
			'year_list' => $this->Commons_model->getYearList(),
			'project_id' => $project_id
		);
		$data['content_view'] = 'pages/dashboard_evaluate_target_detail';
		$this->load->view($this->theme, $data);
	}

	public function view_evaluate_target($id = null)
	{
		$data_temp = array();
		$con = array();
		$year_show = 1;
		$year_start = date('Y')+543;
		$year_end = date('Y')+543;
		$year_check = $this->TargetProfiles_model->getTargetProfiles(array('id'=>$id))[0];
		if($year_check){
			$year_show = $year_check->year_end - $year_check->year_start;
			if ($year_show <= 0) {
				$year_show = 1;
			}

			$target = $this->Targets_model->getTargets(array('profile_id'=>$id));
			if($target){
				foreach ($target as $key => $value) {
					$data_temp[$value->project_id][$value->year] = $data->target;
				}
			}
			$year_start = $year_check->year_start;
			$year_end = $year_check->year_end;
		}
		if($year_check->year_start == $year_check->year_end){
			$con = array("year" => $year_check->year_start);

		}else{
			$con = array("year BETWEEN '$year_check->year_start' and '$year_check->year_end'");
		}
		$data['content_data'] = array(
			'project_list' => $this->Activities_model->getActivities($con,array('year'=>'DESC','id'=>'ASC')),
			'year_list' => $this->Commons_model->getYearList(),
			'year_show' => $year_show,
			'data' => $data_temp,
			'profile_id' => $id,
			'year_start' => $year_start,
			'year_end' => $year_end,
		);
		$data['content_view'] = 'pages/view_evaluate_target';
		$this->load->view($this->theme, $data);
	}

	public function new_evaluate_target($project_id)
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
			'year_list' => $year_list,
			'data' => $project_data,
			'project_id' => $project_id
		);
		$data['content_view'] = 'pages/form_evaluate_target';
		$this->load->view($this->theme, $data);
	}

	public function edit_evaluate_target($project_id,$id = null)
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
			'year_list' => $year_list,
			'data' => $project_data,
			'project_id' => $project_id,
			'result' => $this->CriteriaDatas_model->getTarget(array('id'=>$id))[0]
		);
		$data['content_view'] = 'pages/form_evaluate_target';
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
		$this->form_validation->set_rules('target', 'เป้าหมายโครงการ', 'required|trim');
		$this->form_validation->set_message('required', 'กรุณาระบุ {field}');

		return $this->form_validation->run();
	}

	function _check_year($year,$project_id) {
		$data_result_id = $this->CriteriaDatas_model->getTarget(array('year'=>$year,'project_id'=>$project_id));
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
		$upload_msg = '';
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

		if ($this->validate($result_id)) {
			$data = array();
			foreach ($_POST as $key => $value) {
				$data[$key] = $this->input->post($key);
			}

			if ($action == 'create') {
				$result_id = $this->CriteriaDatas_model->insertTarget($data);
				redirect(base_url("evaluate_targets/dashboard_evaluate_target_detail/{$project_id}"));
				exit;
			} else {
				$this->CriteriaDatas_model->updateTarget($result_id, $data);
				redirect(base_url("evaluate_targets/dashboard_evaluate_target_detail/{$project_id}"));
				exit;
			}
		} else {
			$error_page = true;
		}

		if ($error_page) {
			$data['content_data'] = array(
				// 'status_list'=> $this->Commons_model->getActiveList(),
				'year_list' => $year_list,
				'data' => $project_data,
				'result' => (object)array(
					'id' => $result_id,
					'project_id' => $this->input->post('project_id'),
					'year' => $this->input->post('year'),
					'target' => $this->input->post('target')
				),
				'project_id' => $project_id
			);
			$data['content_view'] = 'pages/form_evaluate_target';
			$this->load->view($this->theme, $data);
		}
	}

	public function delete_evaluate_target($id = null)
	{
		// $this->TargetProfiles_model->deleteTargetProfiles($id);
		$this->CriteriaDatas_model->deleteTarget($id);
		redirect(base_url("evaluate_targets/dashboard_evaluate_targets"));
		exit;
	}

	public function update_target($id = null)
	{
		$data_temp = array();
		$con = array();
		$year_show = 1;
		$year_start = date('Y')+543;
		$year_end = date('Y')+543;
		$year_check = $this->TargetProfiles_model->getTargetProfiles(array('id'=>$id))[0];
		if($year_check){
			$year_show = $year_check->year_end - $year_check->year_start;
			if ($year_show <= 0) {
				$year_show = 1;
			}

			$target = $this->Targets_model->getTargets(array('profile_id'=>$id));
			if($target){
				foreach ($target as $key => $value) {
					$data_temp[$value->project_id][$value->year] = $data->target;
				}
			}
			$year_start = $year_check->year_start;
			$year_end = $year_check->year_end;
		}
		if($year_check->year_start == $year_check->year_end){
			$con = array("year" => $year_check->year_start);

		}else{
			$con = array("year BETWEEN '$year_check->year_start' and '$year_check->year_end'");
		}
		// $this->Activities_model->select('*');
		// $this->Activities_model->from('project');
		// if($year_check->year_start == $year_check->year_end){
		// 	// $con = array("year" => $year_check->year_start);
		// 	$this->Activities_model->where('year'=>$year_check->year_start);
		//
		// }else{
		// 	$con = array("year" => "BETWEEN '$year_check->year_start' and '$year_check->year_end'");
		// }
		$data['content_data'] = array(
			'project_list' => $this->Activities_model->getActivities($con,array('year'=>'DESC','id'=>'ASC')),
			'year_list' => $this->Commons_model->getYearList(),
			'year_show' => $year_show,
			'data' => $data_temp,
			'profile_id' => $id,
			'year_start' => $year_start,
			'year_end' => $year_end,
		);
		// echo "<pre>";
		// print_r($data);
		// die();
		$data['content_view'] = 'pages/form_target';
		$this->load->view($this->theme, $data);
	}

}
