<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Criteria_themes extends CI_Controller
{
	private $theme = 'default';

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session', 'pagination', 'form_validation'));
		$this->load->model(array('Commons_model','CriteriaProfiles_model'));
		$this->load->helper(array('Commons_helper', 'form', 'url'));

		if ($this->session->userdata('user_id') == '') {
			redirect(base_url("authentications"));
			exit;
		}
	}

	public function index()
	{
		redirect(base_url("criteria_themes/dashboard_criteria_themes"));
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

	public function dashboard_criteria_themes()
	{
		$cond = $this->search_form(array('profile_name', 'year', 'detail', 'status'));

		$config_pager = $this->config->item('pager');
		$config_pager['base_url'] = base_url("criteria_themes/dashboard_criteria_themes");
		$count_rows = $this->CriteriaProfiles_model->countCriteriaProfiles($cond);
		$config_pager['total_rows'] = $count_rows;
		$this->pagination->initialize($config_pager);
		$page = 0;
		if (isset($_GET['per_page'])) $page = $_GET['per_page'];

		$data['content_data'] = array(
			'search_url' => base_url("criteria_themes/dashboard_criteria_themes"),
			'status_list' => $this->Commons_model->getActiveList(),
			'datas' => $this->CriteriaProfiles_model->getCriteriaProfiles($cond, array(), $config_pager['per_page'], $page),
			'pages' => $this->pagination->create_links(),
			'count_rows' => $count_rows,
		);

		$data['content_view'] = 'pages/dashboard_criteria_theme';
		$this->load->view($this->theme, $data);
	}

	public function view_criteria_theme($id = null)
	{
		$data['content_data'] = array(
			'status_list' => $this->Commons_model->getActiveList(),
			'year_list' => $this->Commons_model->getYearList(),
			'data' => $this->CriteriaProfiles_model->getCriteriaProfiles(array('id'=>$id))[0]
		);
		$data['content_view'] = 'pages/view_criteria_theme';
		$this->load->view($this->theme, $data);
	}

	public function new_criteria_theme($id = null)
	{
		$data['content_data'] = array(
			'status_list' => $this->Commons_model->getActiveList(),
			'year_list' => $this->Commons_model->getYearList(),
		);
		$data['content_view'] = 'pages/form_criteria_theme';
		$this->load->view($this->theme, $data);
	}

	public function edit_criteria_theme($id = null)
	{
		$data['content_data'] = array(
			'status_list' => $this->Commons_model->getActiveList(),
			'year_list' => $this->Commons_model->getYearList(),
			'data' => $this->CriteriaProfiles_model->getCriteriaProfiles(array('id'=>$id))[0]
		);
		$data['content_view'] = 'pages/form_criteria_theme';
		$this->load->view($this->theme, $data);
	}

	public function validate()
	{
		$this->form_validation->set_rules('profile_name', 'ชื่อแม่แบบเกณฑ์การประเมิน', 'required|trim');
		$this->form_validation->set_rules('year', 'ปี', 'required|trim');
		$this->form_validation->set_rules('status', 'สถานะการใช้งาน', 'required|trim');
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
				$profile_id = $this->CriteriaProfiles_model->insertCriteriaProfiles($data);
				redirect(base_url("criteria_themes/dashboard_criteria_themes"));
				exit;
			} else {
				$this->CriteriaProfiles_model->updateCriteriaProfiles($profile_id, $data);
				redirect(base_url("criteria_themes/dashboard_criteria_themes"));
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
					'profile_name' => $this->input->post('profile_name'),
					'year' => $this->input->post('year'),
					'status' => $this->input->post('status'),
					'detail' => $this->input->post('detail')
				)
			);
			$data['content_view'] = 'pages/form_criteria_theme';
			$this->load->view($this->theme, $data);
		}
	}



	public function delete_criteria_theme($id = null)
	{
		redirect(base_url("criteria_themes/dashboard_criteria_themes"));
		exit;
	}


}
