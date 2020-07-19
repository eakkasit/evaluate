<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agenda_types extends CI_Controller
{
	private $theme = 'default';

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session', 'pagination', 'form_validation'));
		$this->load->model(array('Commons_model', 'Logs_model', 'AgendaTypes_model'));
		$this->load->helper(array('Commons_helper', 'form', 'url'));

		if ($this->session->userdata('user_id') == '') {
			redirect(base_url("authentications"));
			exit;
		}
	}

	public function index()
	{
		redirect(base_url("agenda_types/dashboard_agenda_types"));
		exit;
	}

	public function search_form($fields = array())
	{
		$cond = array();
		if ($this->input->post('form_search_element') && !empty($fields)) {
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

	public function dashboard_agenda_types()
	{
		$cond = $this->search_form(array('agenda_type_name'));

		$config_pager = $this->config->item('pager');
		$config_pager['base_url'] = base_url("agenda_types/dashboard_agenda_types");
		$count_rows = $this->AgendaTypes_model->countAgendaTypes($cond);
		$config_pager['total_rows'] = $count_rows;
		$this->pagination->initialize($config_pager);
		$page = 0;
		if (isset($_GET['per_page'])) $page = $_GET['per_page'];

		$data['content_data'] = array(
			'search_url' => base_url("agenda_types/dashboard_agenda_types"),
			'agenda_types' => $this->AgendaTypes_model->getAgendaTypes($cond, array(), $config_pager['per_page'], $page),

			'pages' => $this->pagination->create_links(),
			'count_rows' => $count_rows,
		);

		$data['content_view'] = 'pages/dashboard_agenda_types';
		$this->load->view($this->theme, $data);
	}

	public function view_agenda_type($agenda_type_id = null)
	{
		$agenda_type_data = $this->AgendaTypes_model->getAgendaTypes(array('agenda_type_id' => $agenda_type_id))[0];
		$data['content_data'] = array(
			'agenda_type_data' => $agenda_type_data,
		);
		$data['content_view'] = 'pages/view_agenda_type';
		$this->load->view($this->theme, $data);
	}

	public function new_agenda_type()
	{
		$data['content_data'] = array();
		$data['content_view'] = 'pages/form_agenda_type';
		$this->load->view($this->theme, $data);
	}

	public function edit_agenda_type($agenda_type_id = null)
	{
		$data['content_data'] = array(
			'agenda_type_data' => $this->AgendaTypes_model->getAgendaTypes(array('agenda_type_id' => $agenda_type_id))[0]
		);
		$data['content_view'] = 'pages/form_agenda_type';
		$this->load->view($this->theme, $data);
	}

	public function validate()
	{
		$this->form_validation->set_rules('agenda_type_name', 'ประเภทวาระการประชุม', 'required|trim');

		$this->form_validation->set_message('required', 'กรุณาระบุ {field}');

		return $this->form_validation->run();
	}

	public function save($agenda_type_id = null)
	{
		$error_page = false;
		$upload_msg = '';
		$action = 'create';
		if ($agenda_type_id != null && $agenda_type_id != '') {
			$action = 'update';
		}

		if ($this->validate()) {
			$data = array();
			foreach ($_POST as $key => $value) {
				$data[$key] = $this->input->post($key);
			}

			if ($action == 'create') {
				$agenda_type_id = $this->AgendaTypes_model->insertAgendaType($data);
				redirect(base_url("agenda_types/dashboard_agenda_types"));
				exit;
			} else {
				$this->AgendaTypes_model->updateAgendaType($agenda_type_id, $data);
				redirect(base_url("agenda_types/dashboard_agenda_types"));
				exit;
			}
		} else {
			$error_page = true;
		}

		if ($error_page) {
			$data['content_data'] = array(
				'agenda_type_data' => (object)array(
					'agenda_type_id' => $agenda_type_id,
					'agenda_type_name' => $this->input->post('agenda_type_name'),
				)
			);
			$data['content_view'] = 'pages/form_agenda_type';
			$this->load->view($this->theme, $data);
		}
	}

	public function delete_agenda_type($agenda_type_id = null)
	{
		$this->AgendaTypes_model->deleteAgendaType($agenda_type_id);
		redirect(base_url("agenda_types/dashboard_agenda_types"));
		exit;
	}
}
