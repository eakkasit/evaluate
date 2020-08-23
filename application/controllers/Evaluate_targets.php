<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Evaluate_targets extends CI_Controller
{
	private $theme = 'default';

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session', 'pagination', 'form_validation'));
		$this->load->model(array('Commons_model', 'TargetProfiles_model','Targets_model','Activities_model'));
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

	public function dashboard_evaluate_targets()
	{

		$data['content_data'] = array(
			'datas' => $this->TargetProfiles_model->getTargetProfiles()
		);

		$data['content_view'] = 'pages/dashboard_evaluate_target';
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

	public function new_evaluate_target($id = null)
	{
		$data['content_data'] = array(
			'year_list' => $this->Commons_model->getYearList(),
			'data' => array()
		);
		$data['content_view'] = 'pages/form_evaluate_target';
		$this->load->view($this->theme, $data);
	}

	public function edit_evaluate_target($id = null)
	{
		$data['content_data'] = array(
			'year_list' => $this->Commons_model->getYearList(),
			'data' => $this->TargetProfiles_model->getTargetProfiles(array('id'=>$id))[0],
		);
		$data['content_view'] = 'pages/form_evaluate_target';
		$this->load->view($this->theme, $data);
	}

	public function validate()
	{
		$this->form_validation->set_rules('name', 'ชื่อ', 'required|trim');
		$this->form_validation->set_rules('year_start', 'ปีเริ่มต้น', 'required|trim');
		$this->form_validation->set_rules('year_end', 'ปีที่สิ้นสุด', 'required|trim');
		$this->form_validation->set_message('required', 'กรุณาระบุ {field}');

		return $this->form_validation->run();
	}

	public function save($profile_id = null)
	{
		$error_page = false;
		$upload_msg = '';
		$action = 'create';
		if ($profile_id != null && $profile_id != '') {
			$action = 'update';
		}

		if ($this->validate()) {
			$data = array();
			foreach ($_POST as $key => $value) {
				$data[$key] = $this->input->post($key);
			}

			if ($action == 'create') {
				$profile_id = $this->TargetProfiles_model->insertTargetProfiles($data);
				redirect(base_url("evaluate_targets/dashboard_evaluate_targets"));
				exit;
			} else {
				$this->TargetProfiles_model->updateTargetProfiles($profile_id, $data);
				redirect(base_url("evaluate_targets/dashboard_evaluate_targets"));
				exit;
			}
		} else {
			$error_page = true;
		}

		if ($error_page) {
			$data['content_data'] = array(
				'status_list'=> $this->Commons_model->getActiveList(),
				'year_list' => $this->Commons_model->getYearList(),
				'data' => (object)array(
					'id' => $profile_id,
					'name' => $this->input->post('name'),
					'year_start' => $this->input->post('year_start'),
					'year_end' => $this->input->post('year_end'),
					'detail' => $this->input->post('detail')
				)
			);
			$data['content_view'] = 'pages/form_evaluate_target';
			$this->load->view($this->theme, $data);
		}
	}

	public function delete_evaluate_target($id = null)
	{
		$this->TargetProfiles_model->deleteTargetProfiles($id);
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
