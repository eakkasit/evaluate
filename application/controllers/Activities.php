<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Activities extends CI_Controller
{
	private $theme = 'default';

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session', 'pagination', 'form_validation'));
		$this->load->model(array('Commons_model','Activities_model'));
		$this->load->helper(array('Commons_helper', 'form', 'url'));

		if ($this->session->userdata('user_id') == '') {
			redirect(base_url("authentications"));
			exit;
		}
	}

	public function index()
	{
		redirect(base_url("activities/dashboard_activity"));
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

	public function dashboard_activity()
	{
		$cond = $this->search_form(array('project_name', 'year', 'date_start', 'date_end', 'status'));

		$config_pager = $this->config->item('pager');
		$config_pager['base_url'] = base_url("activities/dashboard_activity");
		$count_rows = $this->Activities_model->countActivities($cond);
		$config_pager['total_rows'] = $count_rows;
		$this->pagination->initialize($config_pager);
		$page = 0;
		if (isset($_GET['per_page'])) $page = $_GET['per_page'];

		$data['content_data'] = array(
			'search_url' => base_url("activities/dashboard_activity"),
			'status_list' => $this->Commons_model->getProjectStatus(),
			'datas' => $this->Activities_model->getActivities($cond, array(), $config_pager['per_page'], $page),
			'pages' => $this->pagination->create_links(),
			'count_rows' => $count_rows,
		);

		$data['content_view'] = 'pages/dashboard_activity';
		$this->load->view($this->theme, $data);
	}

	public function view_activity($id = null)
	{
		$data['content_data'] = array(
			'status_list'=> $this->Commons_model->getProjectStatus(),
			'year_list' => $this->Commons_model->getYearList(),
			'data' => $this->Activities_model->getActivities(array('id'=>$id))[0]
		);
		$data['content_view'] = 'pages/view_activity';
		$this->load->view($this->theme, $data);
	}

	public function new_activity($id = null)
	{
		$data['content_data'] = array(
			'status_list'=> $this->Commons_model->getProjectStatus(),
			'year_list' => $this->Commons_model->getYearList()
		);
		$data['content_view'] = 'pages/form_activity';
		$this->load->view($this->theme, $data);
	}

	public function edit_activity($id = null)
	{
		$data['content_data'] = array(
			'status_list'=> $this->Commons_model->getProjectStatus(),
			'year_list' => $this->Commons_model->getYearList(),
			'data' => $this->Activities_model->getActivities(array('id'=>$id))[0]
		);
		$data['content_view'] = 'pages/form_activity';
		$this->load->view($this->theme, $data);
	}

	public function validate()
	{
		$this->form_validation->set_rules('project_name', 'ชื่อโครงการ / กิจกรรม', 'required|trim');
		$this->form_validation->set_rules('year', 'ปี', 'required|trim');
		$this->form_validation->set_rules('date_start', 'วันที่เริ่มโครงการ', 'required|trim');
		$this->form_validation->set_rules('date_end', 'วันที่สิ้นสุดโครงการ', 'required|trim');
		$this->form_validation->set_rules('status', 'สถานะโครงการ / กิจกรรม', 'required|trim');

		$this->form_validation->set_message('required', 'กรุณาระบุ {field}');

		return $this->form_validation->run();
	}

	public function save($project_id = null)
	{
		$error_page = false;
		$upload_msg = '';
		$action = 'create';
		if ($project_id != null && $project_id != '') {
			$action = 'update';
		}

		if ($this->validate()) {
			$data = array();
			foreach ($_POST as $key => $value) {
				$data[$key] = $this->input->post($key);
			}

			if ($action == 'create') {
				$project_id = $this->Activities_model->insertActivities($data);
				redirect(base_url("activities/dashboard_activity"));
				exit;
			} else {
				$this->Activities_model->updateActivities($project_id, $data);
				redirect(base_url("activities/dashboard_activity"));
				exit;
			}
		} else {
			$error_page = true;
		}

		if ($error_page) {
			$data['content_data'] = array(
				'status_list'=> $this->Commons_model->getProjectStatus(),
				'year_list' => $this->Commons_model->getYearList(),
				'data' => (object)array(
					'id' => $project_id,
					'project_name' => $this->input->post('project_name'),
					'year' => $this->input->post('year'),
					'date_start' => $this->input->post('date_start'),
					'date_end' => $this->input->post('date_end'),
					'status' => $this->input->post('status'),
					'detail' => $this->input->post('detail')
				)
			);
			$data['content_view'] = 'pages/form_activity';
			$this->load->view($this->theme, $data);
		}
	}



	public function delete_activity($id = null)
	{
		$this->Activities_model->deleteActivities($id);
		redirect(base_url("activities/dashboard_activity"));
		exit;
	}


}
