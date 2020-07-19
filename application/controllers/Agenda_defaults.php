<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agenda_defaults extends CI_Controller
{
	private $theme = 'default';

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session', 'pagination', 'form_validation'));
		$this->load->model(array('Commons_model', 'Logs_model', 'Agendas_model'));
		$this->load->helper(array('Commons_helper', 'form', 'url'));

		if ($this->session->userdata('user_id') == '') {
			redirect(base_url("authentications"));
			exit;
		}
	}

	public function index()
	{
		redirect(base_url("agenda_defaults/dashboard_agenda_defaults"));
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

	public function dashboard_agenda_defaults()
	{
		$cond = $this->search_form(array('agenda_default_name', 'agenda_default_story', 'agenda_default_detail'));

		$config_pager = $this->config->item('pager');
		$config_pager['base_url'] = base_url("agenda_defaults/dashboard_agenda_defaults");
		$count_rows = $this->Agendas_model->countAgendaDefaults($cond);
		$config_pager['total_rows'] = $count_rows;
		$this->pagination->initialize($config_pager);
		$page = 0;
		if (isset($_GET['per_page'])) $page = $_GET['per_page'];

		$data['content_data'] = array(
			'search_url' => base_url("agenda_defaults/dashboard_agenda_defaults"),
			'agenda_defaults' => $this->Agendas_model->getAgendaDefaults($cond, array(), $config_pager['per_page'], $page),

			'pages' => $this->pagination->create_links(),
			'count_rows' => $count_rows,
		);

		$data['content_view'] = 'pages/dashboard_agenda_defaults';
		$this->load->view($this->theme, $data);
	}

	public function view_agenda_default($agenda_default_id = null)
	{
		$agenda_default_data = $this->Agendas_model->getAgendaDefaults(array('agenda_default_id' => $agenda_default_id))[0];
		$data['content_data'] = array(
			'agenda_default_data' => $agenda_default_data,
		);
		$data['content_view'] = 'pages/view_agenda_default';
		$this->load->view($this->theme, $data);
	}

	public function new_agenda_default()
	{
		$data['content_data'] = array();
		$data['content_view'] = 'pages/form_agenda_default';
		$this->load->view($this->theme, $data);
	}

	public function edit_agenda_default($agenda_default_id = null)
	{
		$data['content_data'] = array(
			'agenda_default_data' => $this->Agendas_model->getAgendaDefaults(array('agenda_default_id' => $agenda_default_id))[0]
		);
		$data['content_view'] = 'pages/form_agenda_default';
		$this->load->view($this->theme, $data);
	}

	public function validate()
	{
		$this->form_validation->set_rules('agenda_default_name', 'หัวเรื่อง', 'required|trim');

		$this->form_validation->set_message('required', 'กรุณาระบุ {field}');

		return $this->form_validation->run();
	}

	public function save($agenda_default_id = null)
	{
		$error_page = false;
		$upload_msg = '';
		$action = 'create';
		if ($agenda_default_id != null && $agenda_default_id != '') {
			$action = 'update';
		}

		if ($this->validate()) {
			$data = array();
			foreach ($_POST as $key => $value) {
				$data[$key] = $this->input->post($key);
			}

			if ($action == 'create') {
				$agenda_default_id = $this->Agendas_model->insertAgendaDefault($data);
				redirect(base_url("agenda_defaults/dashboard_agenda_defaults"));
				exit;
			} else {
				$this->Agendas_model->updateAgendaDefault($agenda_default_id, $data);
				redirect(base_url("agenda_defaults/dashboard_agenda_defaults"));
				exit;
			}
		} else {
			$error_page = true;
		}

		if ($error_page) {
			$data['content_data'] = array(
				'agenda_default_data' => (object)array(
					'agenda_default_id' => $agenda_default_id,
					'agenda_default_name' => $this->input->post('agenda_default_name'),
					'agenda_default_story' => $this->input->post('agenda_default_story'),
					'agenda_default_detail' => $this->input->post('agenda_default_detail'),
				)
			);
			$data['content_view'] = 'pages/form_agenda_default';
			$this->load->view($this->theme, $data);
		}
	}

	public function delete_agenda_default($agenda_default_id = null)
	{
		$this->Agendas_model->deleteAgendaDefault($agenda_default_id);
		redirect(base_url("agenda_defaults/dashboard_agenda_defaults"));
		exit;
	}
}
