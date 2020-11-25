<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Config_variables extends CI_Controller
{
	private $theme = 'default';

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session', 'pagination', 'form_validation'));
		$this->load->model(array('Commons_model', 'ConfigVariables_model'));
		$this->load->helper(array('Commons_helper', 'form', 'url'));

		if ($this->session->userdata('user_id') == '') {
			redirect(base_url("authentications"));
			exit;
		}
	}

	public function index()
	{
		redirect(base_url("config_variables/dashboard_config_variables"));
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

	public function dashboard_config_variables()
	{
		$cond = $this->search_form(array('unit_name'));

		$config_pager = $this->config->item('pager');
		$config_pager['base_url'] = base_url("config_variables/dashboard_config_variables");
		$count_rows = $this->ConfigVariables_model->countConfigVariables($cond);
		$config_pager['total_rows'] = $count_rows;
		$this->pagination->initialize($config_pager);
		$page = 0;
		if (isset($_GET['per_page'])) $page = $_GET['per_page'];

		$data['content_data'] = array(
			'search_url' => base_url("config_variables/dashboard_config_variables"),
			'datas' => $this->ConfigVariables_model->getConfigVariables($cond, array(), $config_pager['per_page'], $page),
			'pages' => $this->pagination->create_links(),
			'count_rows' => $count_rows,
		);

		$data['content_view'] = 'pages/dashboard_config_variables';
		$this->load->view($this->theme, $data);
	}

	public function view_config_variables($id = null)
	{
		$data['content_data'] = array(
			'data' => $this->ConfigVariables_model->getConfigVariables(array('id'=>$id))[0]
		);
		$data['content_view'] = 'pages/view_config_variables';
		$this->load->view($this->theme, $data);
	}

	public function new_config_variables($id = null)
	{
		$data['content_data'] = array();
		$data['content_view'] = 'pages/form_config_variables';
		$this->load->view($this->theme, $data);
	}

	public function edit_config_variables($id = null)
	{
		$data['content_data'] = array(
			'data' => $this->ConfigVariables_model->getConfigVariables(array('id'=>$id))[0]
		);
		$data['content_view'] = 'pages/form_config_variables';
		$this->load->view($this->theme, $data);
	}

	public function validate()
	{
		$this->form_validation->set_rules('name', 'ชื่อตัวแปรจากระบบ', 'required|trim');
		$this->form_validation->set_rules('sql', 'sql', 'required|trim');
		$this->form_validation->set_message('required', 'กรุณาระบุ {field}');

		return $this->form_validation->run();
	}

	public function save($id = null)
	{
		$error_page = false;
		$upload_msg = '';
		$action = 'create';
		if ($id != null && $id != '') {
			$action = 'update';
		}

		if ($this->validate()) {
			$data = array();
			foreach ($_POST as $key => $value) {
				$data[$key] = $this->input->post($key);
			}

			if ($action == 'create') {
				$id = $this->ConfigVariables_model->insertConfigVariables($data);
				redirect(base_url("config_variables/dashboard_config_variables"));
				exit;
			} else {
				$this->ConfigVariables_model->updateConfigVariables($id, $data);
				redirect(base_url("config_variables/dashboard_config_variables"));
				exit;
			}
		} else {
			$error_page = true;
		}

		if ($error_page) {
			$data['content_data'] = array(
				'data' => (object)array(
					'id' => $id,
					'unit_name' => $this->input->post('unit_name'),
				)
			);
			$data['content_view'] = 'pages/form_config_variables';
			$this->load->view($this->theme, $data);
		}
	}


	public function delete_config_variables($id = null)
	{
		$this->ConfigVariables_model->deleteConfigVariables($id);
		redirect(base_url("config_variables/dashboard_config_variables"));
		exit;
	}


}
