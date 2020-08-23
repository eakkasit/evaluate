<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Units extends CI_Controller
{
	private $theme = 'default';

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session', 'pagination', 'form_validation'));
		$this->load->model(array('Commons_model', 'Units_model'));
		$this->load->helper(array('Commons_helper', 'form', 'url'));

		if ($this->session->userdata('user_id') == '') {
			redirect(base_url("authentications"));
			exit;
		}
	}

	public function index()
	{
		redirect(base_url("units/dashboard_units"));
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

	public function dashboard_units()
	{
		$cond = $this->search_form(array('unit_name'));

		$config_pager = $this->config->item('pager');
		$config_pager['base_url'] = base_url("units/dashboard_units");
		$count_rows = $this->Units_model->countUnits($cond);
		$config_pager['total_rows'] = $count_rows;
		$this->pagination->initialize($config_pager);
		$page = 0;
		if (isset($_GET['per_page'])) $page = $_GET['per_page'];

		$data['content_data'] = array(
			'search_url' => base_url("units/dashboard_units"),
			'datas' => $this->Units_model->getUnits($cond, array(), $config_pager['per_page'], $page),
			'pages' => $this->pagination->create_links(),
			'count_rows' => $count_rows,
		);

		$data['content_view'] = 'pages/dashboard_units';
		$this->load->view($this->theme, $data);
	}

	public function view_units($id = null)
	{
		$data['content_data'] = array(
			'data' => $this->Units_model->getUnits(array('unit_id'=>$id))[0]
		);
		$data['content_view'] = 'pages/view_units';
		$this->load->view($this->theme, $data);
	}

	public function new_units($id = null)
	{
		$data['content_data'] = array();
		$data['content_view'] = 'pages/form_units';
		$this->load->view($this->theme, $data);
	}

	public function edit_units($id = null)
	{
		$data['content_data'] = array(
			'data' => $this->Units_model->getUnits(array('unit_id'=>$id))[0]
		);
		$data['content_view'] = 'pages/form_units';
		$this->load->view($this->theme, $data);
	}

	public function validate()
	{
		$this->form_validation->set_rules('unit_name', 'หน่วยวัด', 'required|trim');
		$this->form_validation->set_message('required', 'กรุณาระบุ {field}');

		return $this->form_validation->run();
	}

	public function save($unit_id = null)
	{
		$error_page = false;
		$upload_msg = '';
		$action = 'create';
		if ($unit_id != null && $unit_id != '') {
			$action = 'update';
		}

		if ($this->validate()) {
			$data = array();
			foreach ($_POST as $key => $value) {
				$data[$key] = $this->input->post($key);
			}

			if ($action == 'create') {
				$unit_id = $this->Units_model->insertUnits($data);
				redirect(base_url("units/dashboard_units"));
				exit;
			} else {
				$this->Units_model->updateUnits($unit_id, $data);
				redirect(base_url("units/dashboard_units"));
				exit;
			}
		} else {
			$error_page = true;
		}

		if ($error_page) {
			$data['content_data'] = array(
				'data' => (object)array(
					'id' => $unit_id,
					'unit_name' => $this->input->post('unit_name'),
				)
			);
			$data['content_view'] = 'pages/form_units';
			$this->load->view($this->theme, $data);
		}
	}


	public function delete_units($id = null)
	{
		$this->Units_model->deleteUnits($id);
		redirect(base_url("units/dashboard_units"));
		exit;
	}


}
