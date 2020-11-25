<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Variable extends CI_Controller
{
	private $theme = 'default';

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session', 'pagination', 'form_validation'));
		$this->load->model(array('Commons_model', 'Variable_model','ConfigVariables_model'));
		$this->load->helper(array('Commons_helper', 'form', 'url'));

		if ($this->session->userdata('user_id') == '') {
			redirect(base_url("authentications"));
			exit;
		}
	}

	public function index()
	{
		redirect(base_url("variable/dashboard_variable"));
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

	public function dashboard_variable()
	{
		$cond = $this->search_form(array('var_name'));

		$config_pager = $this->config->item('pager');
		$config_pager['base_url'] = base_url("variable/dashboard_variable");
		$count_rows = $this->Variable_model->countVariable($cond);
		$config_pager['total_rows'] = $count_rows;
		$this->pagination->initialize($config_pager);
		$page = 0;
		if (isset($_GET['per_page'])) $page = $_GET['per_page'];

		$data['content_data'] = array(
			'search_url' => base_url("variable/dashboard_variable"),
			'show_type_list'=> $this->Commons_model->getShowTypeList(),
			'field_type_list'=> $this->Commons_model->getFieldTypeList(),
			'units_list'=> $this->Commons_model->getUnitsList(),
			'datas' => $this->Variable_model->getVariable($cond, array(), $config_pager['per_page'], $page),
			'pages' => $this->pagination->create_links(),
			'count_rows' => $count_rows,
		);

		$data['content_view'] = 'pages/dashboard_variable';
		$this->load->view($this->theme, $data);
	}

	public function view_variable($id = null)
	{
		$data['content_data'] = array(
			'show_type_list'=> $this->Commons_model->getShowTypeList(),
			'field_type_list'=> $this->Commons_model->getFieldTypeList(),
			'units_list'=> $this->Commons_model->getUnitsList(),
			'data' => $this->Variable_model->getVariable(array('var_id'=>$id))[0]
		);
		$data['content_view'] = 'pages/view_variable';
		$this->load->view($this->theme, $data);
	}

	public function new_variable($id = null)
	{
		$data['content_data'] = array(
			'show_type_list'=> $this->Commons_model->getShowTypeList(),
			'field_type_list'=> $this->Commons_model->getFieldTypeList(),
			'units_list'=> $this->Commons_model->getUnitsList(),
			'variable_system'=> $this->ConfigVariables_model->getConfigVariablesLists(),
		);
		$data['content_view'] = 'pages/form_variable';
		$this->load->view($this->theme, $data);
	}

	public function edit_variable($id = null)
	{
		$data['content_data'] = array(
			'show_type_list'=> $this->Commons_model->getShowTypeList(),
			'field_type_list'=> $this->Commons_model->getFieldTypeList(),
			'units_list'=> $this->Commons_model->getUnitsList(),
			'data' => $this->Variable_model->getVariable(array('var_id'=>$id))[0],
			'variable_system'=> $this->ConfigVariables_model->getConfigVariablesLists(),
		);
		$data['content_view'] = 'pages/form_variable';
		$this->load->view($this->theme, $data);
	}

	public function validate()
	{
		$this->form_validation->set_rules('var_name', 'ชื่อตัวแปรเกณฑ์การประเมิน', 'required|trim');
		$this->form_validation->set_rules('var_unit_id', 'หน่วยวัด', 'required|trim');
		$this->form_validation->set_rules('var_type_id', 'ชนิดของการแสดงผล', 'required|trim');
		$this->form_validation->set_rules('var_import_id', 'ประเภทการนำเข้าข้อมูล', 'required|trim');
		$this->form_validation->set_rules('var_value', 'ค่าตัวแปร', 'required|trim');
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
				$profile_id = $this->Variable_model->insertVariable($data);
				redirect(base_url("variable/dashboard_variable"));
				exit;
			} else {
				$this->Variable_model->updateVariable($profile_id, $data);
				redirect(base_url("variable/dashboard_variable"));
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
					'var_name' => $this->input->post('var_name'),
					'var_unit_id' => $this->input->post('var_unit_id'),
					'var_type_id' => $this->input->post('var_type_id'),
					'var_import_id' => $this->input->post('var_import_id'),
					'var_max_length' => $this->input->post('var_max_length'),
					'var_value' => $this->input->post('var_value'),
					'var_sql' => $this->input->post('var_sql'),
				)
			);
			$data['content_view'] = 'pages/form_variable';
			$this->load->view($this->theme, $data);
		}
	}


	public function delete_variable($id = null)
	{
		$this->Variable_model->deleteVariable($id);
		redirect(base_url("variable/dashboard_variable"));
		exit;
	}


}
