<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Criteria_datas extends CI_Controller
{
	private $theme = 'default';

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session', 'pagination', 'form_validation'));
		$this->load->model(array('Commons_model', 'CriteriaProfiles_model','Criterias_model','CriteriaDatas_model'));
		$this->load->helper(array('Commons_helper', 'form', 'url'));

		if ($this->session->userdata('user_id') == '') {
			redirect(base_url("authentications"));
			exit;
		}
	}

	public function index()
	{
		redirect(base_url("criteria_datas/dashboard_criteria_datas"));
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

	public function dashboard_criteria_datas()
	{

		$data['content_data'] = array(
			'profiles' => $this->CriteriaProfiles_model->getCriteriaProfileLists(),
			'datas' => $this->CriteriaDatas_model->getCriteriaDatas()
		);

		$data['content_view'] = 'pages/dashboard_criteria_data';
		$this->load->view($this->theme, $data);
	}

	public function view_criteria_data($id = null)
	{
		$data['content_data'] = array(
			'profiles' => $this->CriteriaProfiles_model->getCriteriaProfileLists(),
			'criteria_profiles'=>$this->CriteriaProfiles_model->getCriteriaProfiles(),
			'data'=> $this->CriteriaDatas_model->getCriteriaDatas(array('id'=>$id))[0]
		);
		$data['content_view'] = 'pages/view_criteria_data';
		$this->load->view($this->theme, $data);
	}

	public function new_criteria_data($id = null)
	{
		$data['content_data'] = array(
			'criteria_profiles'=>$this->CriteriaProfiles_model->getCriteriaProfiles()
		);
		$data['content_view'] = 'pages/form_criteria_data';
		$this->load->view($this->theme, $data);
	}

	public function edit_criteria_data($id = null)
	{
		$data['content_data'] = array(
			'criteria_profiles'=>$this->CriteriaProfiles_model->getCriteriaProfiles(),
			'data'=> $this->CriteriaDatas_model->getCriteriaDatas(array('id'=>$id))[0]
		);
		$data['content_view'] = 'pages/form_criteria_data';
		$this->load->view($this->theme, $data);
	}

	public function validate()
	{
		$this->form_validation->set_rules('name', 'ชื่อการประเมิน', 'required|trim');
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
				$criteria_id = $this->CriteriaDatas_model->insertCriteriaDatas($data);
				redirect(base_url("criteria_datas/dashboard_criteria_datas"));
				exit;
			} else {
				$this->CriteriaDatas_model->updateCriteriaDatas($criteria_id, $data);
				redirect(base_url("criteria_datas/dashboard_criteria_datas"));
				exit;
			}
		} else {
			$error_page = true;
		}

		if ($error_page) {
			$data['content_data'] = array(
				'data' => (object)array(
					'id' => $criteria_id,
					'name' => $this->input->post('name'),
					'profile_id' => $this->input->post('profile_id'),
					'detail' => $this->input->post('detail'),
				)
			);
			$data['content_view'] = 'pages/form_criteria_data';
			$this->load->view($this->theme, $data);
		}
	}



	public function delete_criteria_data($id = null)
	{
		redirect(base_url("criteria_datas/dashboard_criteria_datas"));
		exit;
	}


}
