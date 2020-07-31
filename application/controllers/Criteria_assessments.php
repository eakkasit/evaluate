<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Criteria_assessments extends CI_Controller
{
	private $theme = 'default';

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session', 'pagination', 'form_validation'));
		$this->load->model(array('Commons_model', 'CriteriaProfiles_model','Criterias_model'));
		$this->load->helper(array('Commons_helper', 'form', 'url'));

		if ($this->session->userdata('user_id') == '') {
			redirect(base_url("authentications"));
			exit;
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
			'datas' => $this->Criterias_model->getCriterias()
		);
		$data['content_view'] = 'pages/view_criteria_assessment';
		$this->load->view($this->theme, $data);
	}

	public function new_criteria_assessment($id = null)
	{
		$data['content_data'] = array(
			'profile_id' => $id,
			'datas' => $this->Criterias_model->getCriterias(array('profile_id'=>$id),array('id'=>'asc'))
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

			if ($action == 'create') {
				$criteria_id = $this->Criterias_model->insertCriterias($data);
				redirect(base_url("criteria_assessments/dashboard_criteria_assessments"));
				exit;
			} else {
				$this->Criterias_model->updateCriterias($criteria_id, $data);
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
		redirect(base_url("criteria_assessments/dashboard_criteria_assessments"));
		exit;
	}

	public function ajax_get_data($id='')
	{
		// $test_data = $this->Criterias_model->getItemChild($id,0);
		// echo "<pre>";
		// print_r($test_data);
		// die();
		$data['content_data'] = array(
			'profile_id' => $id,
			// 'datas' => $this->Criterias_model->getCriterias(array('profile_id'=>$id),array('id'=>'asc'))
			'datas' =>  $this->Criterias_model->getItemChild($id,0)
		);
		$data['content_view'] = 'ajax/ajax_criteria_left';
		$this->load->view('ajax', $data);
	}
}
