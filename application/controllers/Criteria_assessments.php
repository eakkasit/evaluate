<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Criteria_assessments extends CI_Controller
{
	private $theme = 'default';
	private $ajax_page = ['ajax_get_data','ajax_get_variable'];

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session', 'pagination', 'form_validation'));
		$this->load->model(array('Commons_model', 'CriteriaProfiles_model','Criterias_model','CriteriaVariables_model'));
		$this->load->helper(array('Commons_helper', 'form', 'url'));
		if(!in_array($this->uri->segment(2),$this->ajax_page)){
			if ($this->session->userdata('user_id') == '') {
				redirect(base_url("authentications"));
				exit;
			}
		}

	}

	public function index()
	{
		redirect(base_url("criteria_assessments/dashboard_criteria_assessments"));
		exit;
	}

	public function search_form($fields = array())
	{
		$cond = array();
		if ($this->input->post('form_search_element')['text'] != '' && !empty($fields)) {
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

	public function dashboard_criteria_assessments()
	{
		$cond = $this->search_form(array('profile_name', 'year', 'detail', 'status'));

		$config_pager = $this->config->item('pager');
		$config_pager['base_url'] = base_url("criteria_assessments/dashboard_criteria_assessments");
		$count_rows = $this->CriteriaProfiles_model->countCriteriaProfiles($cond);
		$config_pager['total_rows'] = $count_rows;
		$this->pagination->initialize($config_pager);
		$page = 0;
		if (isset($_GET['per_page'])) $page = $_GET['per_page'];

		$data['content_data'] = array(
			'search_url' => base_url("criteria_assessments/dashboard_criteria_assessments"),
			'status_list' => $this->Commons_model->getActiveList(),
			'datas' => $this->CriteriaProfiles_model->getCriteriaProfiles($cond, array(), $config_pager['per_page'], $page),
			'pages' => $this->pagination->create_links(),
			'count_rows' => $count_rows,
		);

		$data['content_view'] = 'pages/dashboard_criteria_assessment';
		$this->load->view($this->theme, $data);
	}

	public function view_criteria_assessment($id = null)
	{
		$data['content_data'] = array(
			'profile_id' => $id,
			'datas' => (object) $this->Criterias_model->getItemChild($id,0)
		);
		$data['content_view'] = 'pages/view_criteria_assessment';
		$this->load->view($this->theme, $data);
	}

	public function new_criteria_assessment($id = null)
	{
		$data['content_data'] = array(
			'profile_id' => $id,
			'datas' => $this->Criterias_model->getCriterias(array('profile_id'=>$id),array('id'=>'asc')),
			'variable_lists' => $this->CriteriaVariables_model->getCriteriaVariableLists()
		);
		$data['content_view'] = 'pages/form_criteria_assessment';
		$this->load->view($this->theme, $data);
	}

	public function edit_criteria_assessment($id = null)
	{
		$data['content_data'] = array(

		);
		$data['content_view'] = 'pages/form_criteria_assessment';
		$this->load->view($this->theme, $data);
	}

	public function validate()
	{
		$this->form_validation->set_rules('criteria_name', 'ชื่อตัวแปรเกณฑ์การประเมิน', 'required|trim');
		$this->form_validation->set_message('required', 'กรุณาระบุ {field}');

		return $this->form_validation->run();
	}

	public function save($criteria_id = null)
	{
		$error_page = false;
		$upload_msg = '';
		$action = 'create';
		if ($criteria_id != null && $criteria_id != '') {
			$action = 'update';
		}

		if ($this->validate()) {
			$data = array();
			foreach ($_POST as $key => $value) {
				$data[$key] = $this->input->post($key);
			}
			if($data['criteria_id'] != '' && $data['criteria_id'] != '0'){
					$action = 'update';
					$criteria_id = $data['criteria_id'];
			}
			if ($action == 'create') {
				try {
					$this->db->trans_start();
					$criteria_id = $this->Criterias_model->insertCriterias($data);
					$this->Criterias_model->deleteCriteriaNVariables($criteria_id);
					if(isset($data['variable_data'])){
						foreach ($data['variable_data'] as $key => $value) {
							$data_temp = array();
							foreach ($value as $key_temp => $value_temp) {
								if($key_temp == 'criteria_id'){
									$data_temp[$key_temp] = $criteria_id;
								}else{
									$data_temp[$key_temp] = $value_temp;
								}

							}
							$this->Criterias_model->insertCriteriaNVariables($data_temp);
						}
					}
					$this->db->trans_complete();
					if ($this->db->trans_status() === FALSE) {
							$this->db->trans_rollback();
							echo "false";
							exit();
					}
					else {
							$this->db->trans_commit();
							echo "true";
							exit();
					}
				} catch (Exception $e) {
					echo "false";
					exit();
				}
				redirect(base_url("criteria_assessments/dashboard_criteria_assessments"));
				exit;
			} else {
				try {
					$this->db->trans_start();
					$this->Criterias_model->updateCriterias($criteria_id, $data);
					$this->Criterias_model->deleteCriteriaNVariables($criteria_id);
					if(isset($data['variable_data'])){
						foreach ($data['variable_data'] as $key => $value) {
							$data_temp = array();
							foreach ($value as $key_temp => $value_temp) {
								$data_temp[$key_temp] = $value_temp;
							}
							$this->Criterias_model->insertCriteriaNVariables($data_temp);
						}
					}
					$this->db->trans_complete();
					if ($this->db->trans_status() === FALSE) {
					    $this->db->trans_rollback();
							echo "false";
							exit();
					}
					else {
					    $this->db->trans_commit();
							echo "true";
							exit();
					}
				} catch (Exception $e) {
					echo "false";
					exit();
				}


				redirect(base_url("criteria_assessments/dashboard_criteria_assessments"));
				exit;
			}
		} else {
			$error_page = true;
		}

		if ($error_page) {
			$data['content_data'] = array(
				'data' => (object)array(
					'id' => $criteria_id,
					'variable_name' => $this->input->post('variable_name'),
					'units' => $this->input->post('units'),
					'type_show' => $this->input->post('type_show'),
					'type_field' => $this->input->post('type_field'),
					'variable_value' => $this->input->post('variable_value'),
					'sql_text' => $this->input->post('sql_text'),
				)
			);
			$data['content_view'] = 'pages/form_criteria_assessment';
			$this->load->view($this->theme, $data);
		}
	}


	public function delete_criteria_assessment($id = null)
	{
		$this->Criterias_model->deleteCriterias($id);
		return '';

		// redirect(base_url("criteria_assessments/dashboard_criteria_assessments"));
		// exit;
	}

	public function ajax_get_data($id='')
	{
		$data['content_data'] = array(
			'profile_id' => $id,
			'datas' => (object) $this->Criterias_model->getItemChild($id,0)
		);
		$data['content_view'] = 'ajax/ajax_criteria_left';
		$this->load->view('ajax', $data);
	}

	public function ajax_get_criteria_data($id='')
	{
		$data =  $this->Criterias_model->getCriterias(array('id'=>$id),array('id'=>'asc'))[0];
		echo json_encode($data);
	}

	public function ajax_save_variable()
	{
		$data = array();
		foreach ($_POST as $key => $value) {
			$data[$key] = $this->input->post($key);
		}
		// $this->Criterias_model->insertCriteriaNVariables($data);
		// echo "<pre>";
		// print_r($data);
		$data['content_data'] = array(
			// 'profile_id' => $id,
			'data' => (object) $data,
			'variable_lists' => $this->CriteriaVariables_model->getCriteriaVariableLists()
			// 'activities' => $this->Activities_model->getActivityLists(),
		);
		$data['content_view'] = 'ajax/ajax_add_variable';
		$this->load->view('ajax', $data);
		// echo json_encode($data);
	}

	public function ajax_get_variable($criteria_id){
		$data = array();
		$data['content_data'] = array(
			'datas' => (object) $this->Criterias_model->getCriteriaNVariables(array('criteria_id'=>$criteria_id)),
			'variable_lists' => $this->CriteriaVariables_model->getCriteriaVariableLists()
		);
		$data['content_view'] = 'ajax/ajax_view_variable';
		$this->load->view('ajax', $data);
	}
}
