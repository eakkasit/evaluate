<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Criteria_variables extends CI_Controller
{
	private $theme = 'default';

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session', 'pagination', 'form_validation'));
		$this->load->model(array('Commons_model', 'CriteriaVariables_model'));
		$this->load->helper(array('Commons_helper', 'form', 'url'));

		if ($this->session->userdata('user_id') == '') {
			redirect(base_url("authentications"));
			exit;
		}
	}

	public function index()
	{
		redirect(base_url("criteria_variables/dashboard_criteria_variables"));
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

	public function dashboard_criteria_variables()
	{
		$cond = $this->search_form(array('variable_name'));

		$config_pager = $this->config->item('pager');
		$config_pager['base_url'] = base_url("criteria_variables/dashboard_criteria_variables");
		$count_rows = $this->CriteriaVariables_model->countCriteriaVariables($cond);
		$config_pager['total_rows'] = $count_rows;
		$this->pagination->initialize($config_pager);
		$page = 0;
		if (isset($_GET['per_page'])) $page = $_GET['per_page'];

		$data['content_data'] = array(
			'search_url' => base_url("criteria_variables/dashboard_criteria_variables"),
			'show_type_list'=> $this->Commons_model->getShowTypeList(),
			'field_type_list'=> $this->Commons_model->getFieldTypeList(),
			'units_list'=> $this->Commons_model->getUnitsList(),
			'datas' => $this->CriteriaVariables_model->getCriteriaVariables($cond, array(), $config_pager['per_page'], $page),
			'pages' => $this->pagination->create_links(),
			'count_rows' => $count_rows,
		);

		$data['content_view'] = 'pages/dashboard_criteria_variable';
		$this->load->view($this->theme, $data);
	}

	public function view_criteria_variable($id = null)
	{
		$data['content_data'] = array(
			'show_type_list'=> $this->Commons_model->getShowTypeList(),
			'field_type_list'=> $this->Commons_model->getFieldTypeList(),
			'units_list'=> $this->Commons_model->getUnitsList(),
			'data' => $this->CriteriaVariables_model->getCriteriaVariables(array('id'=>$id))[0]
		);
		$data['content_view'] = 'pages/view_criteria_variable';
		$this->load->view($this->theme, $data);
	}

	public function new_criteria_variable($id = null)
	{
		$data['content_data'] = array(
			'show_type_list'=> $this->Commons_model->getShowTypeList(),
			'field_type_list'=> $this->Commons_model->getFieldTypeList(),
			'units_list'=> $this->Commons_model->getUnitsList(),
		);
		$data['content_view'] = 'pages/form_criteria_variable';
		$this->load->view($this->theme, $data);
	}

	public function edit_criteria_variable($id = null)
	{
		$data['content_data'] = array(
			'show_type_list'=> $this->Commons_model->getShowTypeList(),
			'field_type_list'=> $this->Commons_model->getFieldTypeList(),
			'units_list'=> $this->Commons_model->getUnitsList(),
			'data' => $this->CriteriaVariables_model->getCriteriaVariables(array('id'=>$id))[0]
		);
		$data['content_view'] = 'pages/form_criteria_variable';
		$this->load->view($this->theme, $data);
	}

	public function validate()
	{
		$this->form_validation->set_rules('variable_name', 'ชื่อตัวแปรเกณฑ์การประเมิน', 'required|trim');
		$this->form_validation->set_rules('units', 'หน่วยวัด', 'required|trim');
		$this->form_validation->set_rules('type_show', 'ชนิดของการแสดงผล', 'required|trim');
		$this->form_validation->set_rules('type_field', 'ประเภทการนำเข้าข้อมูล', 'required|trim');
		$this->form_validation->set_rules('variable_value', 'ค่าตัวแปร', 'required|trim');
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
				$profile_id = $this->CriteriaVariables_model->insertCriteriaVariables($data);
				redirect(base_url("criteria_variables/dashboard_criteria_variables"));
				exit;
			} else {
				$this->CriteriaVariables_model->updateCriteriaVariables($profile_id, $data);
				redirect(base_url("criteria_variables/dashboard_criteria_variables"));
				exit;
			}
		} else {
			$error_page = true;
		}

		if ($error_page) {
			$data['content_data'] = array(
				'show_type_list'=> $this->Commons_model->getShowTypeList(),
				'field_type_list'=> $this->Commons_model->getFieldTypeList(),
				'units_list'=> $this->Commons_model->getUnitsList(),
				'data' => (object)array(
					'id' => $profile_id,
					'variable_name' => $this->input->post('variable_name'),
					'units' => $this->input->post('units'),
					'type_show' => $this->input->post('type_show'),
					'type_field' => $this->input->post('type_field'),
					'variable_value' => $this->input->post('variable_value'),
					'sql_text' => $this->input->post('sql_text'),
				)
			);
			$data['content_view'] = 'pages/form_criteria_variable';
			$this->load->view($this->theme, $data);
		}
	}


	public function delete_criteria_variable($id = null)
	{
		redirect(base_url("criteria_variables/dashboard_criteria_variables"));
		exit;
	}


}
