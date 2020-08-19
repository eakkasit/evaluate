<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Criteria extends CI_Controller
{
	private $theme = 'default';

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session', 'pagination', 'form_validation'));
		$this->load->model(array('Commons_model','Structure_model','KpiTree_model','Kpi_model'));
		$this->load->helper(array('Commons_helper', 'form', 'url'));

		if ($this->session->userdata('user_id') == '') {
			redirect(base_url("authentications"));
			exit;
		}
	}

	public function index()
	{
		redirect(base_url("criteria/dashboard_criteria"));
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

	public function dashboard_criteria()
	{
		$cond = $this->search_form(array('profile_name', 'year', 'detail', 'status'));

		$config_pager = $this->config->item('pager');
		$config_pager['base_url'] = base_url("criteria/dashboard_criteria");
		$count_rows = $this->Structure_model->countStructure($cond);
		$config_pager['total_rows'] = $count_rows;
		$this->pagination->initialize($config_pager);
		$page = 0;
		if (isset($_GET['per_page'])) $page = $_GET['per_page'];

		$data['content_data'] = array(
			'search_url' => base_url("criteria/dashboard_criteria"),
			'status_list' => $this->Commons_model->getActiveList(),
			'datas' => $this->Structure_model->getStructure($cond, array(), $config_pager['per_page'], $page),
			'pages' => $this->pagination->create_links(),
			'count_rows' => $count_rows,
		);

		$data['content_view'] = 'pages/dashboard_criteria';
		$this->load->view($this->theme, $data);
	}

	public function view_criteria($id = null)
	{
		$data['content_data'] = array(
			'status_list' => $this->Commons_model->getActiveList(),
			'year_list' => $this->Commons_model->getYearList(),
			'data' => $this->Structure_model->getStructure(array('id'=>$id))[0]
		);
		$data['content_view'] = 'pages/view_criteria';
		$this->load->view($this->theme, $data);
	}

	public function new_criteria($id = null)
	{
		$data['content_data'] = array(
			'status_list' => $this->Commons_model->getActiveList(),
			'year_list' => $this->Commons_model->getYearList(),
		);
		$data['content_view'] = 'pages/form_criteria';
		$this->load->view($this->theme, $data);
	}

	public function edit_criteria($id = null)
	{
		$data['content_data'] = array(
			// 'status_list' => $this->Commons_model->getActiveList(),
			// 'year_list' => $this->Commons_model->getYearList(),
			// 'data' => $this->Structure_model->getStructure(array('criteria_id'=>$id))[0]
			'structure_id'=>$id
		);
		$data['content_view'] = 'pages/form_criteria';
		$this->load->view($this->theme, $data);
	}

	public function validate()
	{
		$this->form_validation->set_rules('criteria_name', 'ชื่อแม่แบบเกณฑ์การประเมิน', 'required|trim');
		$this->form_validation->set_rules('profile_year', 'ปี', 'required|trim');
		$this->form_validation->set_rules('criteria_status', 'สถานะการใช้งาน', 'required|trim');
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

			if ($action == 'create') {
				$criteria_id = $this->Structure_model->insertStructure($data);
				redirect(base_url("criteria/dashboard_criteria"));
				exit;
			} else {
				$this->Structure_model->updateStructure($criteria_id, $data);
				redirect(base_url("criteria/dashboard_criteria"));
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
					'criteria_id' => $criteria_id,
					'criteria_name' => $this->input->post('criteria_name'),
					'profile_name' => $this->input->post('profile_year'),
					'criteria_status' => $this->input->post('criteria_status')
				)
			);
			$data['content_view'] = 'pages/form_criteria';
			$this->load->view($this->theme, $data);
		}
	}

	public function delete_criteria($id = null)
	{
		$this->Structure_model->deleteStructure($id);
		redirect(base_url("criteria/dashboard_criteria"));
		exit;
	}

	public function ajax_kpi_tree($id='')
	{
		echo $this->KpiTree_model->getTreeFormList($id,0,'');
	}

}
